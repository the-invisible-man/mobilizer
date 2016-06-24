<?php

namespace App\Lib\Packages\EmailRelay;

use App\Lib\Packages\Core\Validators\ValidatesConfig;
use App\Lib\Packages\EmailRelay\Models\EmailRelay;

use Illuminate\Database\DatabaseManager;

class RelayGateway {

    use ValidatesConfig;

    /**
     * @var \Illuminate\Database\Connection
     */
    private $db;

    /**
     * @var array
     */
    private $config;

    /**
     * RelayGateway constructor.
     * @param array $config
     * @param DatabaseManager $databaseManager
     */
    public function __construct(array $config, DatabaseManager $databaseManager)
    {
        $this->db       = $databaseManager->connection();
        $this->config   = $this->validateConfig($config, ['host']);
    }

    /**
     * @param string $userId
     * @return string
     */
    public function getCreateRelayAddress(string $userId) : string
    {
        $address = $this->db->table('email_relay')->where('fk_user_id', '=', $userId)->value('id');

        if (!$address) {
            $address = $this->createRelayAddress($userId);
        }

        return $address . '@' . $this->config['host'];
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