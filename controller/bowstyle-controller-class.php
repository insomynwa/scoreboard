<?php
namespace scoreboard\controller;

use scoreboard\model\Bowstyle_Model_Class;
use \scoreboard\includes\Tools;

class Bowstyle_Controller_Class extends Controller_Class {

    protected $connection;
    private $model;

    private $radio_template_name;
    private $radio_template_loc;
    private $radio_json_key;

    private $option_template_name;
    private $option_template_loc;
    private $option_json_key;

    private $json_key;

    /**
     * Class Constructor
     *
     * @param object $connection Database Connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Bowstyle_Model_Class($connection);
        $this->json_key = 'bowstyle';
        $this->radio_json_key = 'radio';
        $this->option_json_key = 'option';
        $this->init_templates();
    }

    /**
     * Init Template
     *
     * @return void
     */
    private function init_templates() {
        $template_location = TEMPLATE_DIR . 'bowstyle/';
        $this->option_template_name = 'option';
        $this->radio_template_name = 'radio';

        $this->option_template_loc = $template_location . $this->option_template_name . '.php';
        $this->radio_template_loc = $template_location . $this->radio_template_name . '.php';
    }

    /**
     * Create Default Game Mode
     *
     * @return boolean
     */
    public function create_default() {

        $gamemode_om = new Bowstyle_Model_Class($this->connection);
        if (!$gamemode_om->has_default()) {
            $default_data = [
                'id' => 1,
                'name' => 'Recurve',
            ];
            $gamemode_om->create_default($default_data);

            $default_data = [
                'id' => 2,
                'name' => 'Compound',
            ];
            $gamemode_om->create_default($default_data);
        }
    }

    /**
     * Get Elements
     *
     * @param array $elements Bowstyle Element [ radio, option ]
     * @param string $custom_parent_key Custom Parent Key
     * @param integer $selected_item Selected Item
     * @param boolean $value_only If TRUE, return children
     * @return array
     */
    public function get_elements($elements = array(), $custom_parent_key = '', $selected_item = 0, $value_only = false) {
        $result = array();
        if (empty($elements)) {
            return $result;
        }

        $parent_key = '';
        if ($custom_parent_key == '') {
            $parent_key = $this->json_key;
        } else {
            $parent_key = $custom_parent_key;
        }

        if (in_array($this->radio_json_key, $elements)) {
            // $result[$this->json_key]['radio'] = $this->get_radio('radio')['radio'];
            $result[$parent_key][$this->radio_json_key] = $this->render_loop_element($this->radio_json_key, '', $selected_item, $value_only);
        }

        if (in_array($this->option_json_key, $elements)) {
            // $result[$this->json_key]['radio'] = $this->get_radio('radio')['radio'];
            $result[$parent_key][$this->option_json_key] = $this->render_loop_element($this->option_json_key, '', $selected_item, $value_only);
        }
        return $result;
    }

    /**
     * Render Element
     *
     * @param string $element_type Element Type
     * @param string $custom_key Key for JSON
     * @param integer $selected_item Selected Item
     * @param boolean $value_only If it's TRUE, return string. Otherwise return Array[$key]
     * @return mixed string | array
     */
    private function render_loop_element($element_type = '', $custom_key = '', $selected_item = 0, $value_only = false) {
        $data_list = $this->model->list();
        $key = '';
        $element_pretext = '';
        $template_loc = '';
        if ($custom_key != '') {
            $key = $custom_key;
        } else {
            if ($element_type == $this->option_json_key) {
                $key = $this->option_json_key;
                $element_pretext = '<option value="0">Choose</option>';
                $template_loc = $this->option_template_loc;
            } else if ($element_type == $this->radio_json_key) {
                $key = $this->radio_json_key;
                $template_loc = $this->radio_template_loc;
            }
        }
        $element_value = Tools::create_loop_element(
            $data_list, 'bowstyles', $key, $template_loc, $element_pretext, $selected_item
        );
        if ($value_only) {
            return $element_value[$key];
        }
        return $element_value;
    }
}
?>