<?php

namespace Core\SeedWork\Domain\Events;

interface EventInterface
{
    public function getEventName(): string;

    public function getPayload(): array;
}