<?php

namespace App\Console\Commands;

use App\Lib\Packages\Core\EmailListForNotifications;
use App\Lib\Packages\Search\SearchGateway;
use ErrorStream\ErrorStreamClient\ErrorStreamClient;
use Illuminate\Console\Command;
use Illuminate\Contracts\Logging\Log;
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
     * @var Log
     */
    private $log;

    /**
     * @var ErrorStreamClient
     */
    private $errorStreamClient;

    /**
     * NotifyBackOfListing constructor.
     * Create a new command instance.
     * @param SearchGateway $searchGateway
     * @param Mailer $mailer
     * @param Log $log
     * @param ErrorStreamClient $errorStreamClient
     */
    public function __construct(SearchGateway $searchGateway, Mailer $mailer, Log $log, ErrorStreamClient $errorStreamClient)
    {
        parent::__construct();

        $this->searchGateway        = $searchGateway;
        $this->mailer               = $mailer;
        $this->log                  = $log;
        $this->errorStreamClient    = $errorStreamClient;
    }

    public function handle()
    {
        // We'll fetch the data of those subscribers who have yet to be notified
        $subscribers    = EmailListForNotifications::query()->where('notified', '=', 0)->get();

        foreach ($subscribers as $subscriber) {
            try {
                $this->info('Searching for subscriber ' . $subscriber->getEmail());
                $results = $this->searchGateway->searchRide($subscriber->getQuery(), 1);
            } catch (\Exception $e) {
                $this->errorStreamClient->reportException(new $e('[NotifyBackOfListing]' . $e->getMessage()));
                $this->info('There was an error searching for user: ' . $e->getMessage());
                continue;
            }

            if (!count($results['results'])) {
                $this->info('No matching listing found');
                continue;
            }

            $this->info('Found ' . $results['number_of_hits'] . ' results for this user, will email.');

            // Notify this subscriber that we have something available for them
            $this->mailer->send('emails.notifications.matching_listing_available', $results, function (Message $email) use($subscriber) {
                $email->to($subscriber->getEmail());
                $email->subject('We Found Some Rides For You!');
            });

            // Mark this guy as 'done'
            $subscriber->setNotified(1)->save();
        }
    }
}
