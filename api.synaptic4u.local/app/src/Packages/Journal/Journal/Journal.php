<?php

namespace Synaptic4U\Packages\Journal\Journal;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\Journal\ConfigJournal\Model as ConfigJournalModel;

class Journal
{
    // Returns the Journal create
    public function create($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new ConfigJournalModel();

        if (null === $mod) {
            return null;
        }

        $data = $mod->checklist($params);

        if (null === $data) {
            return null;
        }

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
            'data' => json_encode($data),
            '(isset($data["cnt"]) && (int)$data["cnt"] > 0)' => (isset($data['cnt']) && (int) $data['cnt'] > 0),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = (isset($data['cnt']) && (int) $data['cnt'] > 0) ?
            $mod->create($params) : $data;

        if (null === $data) {
            return null;
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

        if ((isset($data['cnt']) && 0 === (int) $data['cnt']) || (null === $data)) {
            return $view->empty();
        }

        return $view->create();
    }

    // Stores the new Journal to db
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

    //  Returns the Journal edit
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

    // Deletes the Journal entry
    public function delete($params)
    {
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}