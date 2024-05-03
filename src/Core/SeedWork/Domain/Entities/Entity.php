<?php

namespace Core\SeedWork\Domain\Entities;

use Core\SeedWork\Domain\Notification\Notification;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Exception;

abstract class Entity
{
    protected $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }

    public function __get($property)
    {
        if (property_exists( $this , $property ) ) {
            return $this->{$property};
        }

        $className = get_class($this);
        throw new Exception("Property {$property} not found in class {$className}");
    }

    public function id(): string|Uuid
    {
        return (string) $this->id;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
}