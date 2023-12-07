@php
    $cvs = \App\Models\Cv::query()->where('created_by', auth()->id())->count();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="auth()->user()" />
            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ 'Tổng Cv của bạn' }}
                </h2>
                <p>
                    {{$cvs}} {{ 'Cv' }}
                </p>
            </div>
    </x-filament::section>
</x-filament-widgets::widget>
