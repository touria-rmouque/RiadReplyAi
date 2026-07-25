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
            self::Pending => 'bg-slate-800 text-slate-300 border-slate-600/30',
            self::Replied => 'bg-emerald-900/40 text-emerald-300 border-emerald-500/30',
            self::Flagged => 'bg-amber-900/40 text-amber-300 border-amber-500/30',
        };
    }
}
