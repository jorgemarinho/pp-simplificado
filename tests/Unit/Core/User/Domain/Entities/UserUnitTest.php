<?php

use Core\User\Domain\Entities\User;

it('can create a user entity', function () {

    $email = 'john@example.com';
    $password = 'password123';

    $user = new User( $email, $password);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->email)->toBe($email);
    expect($user->password)->toBe($password);
});


