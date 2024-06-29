<?php

namespace App\Models;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
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
        'prefecture',
        'photo_path',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
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
            TextInput::make('prefecture')
                ->required()
                ->maxLength(255),
            TextInput::make('photo_path')
                ->maxLength(255),
        ];
    }

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }
}
