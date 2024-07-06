<?php

namespace App\Models;

use App\Enums\Category;
use App\Enums\Prefecture;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
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
        'event_gallery' => 'array'
    ];

    public static function getForm(): array
    {
        return [
            Section::make('Event')
                ->description(new HtmlString('Please be sure to read <a href="/"><strong>Event Post Guidelines</strong></a> before uploading an event.'))
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
            Section::make('Photos')
                ->description('Need to make some quick adjustments to your images? Try out the Image Editor by pressing the pencil icon!')
                ->schema([
                    FileUpload::make('featured_image')
                        ->directory('event_images')
                        ->helperText('The image that will be shown on the Event listings page')
                        ->imageEditor()
                        ->maxSize(1024 * 1024 * 10),
                    FileUpload::make('event_gallery')
                        ->directory('event_images')
                        ->helperText('Upload up to 3 additional images')
                        ->multiple()
                        ->imageEditor()
                        ->maxFiles(3)
                        ->maxSize(1024 * 1024 * 10),
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
                        $data['user_id'] = Auth::id();
                        $livewire->form->fill($data);
                    }),

            ]),
        ];
    }

    public static function goingOrNot(Event $event)
    {
        if (!Auth::check()) {
            return redirect('/dashboard');
        }
        $userId = Auth::id();
        // add to event user table
        $event->users()->syncWithoutDetaching([$userId]);

        $participant = $event->users()->where('users.id', $userId)->first();

        // Toggle the participation status
        $newStatus = !$participant || !$participant->pivot->participation_status;
        $event->users()->updateExistingPivot($userId, ['participation_status' => $newStatus]);
        if ($participant->participation_status === 1) {
            return 'Going';
        }
        return 'Not Going';
    }


    public function getParticipationStatusAttribute()
    {
        // Assuming there's a currently authenticated user
        $user = auth()->user();
        if (!$user) {
            // set participation_status to 0,
            return 0;
        }
        // Get the pivot row for the current user
        $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;

        // Return the participation status
        return $pivot?->participation_status ?? 'Not Participating';
    }

    public function getParticipantsCountAttribute()
    {
        return $this->users()->wherePivot('participation_status', 1)->count();
    }

    public function getParticipantAvatarsAttribute()
    {
        return $this->users()->wherePivot('participation_status', 1)->pluck('avatar_url')->toArray();
    }

    public function getEventCreatorAvatarAttribute()
    {
        return $this->users()->wherePivot('event_creator', 1)->first()->avatar_url;
    }

    // Add this computed property to your resource or model
    public function getParticipationStatusLabelAttribute()
    {
        return $this->participation_status == 0 ? 'Not going' : 'Going';
    }

    public function users(): BelongsToMany
    {
        // if we dont add withPivot, we can only get the user_id and event_id, but not participation
        return $this->belongsToMany(User::class)
            ->withPivot(['participation_status']);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
