<?php

namespace App\Enums;

enum ReviewStatus: string
{
    case Pending = 'pending';
    case Replied = 'replied';
    case Flagged = 'flagged';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Replied => 'Répondu',
            self::Flagged => 'Action requise',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-slate-50 text-slate-600 border-slate-200',
            self::Replied => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::Flagged => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }
}