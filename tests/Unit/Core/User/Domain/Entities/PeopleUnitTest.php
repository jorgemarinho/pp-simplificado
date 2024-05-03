<?php

use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Domain\Entities\People;

it('can create a people', function () {
    
    $fullName = 'John Doe';
    $cpf = '21835330053';
    $phone = '61981665606';    
    $userId =  Uuid::random();
    $people = new People($fullName,$cpf,$phone,$userId);
    
    expect($people)->toHaveProperties([
        'fullName' => $fullName,
        'cpf' => $cpf,
        'userId' => $userId,
        'phone' => $phone
    ]);

    expect($people->fullName)->toBe($fullName);
    expect($people->cpf)->toBe($cpf);
    expect($people->userId)->toBe($userId);
    expect($people->phone)->toBe($phone);

});

