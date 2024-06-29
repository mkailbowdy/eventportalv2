<?php

namespace App\Models;

use App\Enums\Category;
use App\Enums\Prefecture;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'capacity',
        'prefecture',
        'meeting_spot',
        'photo_path',
        'group_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'capacity' => 'integer',
        'group_id' => 'integer',
        'user_id' => 'integer',
    ];

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            MarkdownEditor::make('description')
                ->required()
                ->columnSpanFull(),
            Select::make('category')
                ->required()
                ->live()
                ->enum(Category::class)
                ->options(Category::class)
                ->searchable(),
            TextInput::make('capacity')
                ->required()
                ->numeric(),
            DateTimePicker::make('start_date')
                ->required(),
            DateTimePicker::make('end_date')
                ->required(),
            Select::make('prefecture')
                ->required()
                ->live()
                ->enum(Prefecture::class)
                ->options(Prefecture::class)
                ->searchable(),
            TextInput::make('meeting_spot')
                ->required(),
            FileUpload::make('featured_image')
                ->columnSpanFull()
                ->label('Featured Image')
                ->directory('featured_image')
                ->imageEditor()
                ->maxSize(1024 * 1024 * 10)
                ->imagePreviewHeight('250')
                ->loadingIndicatorPosition('left')
                ->panelAspectRatio('2:1')
                ->panelLayout('integrated')
                ->removeUploadedFileButtonPosition('right')
                ->uploadButtonPosition('left')
                ->uploadProgressIndicatorPosition('left'),
            Actions::make([
                Action::make('star')
                    ->label('Fill with Factory Data')
                    ->icon('heroicon-m-star')
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
}
