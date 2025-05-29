<?php

namespace App\Livewire;

use App\Http\Requests\ContactRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $message = '';

    protected function rules(): array
    {
        return (new ContactRequest())->rules();
    }

    protected function messages(): array
    {
        return (new ContactRequest())->messages();
    }

    public function submit(): void
    {
        $this->validate();

        try {
            Log::info('Contact form submission', [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
                'timestamp' => now()
            ]);

            session()->flash('success', 'Thank you for your message! We will get back to you soon.');
            
            $this->reset(['name', 'email', 'subject', 'message']);
        } catch (\Exception $e) {
            Log::error('Contact form error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Sorry, there was an error sending your message. Please try again.');
        }
    }

    public function render(): View
    {
        return view('livewire.contact-form');
    }
} 