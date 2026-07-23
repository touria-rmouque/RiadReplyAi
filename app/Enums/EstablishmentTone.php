<?php

namespace App\Enums;

enum EstablishmentTone: string
{
    case Friendly     = 'friendly';
    case Formal       = 'formal';
    case Enthusiastic = 'enthusiastic';

    public function label(): string
    {
        return match ($this) {
            self::Friendly     => 'Chaleureux',
            self::Formal       => 'Formel',
            self::Enthusiastic => 'Enthousiaste',
        };
    }

    public function promptDescription(): string
    {
        return match ($this) {
            self::Friendly     => 'chaleureux, accueillant, personnalisé, comme un ami qui parle à un client fidèle',
            self::Formal       => 'formel, professionnel, courtois, avec une distance respectueuse',
            self::Enthusiastic => 'enthousiaste, dynamique, plein d\'énergie et de gratitude exubérante',
        };
    }
}
