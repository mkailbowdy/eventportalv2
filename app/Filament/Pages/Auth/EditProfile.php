<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Auth;
use Throwable;
use function Filament\Support\is_app_url;


class EditProfile extends BaseEditProfile
{
    // If user deletes their avatar picture, then set their avatar to a default avatar
//    protected function mutateFormDataBeforeSave(array $data): array
//    {
//        if (!$data['avatar_url']) {
//            $data['avatar_url'] = 'https://ui-avatars.com/api/?name='.urlencode($data['name']);
//        }
//        return $data;
//    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getUser(), $data);

            $this->callHook('afterSave');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_'.Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->data['password'] = null;
        $this->data['passwordConfirmation'] = null;

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }

        redirect('/app');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar_url')
                    ->label('Your Profile Picture')
                    ->avatar(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                MarkdownEditor::make('bio')
                    ->disableAllToolbarButtons(),
                TextInput::make('location')
                    ->label('Location'),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                DatePicker::make('date_of_birth')
                    ->label('Date of Birth')
                    ->maxDate(now()->subYears(18))
            ]);
    }
}
