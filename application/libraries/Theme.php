<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18/8/2561
 * Time: 15:11
 */

class Theme
{
    public $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function _load_theme($view, $data = array())
    {
        $this->ci->load->view('component/index', array('view' => $view, 'data' => $data));
    }
}