<?php

namespace Core\SeedWork\Domain\Validation;

use Core\SeedWork\Domain\Exception\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value, string $exceptMessage = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptMessage ?? 'Should not be empty or null');
        }
    }

    public static function strMaxLength(string $value, int $length = 255, string $exceptMessage = null)
    {
        if (strlen($value) >= $length) {
            throw new EntityValidationException($exceptMessage ?? "The value must not be greater than {$length} characters");
        }
    }

    public static function strMinLength(string $value, int $length = 3, string $exceptMessage = null)
    {
        if (strlen($value) < $length) {
            throw new EntityValidationException($exceptMessage ?? "The value must be at least {$length} characters");
        }
    }

    public static function strCanNullAndMaxLength(string $value = '', int $length = 255, string $exceptMessage = null)
    {
        if (!empty($value) && strlen($value) > $length) {
            throw new EntityValidationException($exceptMessage ?? "The value must not be greater than {$length} characters");
        }
    }

    public static function strCanNotNull(string $value)
    {
        if (empty($value) || (int)$value == 0) {
            throw new EntityValidationException($exceptMessage ?? "The value must not null or greater than 0");
        }
    }

    public static function isEmail(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new EntityValidationException($exceptMessage ?? 'The value not e-mail valid');
        }
    }

    public static function isCpf(string $value) 
    {
        if (!ValidateBR::validaCPF($value)) {
            throw new EntityValidationException($exceptMessage ?? 'The value not cpf valid');
        }
    }

    public static function isCnpj(string $value) 
    {
        if (!ValidateBR::validaCNPJ($value)) {
            throw new EntityValidationException($exceptMessage ?? 'The value not cnpj valid');
        }
    }
}
