<?php

namespace Synaptic4U\Packages\Hierachy\Hierachy;

use Synaptic4U\Core\Log;

class Hierachy
{
    /**
     * @param mixed $params
     */
    public function getHierachyName($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        $mod = new Model();

        return $mod->getHierachyName($params);
    }

    /**
     * @param mixed $params
     */
    public function create($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // Loads the organizational hierachy_form to the sidebar.

        $data = [
            'hierachyname' => [
                'pass' => null,
                'message' => null,
                'value' => null,
            ],
            'hierachydescription' => [
                'pass' => null,
                'message' => null,
                'value' => null, ],
        ];
        $calls = null;

        $mod = new Model();

        $calls = $mod->callsCreate($params);

        $data['select'] = $this->getHierachyType($params);

        $view = new View($params, $data, $calls);

        return $view->create();
    }

    /**
     * @param mixed $params
     */
    public function store($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // Stores to DB the organizational hierachy_form from the sidebar.
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->store($params);

        if ($data['return'] > 0) {
            $calls = $mod->callsCreate($params);

            if (null === $calls) {
                return null;
            }

            $data['select'] = $this->getHierachyType($params, $data['hierachytypeid']['value']);

            $view = new View($params, $data, $calls);

            return $view->create();
        }

        $calls = $mod->callsCreated($params);

        $view = new View($params, $data, $calls);

        return $view->created();
    }

    /**
     * @param mixed $params
     */
    public function show($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // Loads the organizational hierachy_form to the accordion - NOT EDITABLE.

        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->show($params);

        $calls = $mod->callsShow($params);

        $view = new View($params, $data, $calls);

        // $this->log([
        //     'Location' => __METHOD__.'() - DEBUG',
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
        //     'view' => json_encode($view, JSON_PRETTY_PRINT),
        // ]);

        return $view->show();
    }

    /**
     * @param mixed      $params
     * @param null|mixed $typeid
     */
    public function getHierachyType($params, $typeid = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        $mod = new Model($params);
        $data['select'] = $mod->getHierachyType($params, $typeid);

        $view = new View($params, $data, []);

        return $view->getHierachyType();
    }

    /**
     * @param mixed $params
     */
    public function edit($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->edit($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsEdit($params);

        if (null === $calls) {
            return null;
        }

        $data['selectHierachyType'] = $this->getHierachyType($params, $data['id']);

        if (null === $data['selectHierachyType']) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->edit();
    }

    /**
     * @param mixed $params
     */
    public function update($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // Updates to DB the organizational hierachy_form from the accordion.

        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->update($params);

        if ($data['return'] > 0) {
            $calls = $mod->callsEdit($params);

            $data['selectHierachyType'] = $this->getHierachyType($params, $data['hierachytypeid']['value']);

            $view = new View($params, $data, $calls);

            return $view->edit();
        }

        $calls = $mod->callsUpdate($params);

        $view = new View($params, $data, $calls);

        return $view->updated();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
