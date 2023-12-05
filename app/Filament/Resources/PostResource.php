<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Session;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Post Information')
                ->description('This information will be publicly visible.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->autofocus()
                        ->required()
                        ->maxValue(255)
                        ->unique(ignorable: fn ($record) => $record)
                        ->placeholder(__('Title')),
                    Forms\Components\TextInput::make('email_contact')
                        ->autofocus()
                        ->required()
                        ->placeholder(__('Email contact')),
                    Forms\Components\TextInput::make('address')
                        ->maxValue(255)
                        ->required()
                        ->placeholder(__('Address')),
                    Forms\Components\Select::make('industry')
                        ->required()
                        ->options([
                            'IT' => 'IT',
                            'Finance' => 'Finance',
                            'Marketing' => 'Marketing',
                            'Sales' => 'Sales',
                            'HR' => 'HR',
                            'Others' => 'Others',
                        ])
                        ->placeholder(__('Industry')),
                    Forms\Components\TextInput::make('limit')
                        ->numeric()
                        ->required()
                        ->placeholder(__('Limit')),
                    Forms\Components\DateTimePicker::make('end_date')
                        ->required()
                        ->placeholder(__('End date')),
                    Forms\Components\RichEditor::make('content')
                        ->autofocus()
                        ->required()
                        ->columnSpan(2)
                        ->placeholder(__('Content')),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_contact')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
