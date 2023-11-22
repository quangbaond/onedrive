<?php

namespace App\Filament\Resources\ActivityLogResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActivityLog extends BaseWidget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = false;

    protected static string $view = 'activity-log-resource.widgets.activity-log';
}
