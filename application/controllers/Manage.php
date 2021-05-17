<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller
{
    public $database_model;
    public $itg_model;
    public $theme;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('itg_model');
        $this->database_model = new Databases_model();
        $this->itg_model = new Itg_model();
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
        if ($this->session->permission == 0):
            $data['site_domain'] = site_url("manage/detail");
            $this->theme->_load_theme('manage/index', $data);
        else:
            $this->session->sess_destroy();
            $site = site_url('manage');
            echo "<script>window.location='{$site}'</script>";
        endif;
    }

    public function detail()
    {
        if ($_POST) {
            $limit = $_POST['limit'];
            $page = $_POST['page'];
            $search = $_POST['search'];
            $offset = ($page - 1) * $limit;
            $data = array();
            $table_name = 'tb_domain_egp';
            $datum = $this->db->like('egp_id', $search)->or_like('database_name', $search)->or_like('egp_name', $search)->limit($limit, $offset)->get($table_name)->result_array();
            $pages = $this->db->like('egp_id', $search)->or_like('database_name', $search)->or_like('egp_name', $search)->get($table_name)->num_rows();
            $data['pages'] = ceil($pages / $limit);
            foreach ($datum as $key => $val) {
                $datum[$key]['list'] = ($offset + $key + 1);
            }
            $data['datum'] = $datum;
            return m_json_encode($data);
        }
    }

    public function change_status()
    {

        if ($_POST) {
            $table_name = 'tb_domain_egp';
            $field = $_POST['field'];
            $status = $_POST['status'];
            $new_status = $status == 1 ? 0 : 1;
            $result = $this->itg_model->update($table_name, array('database_id' => $field), array('database_status' => $new_status));
            return m_json_encode($result);
        } else {
            show_404();
        }
        exit();
    }

    public function view()
    {
        if ($_POST) {
            $table_name = 'tb_domain_egp';
            $field = $_POST['field'];
            $data = $this->db->get_where($table_name, array('database_id' => $field))->row_array();
            if ($field === '') {
                $data = $this->db->get($table_name)->row_array();
                foreach ($data as $k => $v) {
                    $data[$k] = $k == 'egp_date_start' || $k == 'egp_date_exp' ? php_date2date_pick(date('Y-m-d')) : '';
                }
            } else {
                foreach ($data as $k => $v) {
                    $data[$k] = $k == 'egp_date_start' || $k == 'egp_date_exp' ? php_date2date_pick($v) : $v;
                }
            }
            return m_json_encode($data);
        } else {
            show_404();
        }
        exit();
    }

    public function save()
    {
        if ($_POST) {
            $field = $_POST['field'];
            $table_name = 'tb_domain_egp';
            $result = false;
            $data_set = array();
            foreach ($_POST as $k => $v) {
                if ($k !== 'field') {
                    $data_set[$k] = $k === 'egp_date_start' || $k === 'egp_date_exp' ? date_pick2php_date($v) : $v;
                    $data_set['database_status'] = 1;
                }
            }
            if ($field === '') {
                $result = $this->itg_model->insert($table_name, $data_set);
            } else {
                $field = array('database_id' => $field);
                $result = $this->itg_model->update($table_name, $field, $data_set);
            }
            return m_json_encode($result);
        } else {
            show_404();
        }
        exit();
    }

    public function delete()
    {
        $result = false;
        if ($_POST) {
            $table_name = 'tb_domain_egp';
            $field = array('database_id' => $_POST['field']);
            $result = $this->itg_model->delete($table_name, $field);
            return m_json_encode($result);
        } else {
            show_404();
        }
        exit();
    }

    public function table()
    {
        if ($_POST) {
            $field = $_POST['field'];
            $table_name = 'tb_domain_egp';
            $my_item = $this->db->get_where($table_name, array('database_id' => $field))->row_array();
            $my_db = $this->database_model->get_database($my_item);
            $result = $this->database_model->create_table($my_db, 'tb_egps', $this->field_tb_egps(), 'egp_id');
            return m_json_encode($result);
        } else {
            show_404();
        }
        exit();
    }

    private function field_tb_egps()
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
}