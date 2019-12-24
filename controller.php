<?php

namespace scoreboard;

use scoreboard\controller\Bowstyle_Controller_Class;
use scoreboard\controller\Config_Controller_Class;
use scoreboard\controller\Gamedraw_Controller_Class;
use scoreboard\controller\Gamemode_Controller_Class;
use scoreboard\controller\Gameset_Controller_Class;
use scoreboard\controller\Gamestatus_Controller_Class;
use scoreboard\controller\Live_Game_Controller_Class;
use scoreboard\controller\Player_Controller_Class;
use scoreboard\controller\Scoreboard_Style_Controller_Class;
use scoreboard\controller\Score_Controller_Class;
use scoreboard\controller\Team_Controller_Class;

class Controller {

    /**
     * Controller Construct
     */
    public function __construct() {
        spl_autoload_register(function ($class_name) {
            $this->loader($class_name);
        });
    }

    /**
     * Load Class
     *
     * @param string $class_name
     * @return void
     */
    public function loader($class_name) {
        // Cut Root-Namespace
        $class_name = str_replace('_', '-', strtolower($class_name));
        $class_name = str_replace(__NAMESPACE__ . '\\', '', $class_name);
        // Correct DIRECTORY_SEPARATOR
        $class_name = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, __DIR__ . DIRECTORY_SEPARATOR . $class_name . '.php');
        // Get file real path
        if (false === ($class_name = realpath($class_name))) {
            // File not found
            return false;
        } else {
            require_once $class_name;
            return true;
        }
    }

    public function init() {
        new Bowstyle_Controller_Class();
        new Config_Controller_Class();
        new Gamedraw_Controller_Class();
        new Gamemode_Controller_Class();
        new Gameset_Controller_Class();
        new Gamestatus_Controller_Class();
        new Live_Game_Controller_Class();
        new Score_Controller_Class();
        new Scoreboard_Style_Controller_Class();
        new Team_Controller_Class();
        new Player_Controller_Class();
    }
}

define("BASE_DIR", dirname(__FILE__));
define("UPLOAD_DIR", dirname(__FILE__) . "/uploads/");
define("TEMPLATE_DIR", BASE_DIR . '/templates/');


$o_controller = new Controller();
$o_controller->init();

?>