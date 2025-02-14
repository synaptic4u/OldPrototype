<?php

namespace Synaptic4U\Packages\Hierachy\Particulars;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\Hierachy\Hierachy\Hierachy;

class Particulars
{
    public function show($params){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->show($params);

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);
        
        if($data === null){
            return null;
        }

        if((int)$data['id'] === 0){
            return $this->create($params, $data);
        }

        $calls = $mod->callsShow($params);

        $view = new View($params, $data, $calls);

        return $view->show();
    }

    protected function getPartUsers($params, $id = null){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Loads the organizational particulars users.
        
        $data = null;
        
        $calls = null;

        $mod = new Model();

        $data = $mod->getPartUsers($params, $id);

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $calls = [];

        $view = new View($params, $data, $calls);

        return $view->getPartUsers();
    }
    
    public function create($params, $data = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Loads the organizational particulars_form.
        
        // $data = null;
        
        $calls = null;

        $mod = new Model();

        if($data === null){
            $data = $mod->show($params);
        }

        if($data === null){
            return null;
        }

        $data['selectUser'] = $this->getPartUsers($params, $data['contactuserid']['value']);

        if($data['selectUser'] === null){
            return null;
        }

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $calls = $mod->callsCreate($params);
                
        if($calls === null){
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->create();
    }

    public function store($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Stores to DB the organizational particularss.
        
        $data = null;
        $calls = null;
        
        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        $mod = new Model();

        $data = $mod->store($params);

        // $this->log([
        //     'Location' => __METHOD__.'(): 2',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if ($data['return'] > 0) {
            return $this->create($params, $data);
        }

        $hierachy = new Hierachy();

        $data['hierachyname']['value'] = $hierachy->getHierachyName($params);

        $calls = $mod->callsCreated($params);

        if($calls === null){
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->created();
    }

    public function edit($params){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Loads the organizational hierachy_form to the accordion - EDITABLE.
        
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->edit($params);

        if($data === null){
            return null;
        }

        $data['selectUser'] = $this->getPartUsers($params, $data['contactuserid']['id']);

        if($data['selectUser'] === null){
            return null;
        }

        $calls = $mod->callsEdit($params);

        if($calls === null){
            return null;
        }
        
        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $view = new View($params, $data, $calls);

        return $view->edit();
    }

    public function update($params){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        // Updates to DB the organizational hierachy_form from the accordion.
        
        $data = null;
        $calls = null;

        $mod = new Model();

        $data = $mod->update($params);

        if($data === null){
            return null;
        }

        if ($data['return'] > 0) {

            $calls = $mod->callsEdit($params);

            if($calls === null){
                return null;
            }

            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            $view = new View($params, $data, $calls);

            return $view->edit();
        }

        $hierachy = new Hierachy();

        $data['hierachyname']['value'] = $hierachy->getHierachyName($params);
        
        $calls = $mod->callsUpdate($params);

        if($calls === null){
            return null;
        }

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $view = new View($params, $data, $calls);

        return $view->updated();
    }

    public function delete($params){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }

}