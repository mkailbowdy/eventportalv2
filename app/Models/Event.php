<?php

namespace App\Models;

use App\Enums\Category;
use App\Enums\Prefecture;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'capacity' => 'integer',
        'group_id' => 'integer',
        'user_id' => 'integer',
    ];

    public static function getForm(): array
    {
        return [
            Section::make('Event')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    RichEditor::make('description')
                        ->required()
                        ->columnSpanFull(),
                    Select::make('category')
                        ->required()
                        ->helperText(new HtmlString('Choose the category that <strong>best</strong> describes this event'))
                        ->live()
                        ->enum(Category::class)
                        ->options(Category::class)
                        ->searchable(),
                    TextInput::make('capacity')
                        ->required()
                        ->helperText(new HtmlString('The <strong>max</strong> number of people that may attend'))
                        ->numeric()
                        ->maxValue(100),

                ]),
            Section::make('When')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->columns(3)
                ->schema([
                    DatePicker::make('date')
                        ->required()
                        ->minDate(now()),
                    TimePicker::make('start_time')
                        ->required()
                        ->helperText(new HtmlString('Use 24-hour format, e.g., 00:00 (midnight) to 23:59'))
                        ->native(false)
                        ->seconds(false),
                    TimePicker::make('end_time')
                        ->required()
                        ->helperText(new HtmlString('Use 24-hour format, e.g., 00:00 (midnight) to 23:59'))
                        ->native(false)
                        ->seconds(false),
                ]),

            Section::make('Where')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->columns(2)
                ->schema([
                    TextInput::make('meeting_spot')
                        ->required()
                        ->columnSpanFull()
                        ->hint(new HtmlString('<a href="/maps">Get the address using our <strong>Google Maps</strong> feature!</a>'))
                        ->helperText(new HtmlString('e.g. Osaka Castle, 1-1 Osakajo, Chuo Ward, Osaka, 540-0002')),
                    Select::make('prefecture')
                        ->required()
                        ->live()
                        ->enum(Prefecture::class)
                        ->options(Prefecture::class)
                        ->searchable(),
                ]),
            Section::make('Photos and Files')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('images')
                        ->columnSpanFull()
                        ->imageEditor()
                        ->collection('event-images')
                        ->multiple()
                        ->maxFiles(3)
                        ->reorderable()
                        ->appendFiles()
                        ->responsiveImages(),
                ]),
            Actions::make([
                Action::make('star')
                    ->label('Fill with Factory Data')
                    ->icon('heroicon-m-star')
                    ->visible(function (string $operation) {
                        if ($operation !== 'create') {
                            return false;
                        }
                        if (!app()->environment('local')) {
                            return false;
                        }
                        return true;
                    })
                    ->action(function ($livewire) {
                        $data = Event::factory()->make()->toArray();
                        $livewire->form->fill($data);
                    }),

            ]),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

}
