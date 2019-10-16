<?php

namespace App\Controllers;

use ManaPHP\Version;
use ManaPHP\Mvc\Controller;

class SystemInformationController extends Controller
{
    public function indexAction()
    {
        $globals = $this->request->getGlobals();

        $data = [];

        $data['framework_version'] = Version::get();
        $data['php_version'] = PHP_VERSION;
        $data['sapi'] = PHP_SAPI;


        if (function_exists('apache_get_version')) {
            $data['apache_version'] = apache_get_version();
        }

        /** @noinspection ConstantCanBeUsedInspection */
        $data['operating_system'] = php_uname();
        $data['system_time'] = date('Y-m-d H:i:s');
        $data['loaded_ini'] = '';
        $data['server_ip'] = $globals->_SERVER['SERVER_ADDR'];
        $data['client_ip'] = $globals->_SERVER['REMOTE_ADDR'];
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        $data['post_max_size'] = ini_get('post_max_size');
        $data['loaded_ini'] = php_ini_loaded_file();
        $loaded_extensions = get_loaded_extensions();
        sort($loaded_extensions);
        $data['loaded_extensions'] = implode(', ', $loaded_extensions);
        $data['loaded_classes'] = get_declared_classes();
        $this->view->setVar('data', $data);
    }
}