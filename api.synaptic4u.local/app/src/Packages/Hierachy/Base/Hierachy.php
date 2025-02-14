<?php

namespace Synaptic4U\Packages\Hierachy\Base;

use Synaptic4U\Core\Log;

class Hierachy
{
    /** Gets organisation name.
     *
     * @param mixed $params
     */
    public function getHierachy($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $mod = new Model();

        return $mod->getHierachy($params);
    }

    /** Checks status if user belongs to a organisation.
     *
     * @param mixed $params
     */
    public function checkStatus($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $mod = new Model();

        return $mod->checkStatus($params);
    }

    /** Loads the organizational build structure under System Settings.
     *
     * @param mixed $params
     */
    public function load($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->checkStatus($params);

        if (isset($data['count']) && $data['count'] > 0) {
            $calls = $mod->callsExists($params);
        } else {
            $calls = $mod->callsBlank($params);
        }

        $view = new View($params, $data, $calls);

        return $view->load($params);
    }

    /**
     * Loads the organizational build structure from DB.
     *
     * @param mixed $params
     */
    public function show($params)
    {
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->show($params);

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

    /**
     * Loads the organization detail from DB : everything!.
     *
     * @param mixed $params
     */
    public function detail($params)
    {
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->detail($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsDetail($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->detail();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
