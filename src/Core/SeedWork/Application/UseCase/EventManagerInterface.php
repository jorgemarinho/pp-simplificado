<?php

namespace Core\SeedWork\Application\UseCase\Interfaces;

interface EventManagerInterface
{
    public function dispatch(object $event): void;
}