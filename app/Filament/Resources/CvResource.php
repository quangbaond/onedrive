<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CvResource\Pages;
use App\Filament\Resources\CvResource\RelationManagers;
use App\Models\Config;
use App\Models\Cv;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Mail;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CvResource extends Resource
{
    protected static ?string $model = Cv::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Cv';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên')
                            ->autofocus()
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignorable: fn ($record) => $record),
                        Forms\Components\TextInput::make('phone')
                            ->label('Số điện thoại'),
                        Forms\Components\DatePicker::make('birthday')
                            ->label('Ngày sinh'),
                        Forms\Components\RichEditor::make('note')
                            ->label('Ghi chú')
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('other')
                            ->label('Khác'),
                        Forms\Components\Textarea::make('address')
                            ->label('Địa chỉ'),
                        Forms\Components\Select::make('industry_id')
                            ->label('Ngành nghề')
                            ->relationship('industry', 'name')
                            ->placeholder(__('Industry'))
                            ->required(),
                        Forms\Components\Select::make('experience')
                            ->label('Kinh nghiệm')
                            ->required()
                            ->options(function () {
                                for ($i = 0; $i <= 100; $i++) {
                                    $options[$i] = $i .' Năm';
                                }
                                return $options;
                            }),
                        Forms\Components\TextInput::make('salary')
                            ->label('Mức lương')
                            ->placeholder(__('Salary')),
                        Forms\Components\TextInput::make('position')
                            ->label('Vị trí')
                            ->required(),
//                        Forms\Components\TextInput::make('level')
//                            ->label('Cấp bậc')
//                            ->placeholder(__('Level')),
                        Forms\Components\FileUpload::make('cv')
                            ->placeholder(__('Cv'))
                            ->preserveFilenames()
                            ->uploadProgressIndicatorPosition($position = 'right') // Set the position of the upload progress indicator.
                            ->openable()
                            ->reorderable()
                            ->downloadable()
                            ->previewable(true)
                            ->columnSpan(2)->columns(1)
                            ->required(fn (string $context): bool => $context === 'create'),
                        Forms\Components\DateTimePicker::make('interview_time')
                            ->label('Thời gian phỏng vấn'),
                        Forms\Components\Select::make('interview_result')
                            ->label('Kết quả phỏng vấn')
                            ->options([
                                'pending' => 'Pending',
                                'pass' => 'Pass',
                                'fail' => 'Fail',
                                'other' => 'Other',
                            ]),
                        // group id
                        Forms\Components\Select::make('groups')
                            ->label('Nhóm người dùng')
                            ->relationship('groups', 'name')
                            ->options(function () {
                                if(auth()->user()->is_admin) {
                                    return \App\Models\Group::all()->pluck('name', 'id');
                                }
                                return \App\Models\User::query()->find(auth()->id())->groups->pluck('name', 'id');
                            })
                            ->multiple()
                            ->required()
                            ->preload(),
                    ])->columns(2)
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
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Ngày sinh')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('Ngày sinh')
                    ->formatStateUsing(fn (string $state): string => "<a href='{$state}' target='_blank'>{$state}</a>")->Html()
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
                // select filter industry
                Tables\Filters\SelectFilter::make('industry_id')
                ->label('Ngành nghề')
                    ->relationship('industry', 'name')
                    ->options(function () {
                        return \App\Models\Industry::all()->pluck('name', 'id');
                    }),
                // select filter experience
                Tables\Filters\SelectFilter::make('experience')
                    ->label('Kinh nghiệm')
                    ->options(function () {
                        for ($i = 0; $i <= 100; $i++) {
                            $options[$i] = $i .' years';
                        }
                        return $options;
                    }),
//                Tables\Filters\SelectFilter::make('interview_result')
//                    ->label('Kết quả phỏng vấn')
//                    ->options([
//                        'pending' => 'Pending',
//                        'pass' => 'Pass',
//                        'fail' => 'Fail',
//                        'other' => 'Other',
//                    ]),
//                Tables\Filters\SelectFilter::make('groups')
//                    ->relationship('groups', 'name')
//                    ->options(function () {
//                        if(auth()->user()->is_admin === 1) {
//                            return \App\Models\Group::all()->pluck('name', 'id');
//                        } else {
//                            return \App\Models\User::query()->find(auth()->id())->groups->pluck('name', 'id');
//                        }
//                    })
//                    ->multiple()
//                    ->preload()
//                    ->placeholder('Group'),
                // filter date interview_time
                Tables\Filters\Filter::make('interview_time')
                    ->form([
                        Forms\Components\DateTimePicker::make('interview_time_from')
                            ->label('Thời gian bắt đầu'),
                        Forms\Components\DateTimePicker::make('interview_time_to')
                            ->label('Thời gian kết thúc'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['interview_time_from'] ?? null,
                                fn (Builder $query, $interview_time_from) => $query->where('interview_time', '>=', $interview_time_from)
                            )->when($data['interview_time_to'] ?? null,
                                fn (Builder $query, $interview_time_to) => $query->where('interview_time', '<=', $interview_time_to));
                    })
                    ->indicateUsing(function (array $data) {
                        $indicator = [];
                        if($data['interview_time_from'] ?? null) {
                            $indicator['interview_time_from'] = __('From') . ' ' . $data['interview_time_from'] . Carbon::parse($data['interview_time_from'])->toFormattedDateString();
                        }
                        if($data['interview_time_to'] ?? null) {
                            $indicator['interview_time_to'] = __('To') . ' ' . $data['interview_time_to'] . Carbon::parse($data['interview_time_to'])->toFormattedDateString();
                        }
                        return $indicator;
                    })
                    ->columnSpan(2)->columns(2),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)->filtersFormColumns(2)
            ->actions(actions: [
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
                    // check if user is permitted to send email
                    Tables\Actions\BulkAction::make('sendEmail')
                        ->icon('heroicon-s-pencil')
                        ->action(function (Collection $records, array $data): void {
                            $address = $records->pluck('email')->toArray();
                            $accessToken = Config::query()->first()->access_token;
                            if ($data['type'] == 'gmail') {
                                foreach ($address as $key => $value) {
                                    Mail::send('emails.email', $data, function ($mail) use ($address, $data, $value) {
                                        $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                                        $mail->to(trim($value));
                                        $mail->subject($data['subject']);
                                    });
                                }
                            } else {
                                // get access token from session
                                $client = new \GuzzleHttp\Client();
                                $url = 'https://graph.microsoft.com/v1.0/me/sendMail';
                                foreach ($address as $key => $value) {
                                    $client->request('POST', $url, [
                                        'headers' => [
                                            'Authorization' => 'Bearer ' . $accessToken,
                                        ],
                                        'json' => [
                                            "message" => [
                                                "subject" => $data['subject'], // subject of mail
                                                "body" => [
                                                    "contentType" => "HTML",
                                                    "content" => $data['content'] // content of mail
                                                ],
                                                "toRecipients" => [
                                                    [
                                                        "emailAddress" => [
                                                            "address" =>  $value// email of receiver
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]);
                                }
                            }
                        })
                        ->disabled(\App\Models\User::query()->find(auth()->id())->permissions()->where('name', 'send email')->first() ? false : true)
                        ->form([
                            Forms\Components\Textarea::make('subject')
                                ->required()
                                ->placeholder(__('Subject')),
                            Forms\Components\RichEditor::make('content')
                                ->placeholder(__('Subject')),
                            Forms\Components\Select::make('type')
                                ->required()
                                ->placeholder(__('type'))
                                ->options([
                                    'gmail' => 'Gmail',
                                    'outlook' => 'Outlook',
                                ]),
                        ])
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
            'index' => Pages\ListCvs::route('/'),
            'create' => Pages\CreateCv::route('/create'),
            'edit' => Pages\EditCv::route('/{record}/edit'),
        ];
    }
}
