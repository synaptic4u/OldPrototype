<?php

namespace Synaptic4U\Core;

use Exception;

class Template
{
    protected $folder;

    public function __construct($dir = null, $folder = null)
    {
        if (($folder) && ($dir)) {
            $this->folder = $dir.'/'.$folder;
        }
    }

    public function build($name, $array = [])
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = $this->get_template($name);

        $output = '';

        if ($template) {
            $output = $this->build_template($template, $array);
        }

        // $this->log([
        //     'Location' => __METHOD__.'() - DEBUG',
        //     'array' => json_encode($array, JSON_PRETTY_PRINT),
        //     'name' => $name,
        //     'output' => $output,
        // ]);

        return $output;
    }

    protected function get_template($name)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $file = $this->folder.'/'.$name.'.php';


        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'file' => $file,
        //     'file exists' => (file_exists($file)) ? 'Exists' : 'Doesnt exist',
        // ]);

        if (file_exists($file)) {
            return $file;
        }
    }

    protected function build_template($template, $array):string
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $contents = '';
        try {
            ob_start();

            foreach ($array as $key => $value) {
                ${$key} = $value;
            }

            include $template;

            $contents = ob_get_contents();

            ob_end_clean();
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Error' => $e->__toString(),
            ]);
        } finally {
            // $this->log([
            //     'Location' => __METHOD__.'() - DEBUG',
            //     'array' => json_encode($array, JSON_PRETTY_PRINT),
            //     'template' => $template,
            //     'contents' => $contents,
            // ]);

            return $this->string_replace($contents);
        }
    }
 
    public function string_replace($buffer){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $buffer = str_replace("\n","",str_replace("  "," ",$buffer));
        return $buffer;
    }

    protected function escape($variable)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        return htmlspecialchars($variable, ENT_QUOTES, 'UTF-8');
    }

    protected function error($msg)
    {
        new Log($msg, 'error');
    }

    protected function log($msg)
    {
        new Log($msg);
    }
}