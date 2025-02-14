<?php

namespace Synaptic4U\Packages\Journal\ConfigSharing;

use Synaptic4U\Core\Log;

class ConfigSharing
{
    public function loadShareable($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->shareable($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsLoadShareable($params);

        if (null === $calls) {
            return null;
        }

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
            'data' => json_encode($data),
            'calls' => json_encode($calls),
        ]);

        if (isset($data['sharing']) && null !== (int) $data['sharing']) {
            $view = new View($params, $data, $calls);

            if (null === $view) {
                return null;
            }

            return $view->shareable();
        }

        return null;
    }

    public function shared($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->shared($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsShared($params);

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

        if (isset($data['sharing']) && (int) $data['sharing'] > 0) {
            $result = $view->shared();
        } else {
            $result = $view->noShares();
        }

        return $result;
    }

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

        return $view->updated();
    }

    public function request($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->request($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsRequest($params);

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

        return $view->requested();
    }

    public function following($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->following($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsFollowing($params);

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

        if (isset($data['following']) && (int) $data['following'] > 0) {
            $result = $view->following();
        } else {
            $result = $view->noFollowing();
        }

        return $result;
    }

    public function unfollow($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->unfollow($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsUnfollow($params);

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

        return $view->unfollowed();
    }

    public function followed($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->followed($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsFollowed($params);

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

        if (isset($data['followed']) && ((int) $data['followed']) > 0) {
            $result = $view->followed();
        } else {
            $result = $view->notFollowed();
        }

        return $result;
    }

    public function removeFollow($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->removeFollow($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsRemoveFollow($params);

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

        return $view->removedFollow();
    }
    
    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}