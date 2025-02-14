<?php

namespace Synaptic4U\Packages\Applications;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\HtmlComponents\HtmlComponents;

class Applications
{
    public function listApps($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        //  Loads a list of applications that can be subscribed to.

        $mod = new Model();

        $data = $mod->listApps($params);

        $select = new HtmlComponents();

        return $select->select($params, $data);
    }

    public function load($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $data = [
            'personnel_applications' => null,
            'orgnization_applications' => null,
        ];
        $calls = null;

        if ((int) $params['whoami']['app_count'] > 0) {
            $data['personnel_applications'] = $this->loadPersonnelApps($params);
        }

        if ((int) $params['whoami']['hierachy_count'] > 0) {
            $data['orgnization_applications'] = $this->loadOrgApps($params);
        }

        $view = new View($params, $data, $calls);

        return $view->load();
    }

    public function loadPersonnelApps($params)
    {
        $data = null;

        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        //  Fetches all the applications that the user is subscribed to.

        $mod = new Model();

        return $mod->loadPersonnelApps($params);
        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);
    }

    public function loadOrgApps($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        //  Fetches all the applications that the user's organization is subscribed to.

        $mod = new Model();

        return $mod->loadOrgApps($params);
        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
