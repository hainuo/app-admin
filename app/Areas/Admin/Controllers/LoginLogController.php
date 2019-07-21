<?php
namespace App\Areas\Admin\Controllers;

use App\Models\AdminLoginLog;
use ManaPHP\Mvc\Controller;

class LoginLogController extends Controller
{
    public function getAcl()
    {
        return ['latest' => 'user'];
    }

    public function indexAction()
    {
        return $this->request->isAjax()
            ? AdminLoginLog::select(['login_id', 'admin_id', 'admin_name', 'client_udid', 'user_agent', 'client_ip', 'created_time'])
                ->orderBy('login_id DESC')
                ->search(['admin_id', 'admin_name*=', 'client_ip', 'client_udid', 'created_time@='])
                ->paginate()
            : null;
    }

    public function latestAction()
    {
        return $this->request->isAjax()
            ? AdminLoginLog::select(['login_id', 'client_udid', 'user_agent', 'client_ip', 'created_time'])
                ->orderBy('login_id DESC')
                ->where('admin_id', $this->identity->getId())
                ->paginate()
            : null;
    }
}