<?php

namespace Synaptic4U\Packages\Hierachy\AppUsers;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\User\User;

class AppUsers
{
    public function getUsers($params, $userids = [])
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->getUsers($params, $userids);

        if (null === $data) {
            return null;
        }

        // $calls = $mod->callsShowIncluded($params);

        $view = new View($params, $data, $calls);

        return $view->getUsers();
    }

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

        $userids = (is_array($data['users']) && sizeof($data['users']) > 0) ? array_keys($data['users']) : [];

        $data['user_select'] = $this->getUsers($params, $userids);

        if (null === $data['user_select']) {
            return null;
        }

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        //     'userids' => json_encode($userids, JSON_PRETTY_PRINT),
        // ]);

        $calls = $mod->callsShow($params);

        $view = new View($params, $data, $calls);

        return $view->show();
    }

    public function store($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->store($params);

        // $this->log([
        //     'Location' => __METHOD__.'(): 0',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if ($data['return'] > 0) {
            return $this->show($params, $data['appid']);
        }

        $user = new User();

        $data['user'] = $user->getUser($params, 0, $data['userid']['id']);

        $calls = $mod->callsCreated($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->created();
    }

    public function remove($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->remove($params);

        // $this->log([
        //     'Location' => __METHOD__.'(): 0',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $user = new User();

        $data['user'] = $user->getUser($params, 0, $data['userid']['id']);

        $calls = $mod->callsRemoved($params);

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
