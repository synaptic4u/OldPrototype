<?php

namespace Synaptic4U\Packages\HtmlComponents;

use Synaptic4U\Core\Log;

class HtmlComponents
{
    public function select($params, $data)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        //  Return a html select list.

        $calls = null;

        $view = new View($params, $data, $calls);

        return $view->select();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
