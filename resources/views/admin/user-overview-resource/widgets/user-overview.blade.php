@php
    $users = \App\Models\User::query()->count();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="auth()->user()" />
            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ 'Total user system' }}
                </h2>
                <p>
                    {{$users}} {{ 'user system' }}
                </p>
            </div>
    </x-filament::section>
</x-filament-widgets::widget>
