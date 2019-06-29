<?php
namespace App\Models;

use ManaPHP\Db\Model;

/**
 * Class App\Models\AdminLoginLog
 */
class AdminLoginLog extends Model
{
    public $login_id;
    public $admin_id;
    public $admin_name;
    public $client_ip;
    public $client_udid;
    public $user_agent;
    public $created_time;

    /**
     * @param mixed $context
     *
     * @return string
     */
    public function getSource($context = null)
    {
        return 'admin_login_log';
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return 'login_id';
    }
}