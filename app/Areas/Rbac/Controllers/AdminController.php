<?php

namespace App\Areas\Rbac\Controllers;

use App\Models\Admin;
use ManaPHP\Mvc\Controller;

class AdminController extends Controller
{
    public function getAcl()
    {
        return ['list' => '@index', 'lock' => '@edit', 'active' => '@edit', 'create' => '@edit'];
    }

    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $builder = Admin::select(['admin_id', 'admin_name', 'status', 'login_ip', 'login_time', 'email', 'updator_name', 'creator_name', 'created_time', 'updated_time'])
                ->orderBy('admin_id DESC');

            $keyword = input('keyword', '');
            if (strpos($keyword, '@') !== false) {
                $builder->whereContains('email', $keyword);
            } else {
                $builder->whereContains(['admin_name', 'email'], $keyword);
            }

            return $builder->paginate();
        }
    }

    public function listAction()
    {
        return Admin::lists(['admin_id' => 'admin_name']);
    }

    public function lockAction()
    {
        if ($this->identity->getId() == input('admin_id')) {
            return '���������Լ�';
        }

        return Admin::viewOrUpdate(['status' => Admin::STATUS_LOCKED]);
    }

    public function activeAction()
    {
        return Admin::viewOrUpdate(['status' => Admin::STATUS_ACTIVE]);
    }

    public function createAction()
    {
        return Admin::viewOrCreate();
    }

    public function editAction()
    {
        return Admin::viewOrUpdate();
    }
}