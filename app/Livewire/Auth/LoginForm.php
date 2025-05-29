<?php

namespace App\Livewire\Auth;

use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return (new LoginRequest())->rules();
    }

    protected function messages(): array
    {
        return (new LoginRequest())->messages();
    }

    public function login(): void
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        session()->regenerate();
        
        session()->flash('success', 'Welcome back, ' . auth()->user()->name . '!');
        
        $this->redirect(route('movies.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.login-form');
    }
} 