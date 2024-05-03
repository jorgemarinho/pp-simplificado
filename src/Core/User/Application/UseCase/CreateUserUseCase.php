<?php

namespace Core\User\Application\UseCase;

use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Core\SeedWork\Domain\Notification\Notification;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Application\DTO\InputCompanyDTO;
use Core\User\Application\DTO\InputPeopleDTO;
use Core\User\Application\DTO\InputUserDTO;
use Core\User\Application\DTO\OutputUserDTO;
use Core\User\Domain\Entities\Company;
use Core\User\Domain\Entities\People;
use Core\User\Domain\Entities\User;
use Core\User\Domain\Repository\CompanyRepositoryInterface;
use Core\User\Domain\Repository\PeopleRepositoryInterface;
use Core\User\Domain\Repository\UserUseCaseRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateUserUseCase
{
    const ERROR_EMAIL = 'Email is already taken';
    const ERROR_EMAIL_FORMAT = 'Invalid email format';
    const ERROR_CPF = 'CPF is already registered';
    const ERROR_CNPJ = 'CNPJ is already registered';
    const ERROR_PASSWORD = '"Password is required"';  
    const MESSAGE_SUCCESS = 'Registered successfully!';     

    private Notification $notification;

    public function __construct(
        private UserUseCaseRepositoryInterface $userRepository,
        private PeopleRepositoryInterface $peopleRepository,
        private CompanyRepositoryInterface $companyRepository,
        private LoggerInterface $logger,
        private TransactionInterface $transaction
    ) {
        $this->notification = new Notification();
    }

    private function createUser($userDTO)
    {
        return new User(
            $userDTO->email,
            $userDTO->password
        );
    }

    private function createPeople(InputPeopleDTO $peopleDTO, Uuid $userId)
    {
        return new People($peopleDTO->fullName, $peopleDTO->cpf, $peopleDTO->phone, $userId);
    }

    private function createCompany(InputCompanyDTO $companyDTO, Uuid $peopleId)
    {
        return new Company($companyDTO->cnpj, $peopleId);
    }

    /**
     * Valida se o email, cpf e cnpj já estão cadastrados.
     *
     * @param InputUserDTO $userDTO
     * @param InputPeopleDTO $peopleDTO
     * @param InputCompanyDTO $companyDTO
     * @return void
     */
    public function validate(InputUserDTO $userDTO, InputPeopleDTO $peopleDTO, InputCompanyDTO $companyDTO): void
    {
         //verifica se o email, cpf e cnpj já estão cadastrados
        if ($this->userRepository->findByEmail($userDTO->email) !== null) {
            $this->notification->addError(self::ERROR_EMAIL);
        }

        //verifica se o cpf já está cadastrado
        if ($this->peopleRepository->findByCPF($peopleDTO->cpf) !== null) {
            $this->notification->addError(self::ERROR_CPF);
        }

        //verifica se o cnpj já está cadastrado
        if ($companyDTO->cnpj && $this->companyRepository->findByCNPJ($companyDTO->cnpj) !== null) {
            $this->notification->addError(self::ERROR_CNPJ);
        }

    }

    /**
     * Executa o caso de uso para criar um novo usuário.
     *
     * @param InputUserDTO $userDTO O objeto de entrada de dados para o usuário.
     * @param InputPeopleDTO $peopleDTO O objeto de entrada de dados para a pessoa.
     * @param InputCompanyDTO $companyDTO O objeto de entrada de dados para a empresa.
     * @return OutputUserDTO O objeto de saída de dados para o usuário.
     */
    public function execute(InputUserDTO $userDTO, InputPeopleDTO $peopleDTO, InputCompanyDTO $companyDTO): OutputUserDTO
    {
        //verifica se os dados de entrada são válidos
        if (!$userDTO->isValid($this->notification) || !$peopleDTO->isValid($this->notification) || !$companyDTO->isValid($this->notification)) {
            return new OutputUserDTO(false, $this->notification->getErrors(), null, null);
        }

        //valida se o email, cpf e cnpj já estão cadastrados
        $this->validate($userDTO, $peopleDTO, $companyDTO);

        if ($this->notification->hasErrors()) {
            return new OutputUserDTO(false, $this->notification->getErrors(), null);
        }

        $this->transaction->begin();

        try {

            $user = $this->createUser($userDTO);
            $this->userRepository->insert($user);

            $people = $this->createPeople($peopleDTO, $user->id);
            $this->peopleRepository->insert($people);

            if ($companyDTO->cnpj != null) {
                $company = $this->createCompany($companyDTO, $people->id);
                $this->companyRepository->insert($company);
            }

            $this->transaction->commit();

            return new OutputUserDTO(true, [self::MESSAGE_SUCCESS], $user, $people);

        } catch (\Exception $e) {

            $this->transaction->rollBack();
            $this->notification->addError($e->getMessage());
            $this->logger->error("CreateUserUseCase " . date('Y-m-d H:i:s') . " User ID: " . $user->id() . " Error: " . $e->getMessage());

            return new OutputUserDTO(false, $this->notification->getErrors(), null, null);
        }
    }
}
