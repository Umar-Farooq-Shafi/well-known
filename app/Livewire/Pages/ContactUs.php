<?php

namespace App\Livewire\Pages;

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ContactUs extends Component
{
    use WireUiActions;

    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|min:3')]
    public $email;

    #[Validate('required|min:3')]
    public $subject;

    #[Validate('required|min:3')]
    public $message;

    public $captcha = 0;

    public function updatedCaptcha($token)
    {
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret=' . env('CAPTCHA_SECRET_KEY') . '&response=' . $token);
        $this->captcha = $response->json()['score'];

        if (!$this->captcha > .3) {
            $this->submit();
        } else {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Google thinks you are a bot, please refresh and try again',
            ]);
        }
    }

    public function submit()
    {
        $validated = $this->validate();


    }

    public function render()
    {
        return view('livewire.pages.contact-us');
    }
}
