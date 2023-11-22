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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cv Information')
                    ->description('This information will be publicly visible.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->autofocus()
                            ->required()
                            ->placeholder(__('Name')),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignorable: fn ($record) => $record)
                            ->placeholder(__('Email')),
                        Forms\Components\TextInput::make('phone')
                            ->placeholder(__('Phone')),
                        Forms\Components\DatePicker::make('birthday')
                            ->placeholder(__('Birthday')),
                        Forms\Components\Textarea::make('note')
                            ->placeholder(__('Note')),
                        Forms\Components\Textarea::make('address')
                            ->placeholder(__('Address')),

                        Forms\Components\Select::make('industry')
                            ->placeholder(__('Industry'))
                            ->required()
                            ->options([
                                'it' => 'IT',
                                'marketing' => 'Marketing',
                                'sale' => 'Sale',
                                'hr' => 'HR',
                                'accountant' => 'Accountant',
                                'other' => 'Other',
                            ]),
                        Forms\Components\Select::make('experience')
                            ->required()
                            ->placeholder(__('Experience'))
                            ->options(function () {
                                for ($i = 0; $i <= 100; $i++) {
                                    $options[$i] = $i .' years';
                                }
                                return $options;
                            }),
                        Forms\Components\TextInput::make('salary')
                            ->placeholder(__('Salary')),
                        Forms\Components\Select::make('position')
                            ->required()
                            ->placeholder(__('Position'))
                            ->options([
                                'dev' => 'Dev',
                                'tester' => 'Tester',
                                'ba' => 'BA',
                                'pm' => 'PM',
                                'hr' => 'HR',
                                'accountant' => 'Accountant',
                                'other' => 'Other',
                            ]),
                        Forms\Components\Select::make('level')
                            ->placeholder(__('Level'))
                            ->options([
                                'junior' => 'Junior',
                                'senior' => 'Senior',
                                'leader' => 'Leader',
                                'other' => 'Other',
                            ]),
                        Forms\Components\Select::make('language')
                            ->placeholder(__('Language'))
                            ->options([
                                'english' => 'English',
                                'japanese' => 'Japanese',
                                'chinese' => 'Chinese',
                                'korean' => 'Korean',
                                'other' => 'Other',
                            ]),

                        Forms\Components\Textarea::make('skill')
                            ->rows(3)
                            ->placeholder(__('Skill')),
                        Forms\Components\FileUpload::make('cv')
                            ->placeholder(__('Cv'))
                            ->preserveFilenames()
                            ->uploadProgressIndicatorPosition($position = 'right') // Set the position of the upload progress indicator.
                            ->openable()
                            ->reorderable()
                            ->downloadable()
                            ->previewable(true)
                            ->required(fn (string $context): bool => $context === 'create'),

                        Forms\Components\DateTimePicker::make('interview_time')
                            ->placeholder(__('Interview Time')),
                        Forms\Components\Select::make('interview_result')
                            ->placeholder(__('Interview Result'))
                            ->options([
                                'pending' => 'Pending',
                                'pass' => 'Pass',
                                'fail' => 'Fail',
                                'other' => 'Other',
                            ]),
                        // group id
                        Forms\Components\Select::make('groups')
                            ->relationship('groups', 'name')
                            ->options(function () {
                                if(auth()->user()->is_admin) {
                                    return \App\Models\Group::all()->pluck('name', 'id');
                                }
                                return \App\Models\User::query()->find(auth()->id())->groups->pluck('name', 'id');
                            })
                            ->multiple()
                            ->required()
                            ->preload()
                            ->placeholder('Group')
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // select filter industry
                Tables\Filters\SelectFilter::make('industry')
                    ->placeholder(__('Industry'))
                    ->options([
                        'it' => 'IT',
                        'marketing' => 'Marketing',
                        'sale' => 'Sale',
                        'hr' => 'HR',
                        'accountant' => 'Accountant',
                        'other' => 'Other',
                    ]),
                // select filter position
                Tables\Filters\SelectFilter::make('position')
                    ->placeholder(__('Position'))
                    ->options([
                        'dev' => 'Dev',
                        'tester' => 'Tester',
                        'ba' => 'BA',
                        'pm' => 'PM',
                        'hr' => 'HR',
                        'accountant' => 'Accountant',
                        'other' => 'Other',
                    ]),
                // select filter level
                Tables\Filters\SelectFilter::make('level')
                    ->placeholder(__('Level'))
                    ->options([
                        'junior' => 'Junior',
                        'senior' => 'Senior',
                        'leader' => 'Leader',
                        'other' => 'Other',
                    ]),
                // select filter language
                Tables\Filters\SelectFilter::make('language')
                    ->placeholder(__('Language'))
                    ->options([
                        'english' => 'English',
                        'japanese' => 'Japanese',
                        'chinese' => 'Chinese',
                        'korean' => 'Korean',
                        'other' => 'Other',
                    ]),
                // select filter experience
                Tables\Filters\SelectFilter::make('experience')
                    ->placeholder(__('Experience'))
                    ->options(function () {
                        for ($i = 0; $i <= 100; $i++) {
                            $options[$i] = $i .' years';
                        }
                        return $options;
                    }),
                Tables\Filters\SelectFilter::make('interview_result')
                    ->placeholder(__('Interview Result'))
                    ->options([
                        'pending' => 'Pending',
                        'pass' => 'Pass',
                        'fail' => 'Fail',
                        'other' => 'Other',
                    ]),
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
                            ->placeholder(__('Interview Time')),
                        Forms\Components\DateTimePicker::make('interview_time_to')
                            ->placeholder(__('Interview Time')),
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
                    ->columnSpan(2)->columns(2)
                    ->label(__('Interview Time')),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)->filtersFormColumns(4)
            ->actions(actions: [Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
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
