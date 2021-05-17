<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/8/2561
 * Time: 13:50
 */

class Databases_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_database($item)
    {
        $host = $item['database_host'];
        $username = $item['database_user'];
        $password = $item['database_password'];
        $database_name = $item['database_name'];
        $config = array(
            'dsn' => '',
            'hostname' => $host,
            'username' => $username,
            'password' => $password,
            'database' => $database_name,
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );
        return $my_db = $this->load->database($config, TRUE);
    }

    public function create_table($my_db, $table_name, $fields, $id)
    {
        $attributes = array('ENGINE' => 'InnoDB');
        if ($my_db) {
            $this->myforge = $this->load->dbforge($my_db, TRUE);
            $this->myforge->add_key($id, TRUE);
            $this->myforge->add_field($fields);
            $result = $this->myforge->create_table($table_name, TRUE, $attributes);
        } else {
            $this->load->dbforge($my_db, TRUE);
            $this->dbforge->add_key($id, TRUE);
            $this->dbforge->add_field($fields);
            $result = $this->dbforge->create_table($table_name, TRUE, $attributes);
        }
        return $result;
    }
}