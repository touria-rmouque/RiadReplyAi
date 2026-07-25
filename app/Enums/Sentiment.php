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
            self::Positive => 'bg-emerald-900/40 text-emerald-300 border-emerald-500/30',
            self::Neutral  => 'bg-slate-800 text-slate-300 border-slate-600/30',
            self::Negative => 'bg-red-900/40 text-red-300 border-red-500/30',
        };
    }

    public function dot(): string
    {
        return match ($this) {
            self::Positive => '#10B981',
            self::Neutral  => '#94A3B8',
            self::Negative => '#EF4444',
        };
    }
}
