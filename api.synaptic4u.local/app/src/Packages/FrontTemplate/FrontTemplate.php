<?php

namespace Synaptic4U\Packages\FrontTemplate;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\Hierachy\Base\Hierachy;
use Synaptic4U\Packages\User\User;

class FrontTemplate
{
    // Returns the FrontTemplate
    public function show($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $calls = [];

        $data = [];

        $user = new User();

        // Returns user array & user calls array $data['user']['name', 'profile'] && $data['calls']['show','User1','logout','User2']
        $data = $user->getUser($params, 1);

        $mod = new Model($params);

        if (null === $mod) {
            return null;
        }

        $calls = $mod->callsSystemOverview($params);

        if (null === $calls) {
            return null;
        }

        $hierachy = new Hierachy();

        // User belongs to some form of heirachy, need to fetch the Org's name.
        if ((int) $params['whoami']['hierachy_count'] > 0) {
            $data['hierachy'] = $hierachy->getHierachy($params);
        }

        // $this->log([
        //     'Location' => __METHOD__.'(): End',
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->show();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
