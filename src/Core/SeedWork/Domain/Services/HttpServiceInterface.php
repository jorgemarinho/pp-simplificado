<?php

namespace Core\SeedWork\Domain\Services;

interface HttpServiceInterface
{
    public function get(string $url, array $headers = []): array;
}
