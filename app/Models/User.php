<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Components\Section as InfolistSection;
use Illuminate\Support\Facades\Storage;


class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted()
    {
        // set a default avatar using their name
        static::created(function ($user) {
//            $user->avatar_url = 'https://ui-avatars.com/api/?name='.urlencode($user->name);
//            $user->save();
            event(new Registered($user));
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withPivot('participation_status');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getInfoList(): array
    {
        return [
            InfoListSection::make('Bio')
                ->columnSpanFull()
                ->label(false)
                ->columns(1)
                ->schema([
                    ImageEntry::make('avatar_url')
                        ->label(false)
                        ->circular()
                        ->columnSpanFull(),
                    TextEntry::make('name'),
                    TextEntry::make('bio')
                        ->label('Hobbies and interests'),
                    TextEntry::make('location'),
                ]),
//            InfoListSection::make('When')
//                ->columns(3)
//                ->schema([
//                    TextEntry::make('date')
//                        ->date(),
//                    TextEntry::make('start_time')
//                        ->time('H:m'),
//                    TextEntry::make('end_time')
//                        ->time('H:m'),
//                ]),
        ];
    }

}
