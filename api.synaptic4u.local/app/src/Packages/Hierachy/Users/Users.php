<?php

namespace Synaptic4U\Packages\Hierachy\Users;

use Synaptic4U\Packages\Hierachy\Roles\Roles;
use Synaptic4U\Core\Log;
use Synaptic4U\Core\Security;
use Synaptic4U\Packages\Pagination\IPagination;

class Users implements IPagination
{
    public function list($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->list($params);

        if($data === null){
            return null;
        }

        $calls = $mod->callsList($params);

        $view = new View($params, $data, $calls);

        $data['list'] = $view->list();

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
        // ]);

        return $data;
    }

    public function page($params, $page = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->page($params, $page);

        if ($data === null) {
            return null;
        }

        $calls = $mod->callsPage($params);
        
        if ($calls === null) {
            return null;
        }

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
        // ]);

        $view = new View($params, $data, $calls);

        return $view->page();
    }

    public function show($params, $user = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'user' => json_encode($user, JSON_PRETTY_PRINT),
        // ]);

        $mod = new Model();

        $data = $mod->show($params);

        if ($data === null) {
            return null;
        }

        $calls = $mod->callsShow($params);

        if ($calls === null) {
            return null;
        }

        $list = $this->list($params);

        if ($list === null) {
            return null;
        }

        $data['list'] = $list['list'];
        
        $data['page'] = $this->page($params, array_shift($list['usersList']));

        $data['user'] = $user;

        if ($data === null) {
            return null;
        }

        $roles = new Roles();

        $data['roles'] = $roles->getRoles($params, $data['roleid']['value']);

        $view = new View($params, $data, $calls);

        return $view->show();
    }

    public function store($params)
    {        
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
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

        if(isset($data['inviteid'])){
            $security = new Security();

            $data['invited'] = $security->sendInvite($data['inviteid']);
        }

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
        $calls = null;

        $mod = new Model();

        if ($data === null) {
            $data = $mod->edit($params);
        }

        if ($data === null) {
            return null;
        }

        $calls = $mod->callsEdit($params);

        if ($calls === null) {
            return null;
        }

        $roles = new Roles();

        $data['roles'] = $roles->getRoles($params, $data['user']['roleid']['value']);

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

        // $this->log([
        //     'Location' => __METHOD__ . '(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if ($data === null) {
            return null;
        }

        if((int)$data['return'] > 0){
            return $this->edit($params, $data);
        }

        if(isset($data['inviteid'])){
            $security = new Security();

            $data['invited'] = $security->sendInvite($data['inviteid']);
        }

        $calls = $mod->callsUpdated($params);

        if ($calls === null) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->updated();
    }

    public function delete($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // $this->log([
        //     'Location' => __METHOD__ . '(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);
        
        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->delete($params);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if ($data === null) {
            return null;
        }

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