<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Người dùng';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin người dùng')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->autofocus()
                            ->required()
                            ->label(__('Tên'))
                            ->placeholder(__('Nguyễn Văn A')),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique(ignorable: fn ($record) => $record)
                            ->required()
                            ->placeholder(__('nguyenvana@gmail.com')),
                        Forms\Components\TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->placeholder(__('Password')),
                        Forms\Components\Checkbox::make('is_admin')
                            ->label('Admin')
                            ->disabled(!auth()->user()->is_admin && !auth()->user()->permissions()->where('permission', 'assign role user')->exists())
                            ->label(__('Is Admin')),
                        Forms\Components\TextInput::make('memory_limit')
                            ->label('Dung lượng giới hạn')
                            ->required()
                            ->suffix('MB')
                            ->numeric()
                            ->placeholder(__('Memory Limit')),
                        Forms\Components\TextInput::make('memory_usage')
                            ->label('Dung lượng sử dụng')
                            ->disabled(true)
                            ->suffix('MB')
                            ->numeric()
                            ->placeholder(__('Memory Usage')),
                        Forms\Components\Select::make('groups')
                            ->label('Nhóm người dùng')
                            ->relationship('groups', 'name')
                            ->multiple()
                            ->preload()
                            ->placeholder(__('Groups')),
                        Forms\Components\Select::make('permissions')
                            ->label('Quyền hạn')
                            ->relationship('permissions', 'name', fn (Builder $query) => $query->orderBy('id', 'asc'))
                            ->multiple()
                            ->required()
                            ->preload()
                            ->placeholder(__('Permissions')),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_admin')
                    ->label('Admin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('memory_limit')
                    ->label('Dung lượng giới hạn')
                    ->formatStateUsing(fn ($state) => $state . 'MB')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('memory_usage')
                    ->label('Dung lượng sử dụng')
                    ->formatStateUsing(fn ($state) => $state . 'MB')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions(actions: [Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make(), Tables\Actions\ViewAction::make()]
            )->recordUrl(fn (Model $record) => null)
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function getActions(): array
    {
        return [
            //
        ];
    }
}
