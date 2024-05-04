<?php

namespace Core\SeedWork\Application\UseCase\Interfaces;

interface TransactionInterface
{
    public function begin();
    
    public function commit();

    public function rollback();
}