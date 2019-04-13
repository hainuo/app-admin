<?php

namespace App\Areas\Rbac\Controllers;

use App\Areas\Rbac\Models\AdminRole;
use App\Areas\Rbac\Models\Role;
use App\Models\Admin;
use ManaPHP\Mvc\Controller;

class AdminRoleController extends Controller
{
    public function indexAction()
    {
        return $this->request->isAjax()
            ? AdminRole::all(['admin_id' => input('admin_id')],
                ['with' => ['role', 'admins' => 'admin_id, admin_name']],
                ['id', 'admin_id', 'role_id', 'creator_name', 'created_time'])
            : null;
    }

    public function detailAction()
    {
        return $this->request->isAjax() ? AdminRole::all(['admin_id' => input('admin_id')]) : null;
    }

    public function editAction()
    {
        if ($this->request->isPost()) {
            $new_roles = input('role_ids');
            $admin = Admin::get(input('admin_id'));

            $old_roles = AdminRole::values('role_id', ['admin_id' => $admin->admin_id]);
            AdminRole::deleteAll(['role_id' => array_values(array_diff($old_roles, $new_roles))]);

            foreach (array_diff($new_roles, $old_roles) as $role_id) {
                $adminRole = new AdminRole();

                $adminRole->admin_id = $admin->admin_id;
                $adminRole->admin_name = $admin->admin_name;
                $adminRole->role_id = $role_id;
                $adminRole->role_name = Role::value($role_id, 'role_name');

                $adminRole->create();
            }

            return 0;
        }
    }
}