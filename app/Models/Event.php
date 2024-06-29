<?php

namespace App\Models;

use App\Enums\Prefecture;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
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
            RichEditor::make('description')
                ->required()
                ->columnSpanFull(),
            DateTimePicker::make('start_date')
                ->required(),
            DateTimePicker::make('end_date')
                ->required(),
            TextInput::make('capacity')
                ->required()
                ->numeric(),
            Select::make('prefecture')
                ->required()
                ->live()
                ->enum(Prefecture::class)
                ->options(Prefecture::class)
                ->searchable(),
            TextInput::make('meeting_spot')
                ->required()
                ->maxLength(255),
//                Forms\Components\TextInput::make('photo_path')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('group_id')
//                    ->numeric(),
//                Forms\Components\Select::make('user_id')
//                    ->relationship('user', 'name')
//                    ->required(),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
