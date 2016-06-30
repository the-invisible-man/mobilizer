<?php

namespace App\Console\Commands;

use App\Lib\Packages\Core\EmailListForNotifications;
use App\Lib\Packages\Search\SearchGateway;
use Illuminate\Console\Command;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;

class NotifyBackOfListing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NotifyBackOfListing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notification emails to users who did not have any matches when searching on th site. ' .
                            'This command keeps searching for them.';

    /**
     * @var SearchGateway
     */
    private $searchGateway;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * NotifyBackOfListing constructor.
     * Create a new command instance.
     * @param SearchGateway $searchGateway
     * @param Mailer $mailer
     */
    public function __construct(SearchGateway $searchGateway, Mailer $mailer)
    {
        parent::__construct();

        $this->searchGateway    = $searchGateway;
        $this->mailer           = $mailer;
    }


    public function handle()
    {
        // We'll fetch the data of those subscribers who have yet to be notified
        $subscribers    = EmailListForNotifications::query()->where('notified', '=', 0)->get();

        foreach ($subscribers as $subscriber) {
            $results = $this->searchGateway->searchRide($subscriber->getQuery(), 1);

            if (!count($results['results'])) continue;

            // Notify this subscriber that we have something available for them
            $this->mailer->send('emails.notifications.matching_listing_available', $results, function (Message $email) use($subscriber) {
                $email->to($subscriber->getEmail());
                $email->subject('We Found Some Rides For You!');
            });

            // Mark this guy as 'done'
            $subscriber->setNotified(1)
                ->save();
        }
    }
}
