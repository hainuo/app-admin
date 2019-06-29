<?php

namespace App\Areas\Rbac\Controllers;

use App\Areas\Rbac\Models\AdminRole;
use App\Areas\Rbac\Models\Role;
use ManaPHP\Mvc\Controller;

class RoleController extends Controller
{
    public function getAcl()
    {
        return ['list' => '@index', 'enable' => '@edit', 'disable' => '@edit', 'delete' => '@edit', 'create' => '@edit'];
    }

    public function indexAction()
    {
        return $this->request->isAjax()
            ? Role::select()
                ->whereContains('role_name', input('keyword', ''))
                ->orderBy('role_id desc')
                ->paginate()
            : null;
    }

    public function listAction()
    {
        return Role::lists(['display_name', 'role_name']);
    }

    public function createAction()
    {
        if ($role_name = input('role_name', '')) {
            $permissions = ',' . implode(',', $this->authorization->buildAllowed($role_name)) . ',';
        } else {
            $permissions = '';
        }

        return Role::viewOrCreate(['permissions' => $permissions]);
    }

    public function editAction()
    {
        return Role::viewOrUpdate();
    }

    public function disableAction()
    {
        return Role::viewOrUpdate(['enabled' => 0]);
    }

    public function enableAction()
    {
        return Role::viewOrUpdate(['enabled' => 1]);
    }

    public function deleteAction()
    {
        if (!$this->request->isGet()) {
            $role = Role::get(input('role_id'));

            if (AdminRole::exists(['role_id' => $role->role_id])) {
                return 'ɾ��ʧ��: ���û��󶨵��˽�ɫ';
            }

            return $role->delete();
        }
    }
}