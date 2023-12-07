@php
    $users = \App\Models\User::query()->count();
    $memory_usage = auth()->user()->memory_usage;
    $memory_limit = auth()->user()->memory_limit;

@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="auth()->user()" />
            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ 'Số người dùng trên hệ thống và dung lượng của bạn' }}
                </h2>
                <p>
                    {{$users}} {{ 'user system' }}
                </p>
                <p>
                    @if(auth()->user()->is_admin)
                        {{ 'Bạn là quản trị viên' }}
                    @else
                    {{ 'Bạn đã dùng' }}: {{$memory_usage}} {{ 'MB' }}  {{ '/' }}  {{$memory_limit}} {{ 'MB' }} {{ 'được cấp.' }}
                    @endif
                </p>
            </div>
    </x-filament::section>
</x-filament-widgets::widget>
