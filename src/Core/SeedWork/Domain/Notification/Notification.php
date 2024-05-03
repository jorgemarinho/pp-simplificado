<?php

namespace Core\SeedWork\Domain\Notification;

class Notification
{
    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function addError(string $error): void
    {
        array_push($this->errors, $error);
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function messages(string $context = ''): string
    {
        $messages = '';

        foreach ($this->errors as $error) {
            $messages .= "{message : $error},";
        }
        
        return $messages;
    }
}