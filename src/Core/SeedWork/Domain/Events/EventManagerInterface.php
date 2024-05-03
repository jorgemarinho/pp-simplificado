<?php

namespace Core\SeedWork\Domain\Events;

interface EventManagerInterface
{
    public function dispatch(object $event): void;
}