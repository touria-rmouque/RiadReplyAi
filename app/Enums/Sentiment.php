<?php

namespace App\Enums;

enum Sentiment: string
{
    case Positive = 'positive';
    case Neutral = 'neutral';
    case Negative = 'negative';

    public function label(): string
    {
        return match ($this) {
            self::Positive => 'Positif',
            self::Neutral  => 'Neutre',
            self::Negative => 'Négatif',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Positive => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::Neutral  => 'bg-slate-50 text-slate-600 border-slate-200',
            self::Negative => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }

    public function dot(): string
    {
        return match ($this) {
            self::Positive => '#10B981',
            self::Neutral  => '#94A3B8',
            self::Negative => '#E11D48',
        };
    }
}