<?php

namespace Synaptic4U\Packages\Journal\ConfigJournal;

use Synaptic4U\Core\Log;

class ConfigJournal
{
    // Returns the JournalView create
    public function create($params)
    {
        
        $data = null;

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

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
            'calls' => json_encode($calls),
        ]);

        $view = new View($params, [], $calls);

        if (null === $view) {
            return null;
        }

        return $view->create();
    }

    // Stores the new Journal sections to db
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

    //	Updates the Journal to db
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

        $data['lastid'] = $mod->update($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsUpdate($params);

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

        return $view->updated();
    }

    //  Returns the ConfigJournal edit
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

        return $view->show();
    }

    public function loadlist($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->loadlist($params);

        if (null === $data) {
            return null;
        }

        if (isset($data['cnt']) && 0 === (int) $data['cnt']) {
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

        $calls = $mod->callsLoadList($params);

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

        return $view->loadlist();
    }
    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}