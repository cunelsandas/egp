<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public $database_model;
    public $theme;

    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
        $this->database_model = new Databases_model();
        $this->theme = new Theme();
    }

    public function _remap($method)
    {
        if (method_exists($this, $method)) {
            $this->$method($this->uri->segment(3), $this->uri->segment(4));
        } else {
            $this->index($method);
        }
    }

    public function index($s = null)
    {
        $this->load->view('home/index');
    }

    public function get_egp()
    {
        $data = array();
        $test = array();
        $table_name = 'tb_domain_egp';
        if ($_POST) {
            $i = $_POST['i'];
            $ii = $_POST['ii'];
            $k = $_POST['k'];
            $kk = $_POST['kk'];
            $field = $_POST['field'];
            $egp_type = $_POST['type'];
            $web = $this->db->get_where($table_name, array('database_id' => $field))->row_array();
            $my_db = $this->database_model->get_database($web);
            $percent = number_format($i * 100 / $ii, 2) . '%';
            $test = $this->set_egp($my_db, $web['egp_id'], $egp_type);
            $data['percent'] = $percent;
            $data['success'] = $web['database_name'];
            $data['deptID'] = $web['egp_id'];
            $data['test'] = $test;
        }
        return m_json_encode($data);
    }

    public function get_webs()
    {
        $data = array();
        if ($_POST) {
            $table_name = 'tb_domain_egp';
            $table_egp_type = 'tb_egp_types';
            $webs = $this->db->select('database_id as field,database_name as database')->where(array('database_status' => 1))->get($table_name)->result_array();
            $egp_type = $this->db->select('egp_type_parameter as egp_type,egp_type_name')->get($table_egp_type)->result_array();
            $data['webs'] = $webs;
            $data['egp_type'] = $egp_type;
        }
        return m_json_encode($data);
    }

    private function set_egp($my_db, $dep_id, $egp_type)
    {
        $xml = array();
//        $egp_type = $val['egp_type_parameter'];
//        $file = APPPATH . ".." . DIRECTORY_SEPARATOR . "egp" . DIRECTORY_SEPARATOR . "W1.xml";
        $file = "https://process3.gprocurement.go.th/EPROCRssFeedWeb/egpannouncerss.xml?deptId={$dep_id}&anounceType={$egp_type}";
        $data = $this->read_egp($file);
        $test = "START";
        if ($data) {
            if (isset($data['title'])) {
                $xml['egp_name'] = $data['title'];
                $xml['egp_link'] = $data['link'];
                $xml['egp_date'] = $data['pubDate'];
                $xml['egp_type'] = $egp_type;
                $test = $data['title'];
                $xml['egp_status'] = 1;
                if ($this->check_egp($my_db, $xml['egp_link'], $xml['egp_name'], $xml['egp_type'])) {
                    $my_db->insert('tb_egps', $xml);
                }
            } else {
                foreach ($data as $dKey => $dVal) {
                    $xml['egp_name'] = $dVal['title'];
                    $xml['egp_link'] = $dVal['link'];
                    $xml['egp_date'] = $dVal['pubDate'];
                    $xml['egp_type'] = $egp_type;
                    $test = $data['title'];

                    $xml['egp_status'] = 1;
                    if ($this->check_egp($my_db, $xml['egp_link'], $xml['egp_name'], $xml['egp_type'])) {
                        $my_db->insert('tb_egps', $xml);
                    }
                }
            }
        }
        return $test;
    }

    private function check_egp($my_db, $egp_link, $egp_name, $egp_type)
    {
        $table = 'tb_egps';
        $data = $my_db->get_where($table, array('egp_link' => $egp_link, 'egp_name' => $egp_name, 'egp_type' => $egp_type))->num_rows();
        $result = $data > 0 ? false : true;
        return $result;

    }

    private function read_egp($file)
    {
        $use_errors = libxml_use_internal_errors(true);
        $xml = simplexml_load_file($file);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        libxml_clear_errors();
        libxml_use_internal_errors($use_errors);
        $array = isset($array['channel']['item']) ? $array['channel']['item'] : array();
        return $array;
    }

    /*public function add_database()
    {
        for ($i = 1; $i <= 200; $i++) {
            if ($this->dbforge->create_database("domain{$i}")) {
                echo 'Database created!';
            }
        }
    }

    public function drop_database()
    {
        for ($i = 1; $i <= 200; $i++) {
            if ($this->dbforge->drop_database("domain{$i}")) {
                echo 'Database dropped';
            }
        }
    }*/

    /*public function add_table()
    {
        $table_name = 'tb_domain_egp';
        $domains = $this->db->get($table_name)->result_array();
        foreach ($domains as $key => $val) {
            $my_db = $this->database_model->get_database($val);
            $egp_field = $this->set_field_egp();
            $egp_type_field = $this->set_field_egp_type();
            try {
                $re = true;
                $rr = $this->database_model->create_table($my_db, 'tb_egps', $egp_field, 'egp_id');
//                $re = $this->database_model->create_table($my_db, 'tb_egp_types', $egp_type_field, 'egp_type_id');
                if (!$rr && !$re) {
                    throw new Exception('Not');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            };
        }
    }*/

    /*public function add_database_egp()
    {
        $table_name = 'tb_domain_egp';
        $data['database_host'] = 'localhost';
        $data['database_user'] = 'root';
        $data['database_password'] = '12345678';
        $data['egp_id'] = '0307';
        for ($i = 1; $i <= 200; $i++) {
            $data['database_name'] = "domain{$i}";
            $result = $this->db->insert($table_name, $data);
            if ($result) {
                echo "domain{$i} is ok";
            }
        }
    }*/

    /*public function add_egp_type()
    {
        $table_name = 'tb_domain_egp';
        $webs = $this->db->get($table_name)->result_array();
        $data = array(
            array('egp_type_parameter' => 'P0', 'egp_type_name' => 'แผนการจัดซื้อจัดจ้าง'),
            array('egp_type_parameter' => '15', 'egp_type_name' => 'ประกาศราคากลาง'),
            array('egp_type_parameter' => 'B0', 'egp_type_name' => 'e-Bidding'),
            array('egp_type_parameter' => 'D0', 'egp_type_name' => 'ประกาศเชิญชวน'),
            array('egp_type_parameter' => 'W0', 'egp_type_name' => 'ประกาศรายชื่อผู้ชนะการเสนอราคา'),
            array('egp_type_parameter' => 'D1', 'egp_type_name' => 'ยกเลิกประกาศเชิญชวน'),
            array('egp_type_parameter' => 'W1', 'egp_type_name' => 'ยกเลิกประกาศรายชื่อผู้ชนะการเสนอราคา'),
            array('egp_type_parameter' => 'D2', 'egp_type_name' => 'เปลี่ยนแปลงประกาศเชิญชวน'),
            array('egp_type_parameter' => 'W2', 'egp_type_name' => 'เปลี่ยนแปลงประกาศรายชื่อผู้ชนะการเสนอราคา'),
            array('egp_type_parameter' => '', 'egp_type_name' => 'ค่าเริ่มต้น')
        );
        foreach ($webs as $key => $val) {
            if ($key < 7) {
                $my_db = $this->database_model->get_database($val);
                foreach ($data as $dKey => $dVal) {
                    $my_db->insert('tb_egp_types', $dVal);
                }
            }
        }
    }*/

    public function test_read()
    {
        $web = $this->db->get_where('tb_domain_egp', array('database_id' => 1))->row_array();
        $my_db = $this->database_model->get_database($web);
        $xml = array();
        $egp_types = $my_db->get('tb_egp_types')->result_array();
        $dep_id = '0307';
        foreach ($egp_types as $key => $val) {
            $egp_type = $val['egp_type_parameter'];
//            $file = APPPATH . ".." . DIRECTORY_SEPARATOR . "egp" . DIRECTORY_SEPARATOR . "W1.xml";
            $file = "https://process3.gprocurement.go.th/EPROCRssFeedWeb/egpannouncerss.xml?deptId={$dep_id}&anounceType={$egp_type}";
            $data = $this->read_egp($file);
            if ($data) {
                if (isset($data['title'])) {
                    $xml['egp_name'] = $data['title'];
                    $xml['egp_link'] = $data['link'];
                    $xml['egp_date'] = $data['pubDate'];
                    $xml['egp_type'] = $val['egp_type_parameter'];
                    $xml['egp_status'] = 1;
                    if ($this->check_egp($my_db, $xml['egp_link'], $xml['egp_name'], $xml['egp_type'])) {
                        var_dump($xml);
                    }
                } else {
                    foreach ($data as $dKey => $dVal) {
                        $xml['egp_name'] = $dVal['title'];
                        $xml['egp_link'] = $dVal['link'];
                        $xml['egp_date'] = $dVal['pubDate'];
                        $xml['egp_type'] = $val['egp_type_parameter'];
                        $xml['egp_status'] = 1;
                        if ($this->check_egp($my_db, $xml['egp_link'], $xml['egp_name'], $xml['egp_type'])) {
                            var_dump($xml);
                        }
                    }
                }
            }
        }
    }

    private function set_field_egp()
    {
        return $fields = array(
            'egp_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'egp_name' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'egp_link' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'egp_date' => array(
                'type' => 'DATE'
            ),
            'egp_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'egp_status' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
        );
    }

    private function set_field_egp_type()
    {
        return $fields = array(
            'egp_type_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'egp_type_parameter' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'egp_type_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            )
        );
    }
}
