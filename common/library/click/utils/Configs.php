<?php

//  ██████╗██╗     ██╗ ██████╗██╗   ██╗    ██╗    ██╗██████████╗
// ██╔════╝██║     ██║██╔════╝██║ ██╔═╝    ██║    ██║      ██╔═╝
// ██║     ██║     ██║██║     ████╔═╝      ██║    ██║    ██╔═╝
// ██║     ██║     ██║██║     ██║ ██╗      ██║    ██║  ██══╝
// ╚██████╗███████╗██║╚██████╗██║   ██╗ ██╗█████████║██████████╗
//  ╚═════╝╚══════╝╚═╝ ╚═════╝╚═╝   ╚═╝ ╚═╝╚════════╝╚═════════╝

namespace common\library\click\utils;

/**
 * @name Configs class
 */
class Configs{

    /** @var configs array-like */
    private $configs;

    /**
     * Configs constructor
     */
    public function __construct(){
        $path_to_configs = __DIR__ . '//..//config.php';
        $this->configs = require($path_to_configs);
    }

    public function get_provider_configs(){
        return $this->configs['provider'];
    }

}