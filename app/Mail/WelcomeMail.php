<?php
/**
 * Rush Verification Mail
 */
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class WelcomeMail
 * @package App\Mail
 */
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Name of receiver
     *
     */
    protected $name = '';

    /**
     * Claim No. of receiver
     *
     */
    protected $claim = '';

    /**
     * Create a new message instance.
     * @param String $code
     * @return void
     */
    public function __construct($name, $claim)
    {
        $this->name = $name;
        $this->claim = $claim;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Thank you for Registering your Product')
                    ->view('mail.welcome')
                    ->with([
                        'name' => $this->name,
                        'claim' => $this->claim
                    ]);
    }
}