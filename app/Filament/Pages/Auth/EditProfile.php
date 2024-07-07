<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Auth;


class EditProfile extends BaseEditProfile
{
    // If user deletes their avatar picture, then set their avatar to a default avatar
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!$data['avatar_url']) {
            $data['avatar_url'] = 'https://ui-avatars.com/api/?name='.urlencode($data['name']);
        }
        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar_url')
                    ->avatar(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
