<?php

namespace Synaptic4U\Packages\Journal\Dashboard;

use Synaptic4U\Core\Log;

class Dashboard
{
    public function load($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $calls = $mod->callsLoad($params);

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

        return $view->load();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}