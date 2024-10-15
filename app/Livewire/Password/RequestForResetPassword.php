<?php

namespace App\Livewire\Password;

use App\Models\User;
use Livewire\Component;
use Filament\Actions\Action;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Notifications\UserPasswordResetNotification;

#[Layout("components.layouts.auth", ["heading" => "Reset Your Password"])]
class RequestForResetPassword extends Component
{
    #[Validate("required|email|max:255")]
    public $email = "";

    public $success_message = null;

    public function render()
    {
        return view('livewire.password.request-for-reset-password');
    }

    public function passwordResetRequestActionButton(){
        return Action::make('attemptRequest')->label("Send Request for Password Reset")->color("info")->extraAttributes([
            'class' => 'w-full',
        ]);
    }

    public function mountAction($action){
        $this->$action();
    }

    public function attemptRequest(){

        $this->validate();

        $user = User::where('email', $this->email)->first();

        if($user){

            $user->notify(new UserPasswordResetNotification);

            $this->success_message = "We have sent you an email with password reset links. Please check your email inbox.";
        }else{

            $this->addError("email", "These emails are not registered with us. If you think that was a mistake, please contact your administrator.");

        }


    }
}
