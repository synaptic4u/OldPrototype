<?php

namespace Synaptic4U\Packages\Journal\Checklist;

use Synaptic4U\Core\Log;

class Checklist
{
    // Returns the Checklist create
    public function create($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $calls = $mod->callsCreate($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, [], $calls);

        if (null === $view) {
            return null;
        }

        return $view->create();
    }

    // Stores the new Checklist to db
    public function store($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->store($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsStore($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->created();
    }

    public function show($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->exists($params);

        if (null === $data) {
            return null;
        }

        if ((int) $data > 0) {
            $data = $mod->show($params);

            if (null === $data) {
                return null;
            }

            $calls = $mod->callsShow($params);

            if (null === $calls) {
                return null;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params),
                'data' => json_encode($data),
                'calls' => json_encode($calls),
            ]);

            $view = new View($params, $data, $calls);

            if (null === $view) {
                return null;
            }

            return $view->show();
        }

        $calls = $mod->callsCreate($params);

        if (null === $calls) {
            return null;
        }

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
            'data' => json_encode($data),
            'calls' => json_encode($calls),
        ]);
        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->create();
    }

    //	Updates the Checklist to db
    public function update($params)
    {
                $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->update($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsUpdate($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->updated();
    }

    //  Returns the Checklist edit
    public function edit($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->show($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsEdit($params);

        if (null === $calls) {
            return null;
        }

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
            'data' => json_encode($data),
            'calls' => json_encode($calls),
        ]);

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->edit();
    }

    // Deletes the Checklist entry
    public function delete($params)
    {
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}