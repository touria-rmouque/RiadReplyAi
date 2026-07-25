@props(['type' => 'submit'])

<button {{ $attributes->merge(['type' => $type, 'class' => 'w-full inline-flex items-center justify-center gap-2 bg-accent hover:bg-accent-dark text-white px-6 py-2.5 rounded-lg font-medium text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-accent/30 focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>