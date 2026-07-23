<?php

namespace App\Enums;

enum EstablishmentType: string
{
    case Riad = 'riad';
    case Hotel = 'hotel';
    case Restaurant = 'restaurant';

    public function label(): string
    {
        return match ($this) {
            self::Riad => 'Riad',
            self::Hotel => 'Hôtel',
            self::Restaurant => 'Restaurant',
        };
    }
}