<?php

namespace Synaptic4U\Core;

class Validate
{
    protected $formArray;

    protected $structure;

    protected $data;

    protected $filepath;

    public function __construct($filepath)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $this->formArray = [];
        $this->structure = [];
        $this->data = [];
        $this->filepath = $filepath;
    }

    public function isValid($file, $formArray)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     '$formArray' => json_encode($formArray, JSON_PRETTY_PRINT),
        //     '$formArray length' => sizeof($formArray),
        // ]);

        $this->formArray = $formArray;

        $this->filepath .= $file.'.json';

        // $this->log([
        //     'Location' => __METHOD__.'(): 2',
        //     '$this->formArray' => json_encode($this->formArray, JSON_PRETTY_PRINT),
        //     '$this->formArray length' => sizeof($this->formArray),
        //     'filepath' => $this->filepath,
        //     'filepath exists' => (file_exists($this->filepath)) ? 'Yes' : 'No',
        // ]);

        $this->structure = json_decode(file_get_contents($this->filepath), true);

        // $this->log([
        //     'Location' => __METHOD__.'(): 3',
        //     'structure' => json_encode($this->structure, JSON_PRETTY_PRINT),
        // ]);

        $this->check();

        $this->log([
            'Location' => __METHOD__.'(): 4',
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
        ]);

        return $this->data;
    }

    protected function summary()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        // $this->log([
        //     'Location' => __METHOD__.'(): 0',
        //     'data' => json_encode($this->data, JSON_PRETTY_PRINT),
        // ]);

        $this->data['return'] = 0;

        foreach ($this->data as $key => $variable) {
            if(is_array($variable)){
                if ($variable['required']) {
                    if (0 === $variable['pass']) {
                        $this->data['return'] += 0;

                        $this->data[$key]['message'] = 'is-valid';
                    } else {
                        ++$this->data['return'];

                        $this->data[$key]['message'] = 'is-invalid';
                    }
                } else {
                    $this->data['return'] += 0;

                    $this->data[$key]['message'] = 'is-valid';
                }
            }
        }

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'data' => json_encode($this->data, JSON_PRETTY_PRINT),
        // ]);
    }

    protected function str_clean($str)
    {
        return filter_var($str, FILTER_SANITIZE_STRING);
    }

    protected function int_clean($int)
    {
        return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
    }

    protected function arr_clean($array)
    {
        return filter_var($array, FILTER_SANITIZE_NUMBER_INT);
    }

    protected function email_clean($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    protected function str_validate($str)
    {
        return (is_string($str)) ? $str : null;
    }

    protected function int_validate($int)
    {
        return filter_var($int, FILTER_VALIDATE_INT);
    }

    protected function arr_validate($array)
    {
        return (is_array($array)) ? $array : null;
    }

    protected function email_validate($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    protected function space_trim($str)
    {
        return trim($str);
    }

    protected function check()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $this->data['length'] = sizeof($this->structure) - sizeof($this->formArray);

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'sizeof($this->structure) - sizeof($this->formArray)' => sizeof($this->structure) - sizeof($this->formArray),
        //     'length' => $this->data['length'],
        // ]);

        foreach ($this->structure as $key => $value) {
            switch (true) {
                case is_string($this->formArray[$key]):
                    $this->formArray[$key] = $this->space_trim($this->formArray[$key]);

                    $this->data[$key]['validate']['isstring'] = ($this->str_clean($this->formArray[$key])) ? 'pass' : 'fail';

                    $this->data[$key]['validate']['length'] = (strlen($this->formArray[$key]) >= $value['length']) ? 'pass' : 'fail';
                    foreach ($this->data[$key]['validate'] as $variable) {
                        $this->data[$key]['pass'] += ('fail' === $variable) ? 1 : 0;
                    }
                    $this->data[$key]['value'] = $this->str_clean($this->str_validate($this->formArray[$key]));

                    if ('email' === $value['type']) {
                        $this->data[$key]['value'] = $this->email_clean($this->email_validate($this->formArray[$key]));

                        $this->data[$key]['validate']['isemail'] = (filter_var($this->formArray[$key], FILTER_VALIDATE_EMAIL)) ? 'pass' : 'fail';
                        $this->data[$key]['validate']['@'] = (1 === substr_count($this->formArray[$key], '@')) ? 'pass' : 'fail';
                        $this->data[$key]['validate']['.'] = (substr_count($this->formArray[$key], '.', strrpos($this->formArray[$key], '@'), strlen($this->formArray[$key]) - strrpos($this->formArray[$key], '@')) > 0) ? 'pass' : 'fail';
                        foreach ($this->data[$key]['validate'] as $variable) {
                            $this->data[$key]['pass'] += ('fail' === $variable) ? 1 : 0;
                        }
                    }
                    if ('password' === $value['type']) {
                        $this->data[$key]['validate']['>'] = (substr_count('>', $this->formArray[$key]) > 0) ? 'fail' : 'pass';
                        $this->data[$key]['validate']['<'] = (substr_count('<', $this->formArray[$key]) > 0) ? 'fail' : 'pass';
                        $this->data[$key]['validate'][';'] = (substr_count(';', $this->formArray[$key]) > 0) ? 'fail' : 'pass';
                        foreach ($this->data[$key]['validate'] as $variable) {
                            $this->data[$key]['pass'] += ('fail' === $variable) ? 1 : 0;
                        }
                    }
                    if ('contactnu' === $value['type']) {
                        $this->formArray[$key] = str_replace(' ', '', str_replace('(', '', str_replace(')', '', $this->formArray[$key])));

                        $this->data[$key]['validate']['length'] = ((strlen($this->formArray[$key]) + substr_count('+', $this->formArray[$key])) >= $value['length']) ? 'pass' : 'fail';
                        $this->data[$key]['value'] = $this->formArray[$key];

                        foreach ($this->data[$key]['validate'] as $variable) {
                            $this->data[$key]['pass'] += ('fail' === $variable) ? 1 : 0;
                        }
                    }
                    if ('string' === $value['type']) {
                        $this->data[$key]['validate']['isstring'] = ($this->str_clean($this->formArray[$key])) ? 'pass' : 'fail';

                        $this->data[$key]['validate']['length'] = (strlen($this->formArray[$key]) >= $value['length']) ? 'pass' : 'fail';
                        $this->data[$key]['value'] = $this->formArray[$key];

                        foreach ($this->data[$key]['validate'] as $variable) {
                            $this->data[$key]['pass'] += ('fail' === $variable) ? 1 : 0;
                        }

                        $this->data[$key]['value'] = $this->str_clean($this->formArray[$key]);
                    }

                    break;

                case is_int($this->formArray[$key]):
                    $this->data[$key]['value'] = $this->int_clean($this->int_validate($this->formArray[$key]));

                    if (isset($value['min'])) {
                        $this->data[$key]['validate']['value'] = ($this->formArray[$key] >= $value['min']) ? 'pass' : 'fail';
                    } else {
                        $this->data[$key]['validate']['value'] = ($this->formArray[$key] === $value['value']) ? 'pass' : 'fail';
                    }

                    foreach ($this->data[$key]['validate'] as $variable) {
                        $this->data[$key]['pass'] += ('fail' === $variable) ? 1 : 0;
                    }

                    break;
                
                case is_array($this->formArray[$key]):
                    $this->data[$key]['validate']['acceptedtype'] = (in_array($this->formArray[$key]['type'], $value['properties']['accepted'])) ? 'pass' : 'fail';
                    
                    $this->log([
                        'Location' => __METHOD__.'(): 1.5',
                        'accepted types' => (in_array($this->formArray[$key]['type'], $value['properties']['accepted'])) ? 'pass' : 'fail',
                        
                    ]);
                    $this->data[$key]['validate']['size'] = ((int)$value['properties']['sizelimit'] >= (int)$this->formArray[$key]['size']) ? 'pass' : 'fail';

                    if((string)$value['type'] === 'image'){
                        $this->data[$key]['validate']['width'] = ((int)$value['properties']['width'] < (int)$this->formArray[$key]['width']) ? 'pass' : 'fail';
                        
                        $this->data[$key]['validate']['height'] = ((int)$value['properties']['height'] < (int)$this->formArray[$key]['height']) ? 'pass' : 'fail';
                        
                        $this->data[$key]['validate']['content'] = ((int)$value['properties']['content'] < strlen($this->formArray[$key]['content'])) ? 'pass' : 'fail';
                    }

                    $this->data[$key]['validate']['name'] = ($this->str_validate($this->formArray[$key]['name']) && $value['properties']['name'] <= strlen($this->formArray[$key]['name'])) ? 'pass' : 'fail';

                    foreach ($this->data[$key]['validate'] as $variable) {
                        $this->data[$key]['pass'] += ('fail' === $variable) ? 1 : 0;
                    }
                    
                    foreach($this->formArray[$key] as $key2 => $value2){
                        if(is_int($value2)){
                            $this->data[$key]['value'][$key2] = $this->int_clean($this->int_validate($value2));
                        }
                        if(is_string($value2)){
                            $this->data[$key]['value'][$key2] = $this->str_clean($this->str_validate($value2));
                        }
                    }

                    break;
            }
            if (!$value['required']) {
                $this->data[$key]['pass'] = 0;
            }

            $this->data[$key]['required'] = ($value['required']) ? 'true' : 'false';
        }

        $this->summary();

        // $this->log([
        //     'Location' => __METHOD__.'() - FINAL',
        //     'structure' => json_encode($this->structure),
        //     'formArray' => json_encode($this->formArray),
        //     'data' => json_encode($this->data),
        // ]);
    }

    protected function error($msg)
    {
        new Log($msg, 'error', 3);
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}