<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\District;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Session;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Bài đăng';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin bài đăng')
                ->columns(2)
                ->schema(components: [
                    Forms\Components\TextInput::make('title')
                        ->label('Tiêu đề')
                        ->autofocus()
                        ->required()
                        ->maxValue(255)
                        ->unique(ignorable: fn ($record) => $record),
                    Forms\Components\TextInput::make('email_contact')
                        ->label('Email liên hệ')
                        ->autofocus()
                        ->required(),
                    Forms\Components\Select::make('province_code')
                        ->label('Tỉnh/Thành phố')
                        ->relationship('province', 'name')
                        ->required()
                        ->afterStateUpdated(function ($component) {
                            $province_code = $component->getState('province_code');
                            Session::put('province_code', $province_code);
                        })
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('district_code')
                        ->label('Quận/Huyện')
                        ->relationship('district', 'name', fn (Builder $query) => $query->where('province_code', Session::get('province_code')))
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('industry_id')
                        ->label('Ngành nghề')
                        ->relationship('industry', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('limit')
                        ->label('Số lượng')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('company_name')
                        ->label('Tên công ty')
                        ->maxValue(255)
                        ->required(),
                    Forms\Components\DateTimePicker::make('end_date')
                        ->label('Ngày hết hạn')
                        ->required(),
                    Forms\Components\RichEditor::make('content')
                        ->label('Nội dung')
                        ->required()
                        ->columnSpan(2),
                    Forms\Components\Checkbox::make('is_new')
                        ->label('Bài viết mới'),
                    Forms\Components\Checkbox::make('published')
                        ->label('Hiển thị'),
                ])
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Đường dẫn')
                    ->sortable(),
                Tables\Columns\TextColumn::make('province.name')
                    ->label('Tỉnh/Thành phố')
                    ->sortable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->label('Quận/Huyện')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_contact')
                    ->label('Email liên hệ')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('industry.name')
                    ->label('Ngành nghề')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit')
                    ->label('Số lượng')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Tên công ty')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Ngày hết hạn')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_new')
                    ->label('Bài viết mới')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('published')
                    ->label('Hiển thị')
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
                Tables\Filters\Filter::make('province_code')
                    ->form([
                        Forms\Components\Select::make('province_code')
                            ->label('Tỉnh/Thành phố')
                            ->relationship('province', 'name')
                            ->required()
                            ->afterStateUpdated(function ($component) {
                                $province_code = $component->getState('province_code');
                                Session::put('province_code', $province_code);
                            })
                            ->searchable()
                            ->preload(),
                        // filter by district
                        Forms\Components\Select::make('district_code')
                            ->label('Quận/Huyện')
                            ->relationship('district', 'name', fn (Builder $query) => $query->where('province_code', Session::get('province_code')))
                            ->required()
                            ->searchable()
                            ->preload()
                    ])
                    ->columnSpan(2)->columns(2)
                    ->query(function (Builder $query, array $data) {
                        $query->when($data['province_code'] ?? null, fn (Builder $query, $province_code) => $query->where('province_code', $province_code))
                            ->when($data['district_code'] ?? null, fn (Builder $query, $district_code) => $query->where('district_code', $district_code));
                    }),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)->filtersFormColumns(4)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->recordUrl(fn (Model $record) => null)
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
