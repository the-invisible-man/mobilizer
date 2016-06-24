<?php

namespace App\Lib\Packages\EmailRelay;

use App\Lib\Packages\EmailRelay\Models\EmailRelay;

use Illuminate\Database\DatabaseManager;

class RelayGateway {

    /**
     * @var \Illuminate\Database\Connection
     */
    private $db;

    /**
     * RelayGateway constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->db = $databaseManager->connection();
    }

    /**
     * @param string $userId
     * @return string
     */
    public function getCreateRelayAddress(string $userId) : string
    {
        $address = $this->db->table('email_relay')->where('fk_user_id', '=', $userId)->value('id');

        if (!$address) {
            return $this->createRelayAddress($userId);
        }

        return $address . '@seeyouinphilly.com';
    }

    /**
     * @param string $userId
     * @return string
     */
    public function createRelayAddress(string $userId) : string
    {
        $address = new EmailRelay();
        $address->setFkUserId($userId);
        $address->save();

        return $address->getId();
    }
}