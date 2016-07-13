<?php

namespace App\Http\Controllers;

use App\Lib\Packages\EmailRelay\Email;
use App\Lib\Packages\EmailRelay\Postmaster;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

/**
 * Class EmailRelayController
 *
 * @package     App\Http\Controllers
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 *
 */
class EmailRelayController extends Controller
{
    use DispatchesJobs;

    /**
     * @var Postmaster
     */
    private $relayGateway;

    /**
     * EmailRelayController constructor.
     * @param Postmaster $relayGateway
     */
    public function __construct(Postmaster $relayGateway)
    {
        $this->relayGateway = $relayGateway;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receive(Request $request)
    {
        $responseCode = 200;

        try {
            $message = $this->extract($request->all());
            $this->relayGateway->handle($message);
        } catch (\Exception $e) {
            \Log::info("[EmailRelayController] {$e->getMessage()}");
            $responseCode = 500;
        }

        return \Response::json([], $responseCode);
    }

    /**
     * @param array $data
     * @return Email
     */
    public function extract(array $data)
    {
        $missing = array_diff(['sender', 'recipient', 'subject', 'body-plain'], array_keys($data));

        if (count($missing)) {
            throw new \InvalidArgumentException("Incomplete email array, could not find: " . implode(', ', $missing));
        }

        $email = new Email();

        return $email->setBody($data['body-plain'])
                     ->setRecipient($data['recipient'])
                     ->setSender($data['sender'])
                     ->setSubject($data['subject']);
    }
}