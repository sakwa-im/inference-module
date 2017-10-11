<?php

class Bootstrap {

    public function __construct()
    {
        $this->initBootstrap();
        $this->initCwd();
        $this->bootstrapAutoloader();

        //Add additional bootstrap functionality here
    }

    protected function initBootstrap()
    {
        //Code for initialising the bootstrap functionality should be written here.
    }

    protected function initCwd()
    {
        chdir(realpath(dirname(__DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..')));
    }

    protected function bootstrapAutoloader()
    {
        require_once realpath(dirname(__DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..')).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
    }
}

$bootstrap = new Bootstrap();