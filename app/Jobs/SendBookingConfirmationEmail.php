<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendBookingConfirmationEmail
 * @package App\Jobs
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class SendBookingConfirmationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    protected $data;

    /**
     * Create a new job instance.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return bool
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('emails.booking_confirmation', $this->data, function (Message $email) {
            $email->to($this->data['confirm_to_email']);
            $email->from('no-reply@seeyouinphilly.com', '#SeeYouInPhilly Alerts');
            $email->subject('Your Booking Request Has Been Sent');
        });
    }
}