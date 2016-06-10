<?php

namespace App\Lib\Packages\EmailRelay;

use App\Jobs\Relay as Worker;
use App\Lib\Packages\EmailRelay\Exceptions\InvalidThreadException;
use App\Lib\Packages\EmailRelay\Exceptions\InvalidEmailException;
use App\Lib\Packages\Core\Validators\ValidatesConfig;
use App\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Database\Query\Builder;
use App\Lib\Packages\EmailRelay\Exceptions\MutedThreadException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Lib\Packages\EmailRelay\Models\EmailRelay;

/**
 * Class Postmaster
 *
 * Processes incoming mail traffic redirected by our catch
 * all filter and relay messages to the right user email.
 *
 * @package App\Lib\Packages\EmailRelay
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Postmaster {

    use ValidatesConfig, DispatchesJobs;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var \Illuminate\Database\Connection
     */
    private $database;

    /**
     * RelayGateway constructor.
     * @param array $config
     * @param Mailer $mailer
     * @param DatabaseManager $databaseManager
     */
    public function __construct(array $config, Mailer $mailer, DatabaseManager $databaseManager)
    {
        $this->validateConfig($config, ['host']);

        $this->config   = $config;
        $this->mailer   = $mailer;
        $this->database = $databaseManager->connection();
    }

    /**
     * @param Email $email
     * @throws InvalidEmailException
     * @throws InvalidThreadException
     */
    public function handle(Email $email)
    {
        $recipient      = $this->unmask($email->getRecipient());
        $maskedEmail    = $this->mask($email->getSender());
        $getName        = $this->fromName($email->getSender());

        // Let's make sure that neither of the users has muted the other.
        // $this->canEmail($email->getRecipient(), $maskedEmail);

        // We can now relay this message to the intended user,
        // add to our handy-dandy queue
        $this->dispatch(new Worker($recipient, $maskedEmail, $email->getSubject(), $email->getBody(), $getName));
    }

    /**
     * @param string $email
     * @return string
     */
    private function fromName(string $email)
    {
        /**
         * @var User $user
         */
        $user = User::where('email', '=', $email)->firstOrFail();

        return $user->getFirstName() . ' ' . $user->getLastName();
    }

    /**
     * @param string $email_1
     * @param string $email_2
     * @throws InvalidThreadException
     * @throws MutedThreadException
     */
    public function canEmail(string $email_1, string $email_2)
    {
        $emailId_1 = array_get(explode('@', $email_1), 0);
        $emailId_2 = array_get(explode('@', $email_2), 0);

        $result = $this->database->table('email_relay as')
                                 ->where('id', '=', $emailId_1)
                                 ->orWhere('id', '=', $emailId_2)
                                 ->groupBy('booking_id')
                                 ->pluck('fk_booking_id');

        if (!count($result)) {
            throw new InvalidThreadException;
        }

        // Check if conversation is muted
        $muted = $this->database->table('email_relay')
                               ->where(function (Builder $query) use($emailId_1, $emailId_2) {
                                   $query->where('id', '=', $emailId_1)
                                         ->orWhere('id', '=', $emailId_2);
                               })->where('muted', '=', 1)
                               ->exists();

        if ($muted) {
            throw new MutedThreadException;
        }
    }

    /**
     * @param string $emailToMask
     * @returns string
     * @throws InvalidEmailException
     */
    private function mask(string $emailToMask)
    {
        $recipient = $this->database->table('email_relay as a')
                                    ->join('users as b', 'a.fk_user_id', '=', 'b.id')
                                    ->where('b.email', '=', $emailToMask)
                                    ->value('a.id');

        if (!$recipient) {
            // Need to create new relay address, let's check that this is an email belonging to a user:
            $userId = $this->database->table('users')->where('email', '=', $emailToMask)->value('id');

            if (!$userId) {
                // This email address does not belong to a registered
                // user, this isn't allowed.
                throw new InvalidEmailException("Unable to match email '{$emailToMask}' to an existing user.");
            }

            $recipient = $this->createRelayAddress($userId);
        }

        return $recipient . '@' . $this->config['host'];
    }

    /**
     * @param string $userId
     * @return string
     */
    public function createRelayAddress(string $userId)
    {
        $address = new EmailRelay();
        $address->setFkUserId($userId);
        $address->save();

        return $address->getId();
    }

    /**
     * @param string $email
     * @return string
     * @throws InvalidEmailException
     */
    private function unmask(string $email)
    {
        $components = explode('@', $email);

        if (count($components) !== 2) {
            // Something very weird. We don't have email addresses
            // that have an at sign.
            throw new InvalidEmailException("Unable to parse email address: {$email}");
        }

        $recipient = $this->database->table('email_relay as a')
                                    ->join('users as b', 'a.fk_user_id', '=', 'b.id')
                                    ->where('a.id', '=', $components[0])
                                    ->value('b.email');

        if (!$recipient) {
            // This is not a valid relay address
            // that we've created.
            throw new InvalidEmailException("Unable to match {$components[0]} to an existing relay email");
        }

        return $recipient;
    }
}