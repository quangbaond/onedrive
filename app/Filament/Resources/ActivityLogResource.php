<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Filament\Resources\ActivityLogResource\RelationManagers;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Lịch sử hoạt động';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả')
                    ->formatStateUsing(function (string $state): string {
                        switch ($state) {
                            case 'created':
                                return 'Tạo mới';
                            case 'updated':
                                return 'Cập nhật';
                            case 'deleted':
                                return 'Xóa';
                            case 'restored':
                                return 'Khôi phục';
                            default:
                                return $state;
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Chức năng')
                    ->formatStateUsing(function (string $state): string {
                       return \App\Helpers\Helper::getLogSubjectType($state);
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('causer')
                    ->label('Người thực hiện')
                    ->formatStateUsing(function (string $state): string {
                        $state = json_decode($state, true);
                        $state = $state['name'] . ' - ' .$state['email'];
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Đối tượng')
                    ->formatStateUsing(function (string $state): string {
                        $state = json_decode($state, true);
                        $state = $state['name'] ?? $state['title'] ?? $state['email'] ?? $state['slug'] ?? $state['id'];
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DateTimePicker::make('created_from')
                            ->label('Thời gian bắt đầu'),
                        DateTimePicker::make('created_to')
                            ->label('Thời gian kết thúc'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['created_from'] ?? null,
                                fn (Builder $query, $created_from) => $query->where('created_at', '>=', $created_from)
                            )->when($data['created_to'] ?? null,
                                fn (Builder $query, $created_to) => $query->where('created_at', '<=', $created_to));
                    })
                    ->indicateUsing(function (array $data) {
                        $indicator = [];
                        if($data['created_from'] ?? null) {
                            $indicator['created_to'] = __('Bắt đầu') . ' ' . $data['created_from'] . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if($data['created_to'] ?? null) {
                            $indicator['created_to'] = __('Đến') . ' ' . $data['created_to'] . Carbon::parse($data['created_to'])->toFormattedDateString();
                        }
                        return $indicator;
                    })
                    ->columnSpan(2)->columns(2),
            ] ,layout: Tables\Enums\FiltersLayout::AboveContent)->filtersFormColumns(2)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
//                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'create' => Pages\CreateActivityLog::route('/create'),
            'edit' => Pages\EditActivityLog::route('/{record}/edit'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ActivityLogResource\Widgets\ActivityLog::make(),
        ];
    }
}
