<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitacionMail extends Mailable
{
    use Queueable, SerializesModels;

     public $token;
    public $passwordTemp;

    public function __construct($token, $passwordTemp)
    {
        $this->token = $token;
        $this->passwordTemp = $passwordTemp;
    }

    public function build()
    {
        $urlActivacion = env('APP_URL')."/api/activar/{$this->token}";
        return $this->subject('Invitación a sistema ERP')
                    ->view('emails.invitacion')
                    ->with([
                        'urlActivacion' => $urlActivacion,
                        'passwordTemp' => $this->passwordTemp,
                    ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitacion Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
