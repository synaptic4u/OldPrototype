<?php

namespace Synaptic4U\Packages\Hierachy\Types;

use Synaptic4U\Core\Log;

class Types
{
    public function show($params, $data = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // Loads the organizational Types to the accordion - EDITABLE.
        // $this->log([
        //     'Location' => __METHOD__ . '(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $type = (isset($data['type'])) ? $data['type'] : null;
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->show($params);
        $data['type'] = $type;

        // $this->log([
        //     'Location' => __METHOD__ . '(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsShow($params);

        if (null === $calls) {
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
        // $this->log([
        //     'Location' => __METHOD__ . '(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->store($params);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        if ((int) $data['return'] > 0) {
            return $this->show($params, $data);
        }

        $calls = $mod->callsStored($params);

        if (null === $calls) {
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
        // $this->log([
        //     'Location' => __METHOD__ . '(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $calls = null;

        $mod = new Model();
        if (null === $data) {
            $data = $mod->edit($params);
        }

        // $this->log([
        //     'Location' => __METHOD__ . '(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsEdit($params);

        if (null === $calls) {
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
        // $this->log([
        //     'Location' => __METHOD__ . '(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->update($params);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        if ((int) $data['return'] > 0) {
            return $this->edit($params, $data);
        }

        $calls = $mod->callsUpdated($params);

        if (null === $calls) {
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
        // $this->log([
        //     'Location' => __METHOD__ . '(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->toggle($params);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsToggle($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->toggle();
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

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsDeleted($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->deleted();
    }

    public function getTypes($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // Fetches hierachy types into a select
        // $this->log([
        //     'Location' => __METHOD__ . '(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $calls = null;
        $data = null;

        $mod = new Model();

        $data = $mod->getTypes($params);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->getTypes();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
