<?php

namespace Synaptic4U\Core;

class Config
{
    protected $doc_dir;
    protected $file;
    protected $filepath;

    protected $config;

    /**
     * Loads the config.json file.
     */
    public function __construct()
    {
        $this->doc_dir = dirname(__FILE__, 4);

        $this->file = '/config.json';

        $this->filepath = $this->doc_dir.$this->file;

        $this->config = json_decode(file_get_contents($this->filepath), true);
    }

    public function getConfig()
    {
        return $this->config;
    }
}
