@php
    $activityLog = \Spatie\Activitylog\Models\Activity::query()->where('causer_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    @if($activityLog)
        <x-filament::section>
            <div class="flex items-center gap-x-3">
                <x-filament-panels::avatar.user size="lg" :user="$activityLog->causer" />
                <div class="flex-1">
                    <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        {{ 'Lịch sử hoạt động' }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ filament()->getUserName($activityLog->causer) }}
                    </p>
                    <p>
                        {{$activityLog->causer->name}} {{ \App\Helpers\Helper::getLogDescription($activityLog->description) }} {{ \App\Helpers\Helper::getLogSubjectType($activityLog->subject_type) }} {{ \App\Helpers\Helper::getLogSubject($activityLog->subject) }}
                    </p>
                    <p>{{ $activityLog->created_at }}</p>
                </div>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>

