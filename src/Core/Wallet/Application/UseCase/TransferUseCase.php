<?php

namespace Core\Wallet\Application\UseCase;

use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Core\SeedWork\Domain\Enum\UserType;
use Core\SeedWork\Domain\Notification\Notification;
use Core\SeedWork\Domain\Services\HttpServiceInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Domain\Entities\User;
use Core\User\Domain\Repository\UserRepositoryInterface;
use Core\Wallet\Application\DTO\InputTransferWalletDTO;
use Core\Wallet\Application\DTO\OutputTransferWalletDTO;
use Core\Wallet\Domain\Entities\TransferHistory;
use Core\Wallet\Domain\Entities\Wallet;
use Core\Wallet\Domain\Events\TransferEvent;
use Core\Wallet\Domain\Repository\TransferHistoryRepositoryInterface;
use Core\Wallet\Domain\Repository\WalletRepositoryInterface;
use Core\Wallet\Interfaces\TransferEventManagerInterface;
use Psr\Log\LoggerInterface;

class TransferUseCase
{
    public const ERROR_USER_NOT_FOUND = 'User not found';
    public const ERROR_UNAUTHORIZED_TRANSFER = 'Unauthorized transfer';
    public const ERROR_MERCHANT_CANNOT_TRANSFER = 'Merchant cannot transfer';
    public const ERROR_WALLET_NOT_FOUND = 'Wallet not found';
    public const ERROR_INSUFFICIENT_BALANCE = 'Insufficient balance';
    public const ERROR_FAILED_TO_AUTHORIZE_TRANSFER = 'Failed to authorize transfer ';
    public const ERROR_INVALID_CREDENTIALS = 'The provided email or password for the user is invalid';
    public const MESSAGE_SUCCESS = 'Transfer completed successfully!';

    private const EXTERNAL_SERVICE_AUTHORIZE_TRANSFER_URL = 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc';
    private const EXTERNAL_SERVICE_SEND_TRANSFER_EVENT_URL = 'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6';

    private Notification $notification;

    public function __construct(
        private WalletRepositoryInterface $walletRepositoryInterface,
        private TransferHistoryRepositoryInterface $transferHistoryUseCase,
        private UserRepositoryInterface $UserRepositoryInterface,
        private TransferEventManagerInterface $transferEventManagerInterface,
        private HttpServiceInterface $httpService,
        private LoggerInterface $logger,
        private TransactionInterface $transaction
    ) {
        $this->notification = new Notification();
    }

    /**
     * Recupera o usuário com o ID de usuário.
     *
     * @param Uuid $userId o id do usuário para ser recuperado.
     * @return User o objeto usuário.
     */
    private function getUser(Uuid $userId): User
    {
        $user = $this->UserRepositoryInterface->findById($userId);

        if (!$user) {
            $this->notification->addError(self::ERROR_USER_NOT_FOUND);
        }

        return $user;
    }

    /**
     * Valida se o usuario e lojistas
     *
     * @param User $user The user object to validate.
     * @return void
     */
    private function validateUserType(User $user): void
    {
        if ($user->getType() == UserType::MERCHANT->value) {
            $this->notification->addError(self::ERROR_MERCHANT_CANNOT_TRANSFER);
        }
    }

    /**
     * Recupera a carteira do usuário.
     *
     * @param Uuid $userId id do usuario para retonar a carteira.
     * @return Wallet objeto carteira.
     */
    private function getWallet(Uuid $userId): Wallet
    {
        $wallet = $this->walletRepositoryInterface->findWalletByUserId($userId);

        if (!$wallet) {
            $this->notification->addError(self::ERROR_WALLET_NOT_FOUND);
        }

        return $wallet;
    }

    /**
     * Valida o saldo de uma carteira.
     *
     * @param Wallet $wallet objeto carteira.
     * @param float $value valor da transferência.
     * @return void
     */
    private function validateBalance(Wallet $wallet, float $value): void
    {
        if ($wallet->getBalance() < $value) {
            $this->notification->addError(self::ERROR_INSUFFICIENT_BALANCE);
        }
    }

    /**
     * Valida e autoriza a transferência
     *
     * Este método é responsável por validar e autorizar a transferência.
     *
     * @return void
     */
    private function validateAuthorizeTransfer(): void
    {
        try {
            $data = $this->httpService->get(self::EXTERNAL_SERVICE_AUTHORIZE_TRANSFER_URL);
    
            if (!isset($data['message']) || $data['message'] !== 'Autorizado') {
                $this->notification->addError(self::ERROR_UNAUTHORIZED_TRANSFER);
            }
        } catch (\Exception $e) {
            $this->logger->error("validateAuthorizeTransfer " . date('Y-m-d H:i:s') . " Error: " . $e->getMessage());
            $this->notification->addError(self::ERROR_FAILED_TO_AUTHORIZE_TRANSFER);
        }
    }

    /**
     * Verifica se o serviço de notificação está disponível.
     *
     * @return bool returna true se o serviço de notificação estiver disponível; caso contrário, retorna falso.
     */
    private function checkNotificationService(): bool
    {
        try {
            $data = $this->httpService->get(self::EXTERNAL_SERVICE_SEND_TRANSFER_EVENT_URL);

            if ($data['message']) {
                return true;
            }
        } catch (\Exception $e) {
            $this->logger->error("checkNotificationService " . date('Y-m-d H:i:s') . " Error: " . $e->getMessage());
        }
        return false;
    }

    /**
     * Envia um evento de transferência
     *
     * @param TransferEvent $transferEvent objeto transferência a ser enviado.
     * @return void
     */
    private function sendTransferEvent(TransferEvent $transferEvent): void
    {
        if ($this->checkNotificationService()) {
            $this->transferEventManagerInterface->dispatch($transferEvent);
        }
    }

    /**
     * Valida a senha
     *
     * @param User $user objeto usuário
     * @param string $password O password para ser validado.
     * @return void
     */
    private function validatePassword(User $user, string $password): void
    {
        if (!$this->UserRepositoryInterface->checkUserCredentials($user->getEmail(), $password)) {
            $this->notification->addError(self::ERROR_INVALID_CREDENTIALS);
        }
    }

    /**
     * Atualiza as carteiras do pagador e do beneficiário com o valor fornecido.
     *
     * @param mixed $payerWallet The payer's wallet.
     * @param mixed $payeeWallet The payee's wallet.
     * @param mixed $value The value to be transferred.
     * @return void
     */
    private function updateWallets($payerWallet, $payeeWallet, $value)
    {

        $payerWallet->withdraw($value);
        $payeeWallet->deposit($value);

        // Atualiza a carteira do pagador
        $this->walletRepositoryInterface->update($payerWallet);

        // Atualiza a carteira do beneficiário
        $this->walletRepositoryInterface->update($payeeWallet);
    }

    /**
     * Valida o pagador
     *
     * @param User $payerUser 
     * @param Wallet $payerWallet.
     * @param float $value .
     * @return void
     */
    private function validatePayer(User $payerUser, Wallet $payerWallet, float $value): void
    {
        //verifica se o pagador é lojista
        $this->validateUserType($payerUser);

        //verifica se o pagador tem saldo suficiente
        $this->validateBalance($payerWallet, $value);
    }

    /**
     * Executa o caso de uso
     *
     * @param InputTransferWalletDTO $inputTransferWalletDTO objeto para entrada de dados.
     * @return OutputTransferWalletDTO Objeto para saida dos dados.
     */
    public function execute(InputTransferWalletDTO $inputTransferWalletDTO): OutputTransferWalletDTO
    {
        $this->transaction->begin();

        try {

            $payerUser = $this->getUser($inputTransferWalletDTO->payerUserId);
            $payeeUser = $this->getUser($inputTransferWalletDTO->payeeUserId);

            //verifica se os dados de entrada são válidos
            $inputTransferWalletDTO->isValid($this->notification);

            $payerWallet = $this->getWallet($inputTransferWalletDTO->payerUserId);
            $payeeWallet = $this->getWallet($inputTransferWalletDTO->payeeUserId);

            //verifica se o pagador é lojista e se tem saldo suficiente
            $this->validatePayer($payerUser, $payerWallet, $inputTransferWalletDTO->value);

            //verifica se a transferência é autorizada
            $this->validateAuthorizeTransfer();

            if ($this->notification->hasErrors()) {
                return new OutputTransferWalletDTO(false, $this->notification->getErrors());
            }

            //atualiza as carteiras
            $this->updateWallets($payerWallet, $payeeWallet, $inputTransferWalletDTO->value);

            //gravar histórico de transferência
            $this->transferHistoryUseCase->insert(
                new TransferHistory($inputTransferWalletDTO->value, $inputTransferWalletDTO->payerUserId, $inputTransferWalletDTO->payeeUserId)
            );

            $this->transaction->commit();

            //dispara evento de transferência para enviar e-mail, sms, etc
            $this->sendTransferEvent(new TransferEvent($payerUser, $payeeUser, $inputTransferWalletDTO->value));

            return new OutputTransferWalletDTO(true, [self::MESSAGE_SUCCESS], $inputTransferWalletDTO->value);
        } catch (\Exception $e) {

            $this->transaction->rollback();
            $this->notification->addError($e->getMessage());
            $this->logger->error("TransferUSeCase " . date('Y-m-d H:i:s')  . " Valor " . $inputTransferWalletDTO->value . " Error: " . $e->getMessage());

            return new OutputTransferWalletDTO(false, $this->notification->getErrors());
        }
    }
}
