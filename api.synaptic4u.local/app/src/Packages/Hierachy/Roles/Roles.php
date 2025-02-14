<?php

namespace Synaptic4U\Packages\Hierachy\Roles;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\Hierachy\Hierachy\Hierachy;

class Roles
{
    public function show($params, $data = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Loads the organizational Roles to the accordion - EDITABLE.
        $role = (isset($data['role'])) ? $data['role'] : null;
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->show($params);
        $data['role'] = $role;

        if ($data === null) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsShow($params);

        if ($calls === null) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->show();
    }

    public function store($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Stores the organizational custom Types to the DB.
        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->store($params);

        if ($data === null) {
            return null;
        }

        if((int)$data['return'] > 0){
            return $this->show($params, $data);
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsStored($params);

        if ($calls === null) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->stored();
    }

    public function edit($params, $data = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Stores the organizational custom Types to the DB.
        $calls = null;

        $mod = new Model();
        if ($data === null) {
            $data = $mod->edit($params);
        }

        if ($data === null) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsEdit($params);

        if ($calls === null) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->edit();
    }

    public function update($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->update($params);

        if ($data === null) {
            return null;
        }

        if((int)$data['return'] > 0){
            return $this->edit($params, $data);
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsUpdated($params);

        if ($calls === null) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->updated();
    }

    public function toggle($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Stores the organizational custom Types to the DB.
        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->toggle($params);

        if ($data === null) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsToggle($params);

        if ($calls === null) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->toggle();
    }

    public function getRoleName($roleid){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  

        $data = null;

        $mod = new Model();

        $data = $mod->getRoleName($roleid);

        if ($data === null) {
            return null;
        }

        return $data;
    }

    public function getRoles($params, $roleid = null, $roleids = null, $exclude = 0, $select = 0, $list = 0)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->getRoles($params, $roleid, $roleids, $exclude);

        if ($data === null) {
            return null;
        }

        if((int)$list === 1){
            return $data;
        }

        $view = new View($params, $data, $calls);

        return $view->getRoles($select, $roleid);
    }

    public function delete($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->delete($params);

        if ($data === null) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsDeleted($params);

        if ($calls === null) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->deleted();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }

}