@extends('layouts.app')
@section('titre', 'Dashboard')

@section('contenu')
@if ($needsSetup)
    <div class="max-w-lg mx-auto mt-16 text-center">
        <div class="w-14 h-14 rounded-2xl bg-accent/10 border border-accent/20 flex items-center justify-center mx-auto mb-5">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-accent">
                <path d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1H5a1 1 0 01-1-1v-9.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            </svg>
        </div>

        <h2 class="font-display font-semibold text-xl mb-2 text-ink">
            Aucun établissement
        </h2>

        <p class="text-muted text-sm mb-6">
            Vous devez créer un établissement avant de pouvoir analyser des avis.
        </p>

        <a href="{{ route('establishments.create') }}" class="btn-primary inline-flex items-center gap-2 text-white px-6 py-2.5 rounded-lg font-medium text-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Créer mon établissement
        </a>
    </div>
@else
    {{-- En-tête --}}
    <div class="flex items-end justify-between mb-8">
        <div>
           <p class="text-sm text-muted mt-1">
    {{ $establishment->name }}
</p>
        </div>
        <a href="{{ route('reviews.create') }}" class="btn-primary text-white px-5 py-2.5 rounded-lg font-medium text-sm flex items-center gap-2">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Analyser un avis
        </a>
    </div>

    {{-- Cartes stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
        $cards = [
            ['label'=>'Total avis','value'=>$stats['total'],'dot'=>'bg-slate-400','bar'=>'bg-slate-400'],
            ['label'=>'Positifs','value'=>$stats['positive'],'dot'=>'bg-emerald-500','bar'=>'bg-emerald-500'],
            ['label'=>'Négatifs','value'=>$stats['negative'],'dot'=>'bg-rose-500','bar'=>'bg-rose-500'],
            ['label'=>'Action requise','value'=>$stats['flagged'],'dot'=>'bg-accent','bar'=>'bg-accent'],
        ];
        @endphp
        @foreach ($cards as $c)
        <div class="card rounded-xl p-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                <p class="text-xs text-muted">{{ $c['label'] }}</p>
            </div>
            <p class="font-display font-semibold text-3xl text-ink">{{ $c['value'] }}</p>
            @if ($stats['total'] > 0)
            <div class="mt-3 h-1.5 bg-stone rounded-full overflow-hidden">
                <div class="h-full rounded-full {{ $c['bar'] }}" style="width:{{ round(($c['value']/$stats['total'])*100) }}%"></div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Avis récents --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-display font-semibold text-ink">Avis récents</h2>
                <a href="{{ route('reviews.index') }}" class="text-xs text-accent hover:underline">Voir tous →</a>
            </div>
            @if ($recentReviews->isEmpty())
                <div class="card rounded-xl py-12 text-center border-dashed">
                    <p class="text-muted text-sm mb-3">Aucun avis analysé encore.</p>
                    <a href="{{ route('reviews.create') }}" class="text-accent text-sm font-medium hover:underline">Analyser mon premier avis →</a>
                </div>
            @else
                <div class="space-y-3">
                @foreach ($recentReviews as $review)
                    <a href="{{ route('reviews.show', $review) }}" class="card card-hover rounded-xl p-4 flex items-start gap-3 block">
                        <span class="w-2 h-2 rounded-full shrink-0 mt-1.5" style="background:{{ $review->sentiment->dot() }}"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-ink line-clamp-2">{{ $review->raw_text }}</p>
                            <div class="flex items-center gap-3 mt-2 flex-wrap">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded border text-xs font-medium {{ $review->sentiment->badgeClasses() }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>{{ $review->sentiment->label() }}
                                </span>
                                @if ($review->is_flagged)
                                    <span class="text-xs text-accent font-medium flex items-center gap-0.5">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                        Flagué
                                    </span>
                                @endif
                                <span class="mono text-xs text-muted">{{ strtoupper($review->language ?? '?') }}</span>
                                <span class="text-xs text-muted/70">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
                </div>
            @endif
        </div>

        {{-- Colonne droite : thématiques + répartition --}}
        <div class="space-y-4">
            <div>
                <h2 class="font-display font-semibold mb-4 text-ink">Top thématiques</h2>
                @if ($topTags->isEmpty())
                    <div class="card rounded-xl py-8 text-center">
                        <p class="text-muted text-xs">Aucune thématique détectée encore.</p>
                    </div>
                @else
                    <div class="card rounded-xl p-5 space-y-3">
                    @foreach ($topTags as $tag)
                        @php $pct = $stats['total'] > 0 ? round(($tag->reviews_count/$stats['total'])*100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-ink">{{ $tag->label }}</span>
                                <span class="mono text-xs text-muted">{{ $tag->reviews_count }}</span>
                            </div>
                            <div class="h-1.5 bg-stone rounded-full overflow-hidden">
                                <div class="h-full bg-accent rounded-full" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                @endif
            </div>

            @if ($stats['total'] > 0)
            <div class="card rounded-xl p-5">
                <h3 class="font-medium text-sm mb-3 text-ink">Répartition des sentiments</h3>
                <div class="flex h-2 rounded-full overflow-hidden gap-0.5">
                    @if ($stats['positive'] > 0)
                        <div class="bg-emerald-500 rounded-l-full" style="width:{{ round(($stats['positive']/$stats['total'])*100) }}%"></div>
                    @endif
                    @if ($stats['neutral'] > 0)
                        <div class="bg-slate-300" style="width:{{ round(($stats['neutral']/$stats['total'])*100) }}%"></div>
                    @endif
                    @if ($stats['negative'] > 0)
                        <div class="bg-rose-500 rounded-r-full" style="width:{{ round(($stats['negative']/$stats['total'])*100) }}%"></div>
                    @endif
                </div>
                <div class="flex items-center gap-4 mt-3 text-xs text-muted flex-wrap">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ round(($stats['positive']/$stats['total'])*100) }}% positifs</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-300"></span>{{ round(($stats['neutral']/$stats['total'])*100) }}% neutres</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span>{{ round(($stats['negative']/$stats['total'])*100) }}% négatifs</span>
                </div>
            </div>
            @endif
        </div>
    </div>
@endif
@endsection