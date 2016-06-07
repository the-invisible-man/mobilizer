<?php

namespace App\Jobs;

use App\Lib\Packages\Bookings\Contracts\BaseBooking;
use App\Lib\Packages\Bookings\Models\BookingMetadata;
use App\Lib\Packages\Geo\Location\Location;
use App\Lib\Packages\Listings\ListingsGateway;
use App\User;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingNotificationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var BaseBooking
     */
    protected $booking;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param BaseBooking $booking
     * @param BookingMetadata $metadata
     * @param Location $location
     */
    public function __construct(User $user, BaseBooking $booking, BookingMetadata $metadata, Location $location)
    {
        $this->user         = $user;
        $this->booking      = $booking;

        $this->booking->setMetadata($metadata);
        $this->booking->getMetadata()->setLocation($location);
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @param ListingsGateway $gateway
     * @return void
     */
    public function handle(Mailer $mailer, ListingsGateway $gateway)
    {
        $data['booking']                = $this->booking->toArray();
        $data['booking']['user']        = $this->user->toArray();
        $data['booking']['metadata']    = $this->booking->getMetadata()->toArray();
        $data['booking']['location']    = $this->booking->getMetadata()->getLocation()->toArray();

        $listing = $gateway->find($this->booking->getId());

        $data['listing']['location']    = $listing->getLocation()->toArray();

        $mailer->send('emails.booking_notification', $data, function (Message $email) {
            $email->to($this->user->getEmail());

        });
    }
}
