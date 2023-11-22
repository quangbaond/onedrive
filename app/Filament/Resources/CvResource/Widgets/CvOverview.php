<?php

namespace App\Filament\Resources\CvResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CvOverview extends BaseWidget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = false;

    protected static string $view = 'owner-overview-resource.widgets.onwer-overview';
}
