<?php 

namespace Core\SeedWork\Domain\Enum;

enum UserType: int 
{
    case CLIENT = 1;
    case MERCHANT = 2;
}