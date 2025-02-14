<?php

namespace Synaptic4U\Packages\Hierachy\Applications;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\Hierachy\Hierachy\Hierachy;
use Synaptic4U\Packages\HtmlComponents\HtmlComponents;

class Applications
{
    public function listApps($params, $ids = '0', $id = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        //  Loads a select list of applications that can be subscribed to.
        $mod = new Model();

        $data = $mod->listApps($params, $ids, $id);

        // $this->log([
        //     'Location' => __METHOD__.'() : 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        //     'ids' => $ids,
        //     'id' => $id,
        // ]);

        if (0 === (int) $data['availablecount']) {
            return $data;
        }

        $select = new HtmlComponents();

        return $select->select($params, $data);
    }

    public function show($params, $appid = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        //  Loads a list of applications that are subscribed to.
        $data = null;
        $ids = '0';

        $mod = new Model();

        $data = $mod->show($params);

        // $this->log([
        //     'Location' => __METHOD__.'() : 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        if ((int) $data['count'] > 0) {
            $ids = implode(',', array_keys($data['subscribedApps']));

            // $this->log([
            //     'Location' => __METHOD__.'() : 2',
            //     'ids' => json_encode($ids, JSON_PRETTY_PRINT),
            // ]);
        }

        $data['selectApps'] = $this->listApps($params, $ids, $appid);

        if (null === $data['selectApps']) {
            return null;
        }

        $calls = $mod->callsShow($params);

        // $this->log([
        //     'Location' => __METHOD__.'() : DEBUG',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
        // ]);

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

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $mod = new Model();

        $data = $mod->store($params);

        // $this->log([
        //     'Location' => __METHOD__.'(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if ($data['return'] > 0) {
            return $this->show($params, $data['appid']);
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

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

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $mod = new Model();

        $data = $mod->remove($params);

        if (null === $data) {
            return null;
        }

        $hierachy = new Hierachy();

        $data['hierachyname'] = $hierachy->getHierachyName($params);

        // $this->log([
        //     'Location' => __METHOD__.'(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

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
