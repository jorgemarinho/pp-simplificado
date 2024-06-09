<?php

use App\Models\User as UserModel;
use App\Models\People as PeopleModel;
use App\Models\Company as CompanyModel;
use App\Models\Wallet as WalletModel;

use App\Repositories\Eloquent\UserEloquentRepository;
use App\Repositories\Eloquent\PeopleEloquentRepository;
use App\Repositories\Eloquent\CompanyEloquentRepository;
use App\Repositories\Eloquent\WalletEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use App\Logging\Log;

use Core\User\Application\DTO\InputCompanyDTO;
use Core\User\Application\DTO\InputPeopleDTO;
use Core\User\Application\DTO\InputUserDTO;
use Core\User\Application\UseCase\CreateUserUseCase;
use Core\User\Domain\Entities\People;
use Core\User\Domain\Entities\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->userRepository = new UserEloquentRepository(new UserModel());
    $this->peopleRepository = new PeopleEloquentRepository(new PeopleModel());
    $this->companyRepository = new CompanyEloquentRepository(new CompanyModel());
    $this->walletRepository = new WalletEloquentRepository(new WalletModel());
    $this->logger = new Log();
    $this->transaction = new DBTransaction();
});

it('execute create user case success- feature', function () {

    $useCase = new CreateUserUseCase(
        $this->userRepository,
        $this->peopleRepository,
        $this->companyRepository,
        $this->walletRepository,
        $this->logger,
        $this->transaction
    );

    $emailUser = 'jorge@gmail.com';
    $namePeople = 'Jorge Luiz Caetano Marinho';
    $cpfPeople = '78030370032';
    $phonePeople = '61981665606';

    $userDTO = new InputUserDTO($emailUser, '12345678');
    $peopleDTO = new InputPeopleDTO($namePeople,  $cpfPeople,  $phonePeople);

    $result = $useCase->execute($userDTO, $peopleDTO);

    expect($result->isSuccess())->toBeTrue();
    expect($result->getMessage())->toBe([CreateUserUseCase::MESSAGE_SUCCESS]);
    expect($result->getUser())->toBeInstanceOf(User::class);
    expect($result->getPeople())->toBeInstanceOf(People::class);

    $this->assertDatabaseHas('users', [
        'email' => $emailUser,
    ]);

    $this->assertDatabaseHas('people', [
        'name' => $namePeople,
        'cpf' =>  $cpfPeople,
        'phone' => $phonePeople,
    ]);

    $this->assertDatabaseHas('wallets', [
        'user_id' => $result->getUser()->id(),
    ]);
});
