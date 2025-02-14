<?php

namespace Synaptic4U\Packages\Journal\ConfigNotifications;

use Synaptic4U\Core\Log;

class ConfigNotifications
{
    public function loadRequests($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->loadRequests($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsLoadRequests($params);

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

        if (isset($data['requests']) && ((int) $data['requests']) > 0) {
            $result = $view->loadRequests();
        } else {
            $result = $view->noRequests();
        }

        return $result;
    }

    public function acceptRequest($params)
    {
        

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->acceptRequest($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsAcceptRequests($params);

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

        return $view->acceptedRequest();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
    
}