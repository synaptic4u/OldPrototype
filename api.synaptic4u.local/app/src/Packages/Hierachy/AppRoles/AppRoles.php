<?php

namespace Synaptic4U\Packages\Hierachy\AppRoles;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\Hierachy\AppUsers\AppUsers;
use Synaptic4U\Packages\Hierachy\Hierachy\Hierachy;
use Synaptic4U\Packages\Hierachy\Roles\Roles;

class AppRoles
{
    public function show($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        $mod = new Model();

        $data = $mod->show($params);

        if (null === $data) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsShow($params);

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
        // ]);

        $view = new View($params, $data, $calls);

        return $view->show();
    }

    public function edit($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        $mod = new Model();

        $data = $mod->edit($params);

        if (null === $data) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsEdit($params);

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $view = new View($params, $data, $calls);

        return $view->edit();
    }

    public function showAppRoles($params, $roleid = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        $mod = new Model();

        $data = $mod->showAppRoles($params);

        if (null === $data) {
            return null;
        }

        $role = new Roles();

        $data['roles'] = $role->getRoles($params, $roleid, array_keys($data['mod_roles']), 0, 1);

        if (null === $data['roles']) {
            return null;
        }

        $calls = $mod->callsShowAppRoles($params);

        $view = new View($params, $data, $calls);

        return $view->showAppRoles();
    }

    public function editDetail($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        $mod = new Model();

        $data = $mod->editDetail($params);

        if (null === $data) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        if (null === $data['hierachyname']) {
            return null;
        }

        $data['mod_roles'] = $this->showAppRoles($params);

        $appuser = new AppUsers();

        $data['users'] = $appuser->show($params);

        if (null === $data['users']) {
            return null;
        }

        $calls = $mod->callsEditDetail($params);

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
        // ]);

        $view = new View($params, $data, $calls);

        return $view->editDetail();
    }

    public function storeAppRoles($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        $this->log([
            'Location' => __METHOD__.'(): 1',
            'params' => json_encode($params, JSON_PRETTY_PRINT),
        ]);

        $mod = new Model();

        $data = $mod->storeAppRoles($params);

        // $this->log([
        //     'Location' => __METHOD__.'(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        if ($data['return'] > 0) {
            return $this->showAppRoles($params, $data['roleid']);
        }

        $calls = $mod->callsCreated($params);

        if (null === $calls) {
            return null;
        }

        $role = new Roles();

        $data['role'] = $role->getRoleName($data['roleid']['id']);

        $view = new View($params, $data, $calls);

        return $view->created();
    }

    public function removeAppRoles($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $mod = new Model();

        $data = $mod->removeAppRoles($params);

        if (null === $data) {
            return null;
        }

        $role = new Roles();

        $data['role'] = $role->getRoleName($data['roleid']['id']);

        // $this->log([
        //     'Location' => __METHOD__.'(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $calls = $mod->callsAppRolesRemoved($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->removed();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
