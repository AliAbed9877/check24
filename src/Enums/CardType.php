<?php
namespace App\Enums;

enum CardType: int
{
    case CREDIT = 0;
    case DEBIT = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::CREDIT => 'credit',
            self::DEBIT => 'debit',
        };
    }
}