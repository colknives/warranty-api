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
     * Product Type ordered by receiver
     *
     */
    protected $productType = '';

    /**
     * Serial Number of receiver
     *
     */
    protected $serialNumber = '';

    /**
     * Create a new message instance.
     * @param String $code
     * @return void
     */
    public function __construct($name, $claim, $productType, $serialNumber)
    {
        $this->name = $name;
        $this->claim = $claim;
        $this->productType = $productType;
        $this->serialNumber = $serialNumber;
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
                        'claim' => $this->claim,
                        'productType' => $this->productType,
                        'serialNumber' => $this->serialNumber
                    ]);
    }
}