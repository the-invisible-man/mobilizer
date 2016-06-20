<?php

namespace App\Jobs;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendBookingNotificationEmail
 * @package App\Jobs
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class SendBookingNotificationEmail extends Job implements ShouldQueue
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
        $mailer->send('emails.booking_notification', $this->data, function (Message $email) {
            $email->to($this->data['to_email']);
            $email->subject('Your Have a New Request');
        });

        $mailer->send('emails.booking_confirmation', $this->data, function (Message $email) {
            $email->to($this->data['confirm_to_email']);
            $email->subject('Your Booking Request Has Been Sent');
        });

        $this->delete();
    }
}
