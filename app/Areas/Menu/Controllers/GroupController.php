<?php

namespace App\Areas\Menu\Controllers;

use App\Areas\Menu\Models\Group;
use ManaPHP\Mvc\Controller;

class GroupController extends Controller
{
    public function indexAction()
    {
        return Group::viewOrAll(['group_id'], ['order' => ['display_order' => SORT_DESC, 'group_id' => SORT_ASC]]);
    }

    public function listAction()
    {
        return Group::viewOrAll([], [], ['group_id', 'group_name']);
    }

    public function createAction()
    {
        return Group::viewOrCreate();
    }

    public function editAction()
    {
        return Group::viewOrUpdate();
    }

    public function deleteAction()
    {
        return Group::viewOrDelete();
    }
}