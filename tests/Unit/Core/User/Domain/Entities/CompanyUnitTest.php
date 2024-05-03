<?php

use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Domain\Entities\Company;

it('can create a company', function () {
    
    $cnpj = '63656835000114';
    $peopleId =  Uuid::random();

    $company = new Company($cnpj,$peopleId);

    expect($company)->toHaveProperties([
        'cnpj' => $cnpj,
        'peopleId' => $peopleId
    ]);

    expect($company->cnpj)->toBe($cnpj);
    expect($company->peopleId)->toBe($peopleId);
});

it('can create a company with id and createdAt', function () {
    
    $cnpj = '63656835000114';
    $peopleId =  Uuid::random();
    $id = Uuid::random();
    $createdAt = new DateTime();

    $company = new Company($cnpj,$peopleId,$id,$createdAt);

    expect($company)->toHaveProperties([
        'cnpj' => $cnpj,
        'peopleId' => $peopleId,
        'id' => $id,
        'createdAt' => $createdAt
    ]);

    expect($company->cnpj)->toBe($cnpj);
    expect($company->peopleId)->toBe($peopleId);
    expect($company->id)->toBe($id);
    expect($company->createdAt)->toBe($createdAt);
});

it('should throw exception when cnpj is invalid', function () {
    
    $cnpj = '6365683500011';
    $peopleId =  Uuid::random();

    new Company($cnpj,$peopleId);

})->throws(Exception::class);

