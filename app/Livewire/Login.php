<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Action;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

#[Layout("components.layouts.auth", ['heading' => "Sign in to your account"])]
class Login extends Component
{
    #[Validate("required|email|max:255")]
    public $email = "";

    #[Validate("required|max:100")]
    public $password = "";

    public function render()
    {
        return view('livewire.login');
    }

    public function loginActionButton(){
        return Action::make('attemptLogin')->label("Login")->color("info")->extraAttributes([
            'class' => 'w-full',
        ]);
    }

    public function mountAction($action){
        $this->$action();
    }

    public function attemptLogin(){

        $this->validate();

        if(Auth::attempt(['email' => $this->email, 'password' => $this->password])){
            return redirect()->intended(route('users.listing'));
        }

        $this->addError("email", "These credentials do not match our records.");

    }
}
