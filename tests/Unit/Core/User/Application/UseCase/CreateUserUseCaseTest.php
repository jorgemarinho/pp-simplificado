<?php

use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Application\DTO\InputCompanyDTO;
use Core\User\Application\DTO\InputPeopleDTO;
use Core\User\Application\DTO\InputUserDTO;
use Core\User\Application\DTO\OutputUserDTO;
use Core\User\Application\UseCase\CreateUserUseCase;
use Core\User\Domain\Entities\Company;
use Core\User\Domain\Entities\People;
use Core\User\Domain\Entities\User;
use Core\User\Domain\Repository\CompanyRepositoryInterface;
use Core\User\Domain\Repository\PeopleRepositoryInterface;
use Core\User\Domain\Repository\UserRepositoryInterface;
use Core\Wallet\Domain\Repository\WalletRepositoryInterface;
use Psr\Log\LoggerInterface;
use Mockery\MockInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

it('can create a user client', function () {
    
    $userRepository = mock(UserRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once();
        $mock->shouldReceive('update')->once();
        $mock->shouldReceive('findByEmail')->once();
    });

    $peopleRepository = mock(PeopleRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once();
        $mock->shouldReceive('findByCPF')->once();
    });

    $companyRepository = mock(CompanyRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once();
        $mock->shouldReceive('findByCNPJ')->once();
    });

    $walletRepository = mock(WalletRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once();
    });

    $logger = mock(LoggerInterface::class);

    $transaction = mock(TransactionInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('begin')->once();
        $mock->shouldReceive('commit')->once();
    });

    $useCase = new CreateUserUseCase($userRepository, $peopleRepository, $companyRepository,$walletRepository, $logger, $transaction);

    $email = "jorgeluizbsi@gmail.com";
    $password = "98765432";
    $userDTO = new InputUserDTO($email, $password);

    $peopleDTO = new InputPeopleDTO(
        fullName: "Jorge Luiz",
        cpf: "82477613006",
        phone: "61981665606"
    );

    $companyDTO = new InputCompanyDTO(
        cnpj: "01380852000114"
    );
    
    $outputUserDTO = $useCase->execute($userDTO, $peopleDTO, $companyDTO);

    $this->assertInstanceOf(OutputUserDTO::class, $outputUserDTO);

    expect($outputUserDTO->isSuccess())->toBe(true);
    expect($outputUserDTO->getMessage())->toBe([CreateUserUseCase::MESSAGE_SUCCESS]);
    expect($outputUserDTO->getUser())->toBeInstanceOf(User::class);
    expect($outputUserDTO->getPeople())->toBeInstanceOf(People::class);
    expect($outputUserDTO->getUser()->id())->not->toBeNull();
    expect($outputUserDTO->getPeople()->id())->not->toBeNull();
    expect($outputUserDTO->getUser()->getEmail())->toBe($email);
    expect($outputUserDTO->getPeople()->getCpf())->toBe($peopleDTO->cpf);
    expect($outputUserDTO->getPeople()->getPhone())->toBe($peopleDTO->phone);
    expect($outputUserDTO->getPeople()->getFullName())->toBe($peopleDTO->fullName);
    expect($outputUserDTO->getPeople()->getUserId())->toBe($outputUserDTO->getUser()->id());

    
});

it('can create a user merchant', function () {
    
    $userRepository = mock(UserRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once();
        $mock->shouldReceive('findByEmail')->once();
    });

    $peopleRepository = mock(PeopleRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once();
        $mock->shouldReceive('findByCPF')->once();
    });

    $companyRepository = mock(CompanyRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByCNPJ')->never();
    });

    $walletRepository = mock(WalletRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once();
    });

    $logger = mock(LoggerInterface::class);

    $transaction = mock(TransactionInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('begin')->once();
        $mock->shouldReceive('commit')->once();
    });

    $useCase = new CreateUserUseCase($userRepository, $peopleRepository, $companyRepository, $walletRepository, $logger, $transaction);

    $email = "jorgeluizbsi@gmail.com";
    $password = "98765432";
    $userDTO = new InputUserDTO($email, $password);

    $peopleDTO = new InputPeopleDTO(
        fullName: "Jorge Luiz",
        cpf: "82477613006",
        phone: "61981665606"
    );

    $companyDTO = null;
    
    $outputUserDTO = $useCase->execute($userDTO, $peopleDTO, $companyDTO);

    $this->assertInstanceOf(OutputUserDTO::class, $outputUserDTO);
    
    expect($outputUserDTO->isSuccess())->toBe(true);
    expect($outputUserDTO->getMessage())->toBe([CreateUserUseCase::MESSAGE_SUCCESS]);
    expect($outputUserDTO->getUser())->toBeInstanceOf(User::class);
    expect($outputUserDTO->getPeople())->toBeInstanceOf(People::class);
    
});


it('can not create a user with invalid data', function () {
    
    $userRepository = mock(UserRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByEmail')->never();
    });

    $peopleRepository = mock(PeopleRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByCPF')->never();
    });

    $companyRepository = mock(CompanyRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByCNPJ')->never();
    });

    $walletRepository = mock(WalletRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
    });

    $logger = mock(LoggerInterface::class);

    $transaction = mock(TransactionInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('begin')->never();
        $mock->shouldReceive('commit')->never();
    });

    $useCase = new CreateUserUseCase($userRepository, $peopleRepository, $companyRepository, $walletRepository, $logger, $transaction);

    $email = "";
    $password = "";
    $userDTO = new InputUserDTO($email, $password);

    $peopleDTO = new InputPeopleDTO(
        fullName: "",
        cpf: "",
        phone: ""
    );

    $companyDTO = new InputCompanyDTO(
        cnpj: ""
    );
    
    $outputUserDTO = $useCase->execute($userDTO, $peopleDTO, $companyDTO);

    $this->assertInstanceOf(OutputUserDTO::class, $outputUserDTO);
    
    expect($outputUserDTO->isSuccess())->toBe(false);

    $actualMessages = $outputUserDTO->getMessage();
    sort($actualMessages);
    $expectedMessages = [ "Email is required", "Password is required", "Invalid email format" ];
    sort($expectedMessages);
    
    expect($actualMessages)->toMatchArray($expectedMessages);

    $this->assertNull($outputUserDTO->getUser());
    $this->assertNull($outputUserDTO->getPeople());

    expect($outputUserDTO->getPeople())->toBeNull();
    expect($outputUserDTO->getUser())->toBeNull();
    
    $useCase = new CreateUserUseCase($userRepository, $peopleRepository, $companyRepository,$walletRepository,  $logger, $transaction);

    $email = "jorgeluizbsi@gmail.com";
    $password = "98765432";
    $fullName = "Jorge Luiz";
    $cpf = "82477613006";
    $phone = "61981665606";

    $userDTO = new InputUserDTO($email, $password);

    $peopleDTO = new InputPeopleDTO(
        fullName: "",
        cpf: "",
        phone: ""
    );

    $companyDTO = new InputCompanyDTO(
        cnpj: ""
    );
    
    $outputUserDTO = $useCase->execute($userDTO, $peopleDTO, $companyDTO);

    $actualMessages = $outputUserDTO->getMessage();
    sort($actualMessages);
    $expectedMessages = ["CPF is required", "FullName is required", "Phone is required"];
    sort($expectedMessages);
    
    expect($actualMessages)->toMatchArray($expectedMessages);

    $useCase = new CreateUserUseCase($userRepository, $peopleRepository, $companyRepository,$walletRepository, $logger, $transaction);

    $userDTO = new InputUserDTO($email, $password);

    $peopleDTO = new InputPeopleDTO(
        fullName: $fullName,
        cpf: $cpf,
        phone: $phone
    );

    $companyDTO = new InputCompanyDTO(
        cnpj: ""
    );
    
    $outputUserDTO = $useCase->execute($userDTO, $peopleDTO, $companyDTO);

    $actualMessages = $outputUserDTO->getMessage();
    sort($actualMessages);
    $expectedMessages = ["CNPJ is required"];
    sort($expectedMessages);
    
    expect($actualMessages)->toMatchArray($expectedMessages);

});

it('can create a user with existing email, cpf and cnpj', function () {

    $email = "jorgeluizbsi@gmail.com";
    $password = "98765432";
    $fullName = "Jorge Luiz";
    $cpf = "82477613006";
    $phone = "61981665606";
    $cnpj = "01380852000114";

    $userId =  Uuid::random();
    $peopleId = Uuid::random();

    $userRepository = mock(UserRepositoryInterface::class, function (MockInterface $mock) use ($email, $password) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByEmail')->andReturn(new User( email: $email, password: $password));
    });

    $peopleRepository = mock(PeopleRepositoryInterface::class, function (MockInterface $mock) use ($fullName, $cpf, $phone, $userId) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByCPF')->andReturn(new People( fullName: $fullName, cpf: $cpf, phone: $phone, userId: $userId));
    });

    $companyRepository = mock(CompanyRepositoryInterface::class, function (MockInterface $mock) use ($cnpj, $peopleId) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByCNPJ')->andReturn(new Company( cnpj: $cnpj, peopleId:  $peopleId ));
    });

    $walletRepository = mock(WalletRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
    });
    
    $logger = mock(LoggerInterface::class);

    $transaction = mock(TransactionInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('begin')->never();
        $mock->shouldReceive('commit')->never();
    });

    $useCase = new CreateUserUseCase($userRepository, $peopleRepository, $companyRepository,$walletRepository, $logger, $transaction);

    $userDTO = new InputUserDTO($email, $password);
    $peopleDTO = new InputPeopleDTO(
        fullName:  $fullName,
        cpf: $cpf,
        phone: $phone
    );
    $companyDTO = new InputCompanyDTO(
        cnpj: $cnpj
    );
    
    $outputUserDTO = $useCase->execute($userDTO, $peopleDTO, $companyDTO);
    $this->assertInstanceOf(OutputUserDTO::class, $outputUserDTO);

    expect($outputUserDTO->isSuccess())->toBe(false);
    expect($outputUserDTO->getMessage())->toBe([ CreateUserUseCase::ERROR_EMAIL, CreateUserUseCase::ERROR_CPF, CreateUserUseCase::ERROR_CNPJ]);
    $this->assertNull($outputUserDTO->getUser());
    $this->assertNull($outputUserDTO->getPeople());
});


it('throws an exception when an error occurs', function () {

    $userRepository = mock(UserRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once()->andThrow(new \Exception());
        $mock->shouldReceive('findByEmail')->once();
    });

    $peopleRepository = mock(PeopleRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByCPF')->once();
    });

    $companyRepository = mock(CompanyRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
        $mock->shouldReceive('findByCNPJ')->once();
    });

    $walletRepository = mock(WalletRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
    });
    
    $logger = mock(LoggerInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('error')->once();
    });

    $transaction = mock(TransactionInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('begin')->once();
        $mock->shouldReceive('commit')->never();
        $mock->shouldReceive('rollBack')->once();
    });

    $useCase = new CreateUserUseCase($userRepository, $peopleRepository, $companyRepository,$walletRepository, $logger, $transaction);

    $email = "jorgeluizbsi@gmail.com";
    $password = "98765432";
    $fullName = "Jorge Luiz";
    $cpf = "82477613006";
    $phone = "61981665606";  
    $cnpj = "01380852000114";

    $userDTO = new InputUserDTO($email, $password);

    $peopleDTO = new InputPeopleDTO(
        fullName: $fullName,
        cpf: $cpf,
        phone: $phone
    );

    $companyDTO = new InputCompanyDTO(
        cnpj: $cnpj
    );
    
    $outputUserDTO = $useCase->execute($userDTO, $peopleDTO, $companyDTO);

    $this->assertInstanceOf(OutputUserDTO::class, $outputUserDTO);

    expect($outputUserDTO->isSuccess())->toBe(false);

});
            
