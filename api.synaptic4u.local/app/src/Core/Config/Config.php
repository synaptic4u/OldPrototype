<?php

namespace Synaptic4U\Core;

class Config
{
    protected $filepath;

    protected $config;

    /**
     * Loads the config.json file.
     */
    public function __construct($filename = '/config.json')
    {
        $this->filepath = dirname(__FILE__, 4).$filename;

        $this->config = json_decode(file_get_contents($this->filepath), true);
    }

    public function getConfig()
    {
        return $this->config;
    }
}
