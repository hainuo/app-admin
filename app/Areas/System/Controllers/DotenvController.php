<?php
namespace App\Areas\System\Controllers;

use App\Areas\System\Models\DotenvLog;
use ManaPHP\Mvc\Controller;

class DotenvController extends Controller
{
    const REDIS_KEY = '.env';

    public function indexAction()
    {
        $app_id = input('app_id', '');

        if ($app_id === '') {
            return [];
        } else {
            $current = [['app_id' => $app_id, 'env' => $this->redis->hGet(self::REDIS_KEY, $app_id) ?: '']];
            $logs = DotenvLog::where(['app_id' => $app_id])->orderBy(['id' => SORT_DESC])->limit(10)->execute();

            return compact('current', 'logs');
        }
    }

    public function appsAction()
    {
        $apps = $this->redis->hKeys(self::REDIS_KEY);
        sort($apps);

        return $apps;
    }

    public function createAction()
    {
        $app_id = input('app_id');
        $env = input('env');

        if ($this->redis->hExists(self::REDIS_KEY, $app_id)) {
            return sprintf("${app_id}已存在");
        }

        $dotenvLog = new DotenvLog();

        $dotenvLog->app_id = $app_id;
        $dotenvLog->env = $env;

        $dotenvLog->create();

        $this->redis->hSet(self::REDIS_KEY, $app_id, $env);
    }

    public function editAction()
    {
        $app_id = input('app_id');
        $env = input('env');

        if (!$this->redis->hExists(self::REDIS_KEY, $app_id)) {
            return sprintf("${app_id}不存在");
        }

        if ($this->redis->hGet(self::REDIS_KEY, $app_id) === $env) {
            return 0;
        }

        $dotenvLog = new DotenvLog();

        $dotenvLog->app_id = $app_id;
        $dotenvLog->env = $env;

        $dotenvLog->create();

        $this->redis->hSet(self::REDIS_KEY, $app_id, $env);
    }

    public function deleteAction()
    {
        $this->redis->hDel(self::REDIS_KEY, input('app_id'));
    }
}
