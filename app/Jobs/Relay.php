<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Message;

/**
 * Class EmailRelay
 * @package App\Jobs
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Relay extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $name;

    /**
     * EmailRelay constructor.
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $body
     * @param string $name
     */
    public function __construct(string $to, string $from, string $subject, string $body, string $name = null)
    {
        $this->to       = $to;
        $this->from     = $from;
        $this->subject  = $subject;
        $this->body     = $body;
        $this->name     = $name;
    }

    /**
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('emails.relay', ['content' => $this->body], function (Message $message) {
            $message->to($this->to);
            $message->from($this->from, $this->name);
            $message->subject($this->subject);
        });
    }
}