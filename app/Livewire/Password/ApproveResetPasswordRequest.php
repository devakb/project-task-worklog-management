<?php

namespace App\Livewire\Password;

use App\Models\User;
use App\Notifications\UserPasswordChangedNotification;
use Livewire\Component;
use Filament\Actions\Action;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Cache;

#[Layout("components.layouts.auth", ["heading" => "Change Password"])]
class ApproveResetPasswordRequest extends Component
{

    #[Validate("required")]
    public $password = "";

    #[Validate("required|same:password")]
    public $confirm_password = "";

    public $isPasswordChanged = false;

    public $user;

    public function mount(){

        $key = request()->get("key");
        $email = request()->get("email");

        $user = User::whereEmail($email)->firstOrfail();

        $cache_key = "password-reset-{$user->id}";

        $cache_token = Cache::get($cache_key);

        if($cache_token != $key){
            abort(419);
        }

        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.password.approve-reset-password-request');
    }

    public function passwordChangeActionButton(){
        return Action::make('attemptPasswordChange')->label("Change Password")->color("info")->extraAttributes([
            'class' => 'w-full',
        ]);
    }

    public function mountAction($action){
        $this->$action();
    }

    public function attemptPasswordChange(){

        $this->validate();

        $this->user->update(['password' => $this->password]);

        $this->user->notify(new UserPasswordChangedNotification);

        $cache_key = "password-reset-{$this->user->id}";
        Cache::forget($cache_key);

        $this->isPasswordChanged = true;

        $this->dispatch("passwordChanged");
    }
}
