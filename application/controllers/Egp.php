<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egp extends CI_Controller
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

    public function index($egp_id = null)
    {
        $session = $this->session->egp_id;
        if ($egp_id === $session) {
            $view = 'egp/manage';
            $data['site_url'] = site_url("egp/detail/$egp_id");
            $data['site_status'] = site_url("egp/status/$egp_id");
            $data['site_view'] = site_url("egp/view/$egp_id");
            $data['site_delete'] = site_url("egp/delete/$egp_id");
            $data['site_save'] = site_url("egp/save/$egp_id");
            $data['egp_type'] = $this->db->get('tb_egp_types')->result_array();
            $this->theme->_load_theme($view, $data);
        } else {
            $this->load->view('egp/register');
        }
    }

    public function view($egp_id = null)
    {
        if ($_POST && $egp_id) {
            $table_egp = 'tb_egps';
            $field = $_POST['field'];
            $my_db = $this->get_my_db($egp_id);
            if ($field === ''):
                $data = $my_db->select('*')->get($table_egp)->row_array();
                foreach ($data as $k => $v) {
                    $data[$k] = '';
                    $data[$k] = $k === 'egp_date' ? php_date2date_pick(date('Y-m-d')) : '';
                }
            else:
                $field = array('egp_id' => $field);
                $data = $my_db->select('*')->get_where($table_egp, $field)->row_array();
                foreach ($data as $k => $v) {
                    $data[$k] = $k === 'egp_date' ? php_date2date_pick($v) : $v;
                }
            endif;
            $data['type'] = $this->db->select('egp_type_name as name,egp_type_parameter as parameter')->get('tb_egp_types')->result_array();
            return m_json_encode($data);
        } else {
            show_404();
        }
        exit();
    }

    public function delete($egp_id = null)
    {
        if ($_POST && $egp_id):
            $table_egp = 'tb_egps';
            $field = $_POST['field'];
            $my_db = $this->get_my_db($egp_id);
            $result = false;
            $field = array('egp_id' => $field);
            $result = $my_db->delete($table_egp, $field);
            return m_json_encode($result);
        else:
            show_404();
        endif;
        exit();
    }

    public function save($egp_id = null)
    {
        if ($_POST && $egp_id):
            $table_egp = 'tb_egps';
            $field = $_POST['field'];
            $my_db = $this->get_my_db($egp_id);
            $data = array(
                'egp_name' => $_POST['egp_name'],
                'egp_link' => $_POST['egp_link'],
                'egp_date' => date_pick2php_date($_POST['egp_date']),
                'egp_type' => $_POST['egp_type'],
                'egp_status' => isset($_POST['egp_status']) ? 1 : 0,
            );
            if ($field === ''):
                $result = $my_db->insert($table_egp, $data);
            else:
                $field = array('egp_id' => $field);
                $result = $my_db->update($table_egp, $data, $field);
            endif;
            return m_json_encode($result);
        else:
            show_404();
        endif;
        exit();
    }

    public function status($egp_id = null)
    {
        if ($_POST && $egp_id) {
            $table_egp = 'tb_egps';
            $field = array('egp_id' => $_POST['field']);
            $status = array('egp_status' => $_POST['status'] == 1 ? 0 : 1);
            $my_db = $this->get_my_db($egp_id);
            $result = $my_db->update($table_egp, $status, $field);
            return m_json_encode($result);
        } else {
            show_404();
        }
        exit();
    }

    public function detail($egp_id = null)
    {
        if ($_POST) {
            /*SET DATA*/
            $table_name = 'tb_domain_egp';
            /*SET CONNECTION*/
            $web = $this->db->get_where($table_name, array('egp_id' => $egp_id))->row_array();
            $my_db = $this->database_model->get_database($web);
            /*SET RESPONSE*/
            $result = $this->set_egp($my_db, $_POST);
            $page_all = $this->set_egp($my_db, $_POST, true);
            $data['datum'] = $result;
            $data['page'] = $page_all;
            return m_json_encode($data);
        } else {
            show_404();
        }
        exit(0);
    }

    private function set_egp($con, $post, $pages = false)
    {
        /*SET DATA*/
        $data = array();
        $my_data = array();
        $table_egp = 'tb_egps';
        $search = $post['search'];
        $type = $post['type'];
        $page = $post['page'];
        $limit = $post['limit'];
        $offset = ($page - 1) * $limit;
        /*SET CONNECTION*/
        $my_con = $con->like('egp_name', $search)->where('egp_type', $type);
        if ($pages):
            $data = $my_con->get($table_egp)->num_rows();
            $my_page = ceil($data / $limit);
            $data = $my_page == 0 ? 1 : $my_page;
        else:
            $data = $my_con->order_by('egp_date DESC , egp_id DESC')->limit($limit, $offset)->get($table_egp)->result_array();
            foreach ($data as $key => $datum) {
                $my_data[$key]['title'] = $datum['egp_name'];
                $my_data[$key]['link'] = $datum['egp_link'];
                $my_data[$key]['status'] = $datum['egp_status'];
                $my_data[$key]['field'] = $datum['egp_id'];
                $my_data[$key]['date'] = date_thai($datum['egp_date']);
                $my_data[$key]['list'] = ($offset + $key + 1);
            }
            $data = $my_data;
        endif;
        return $data;
    }

    private function get_my_db($egp_id)
    {
        $table_name = 'tb_domain_egp';
        $web = $this->db->get_where($table_name, array('egp_id' => $egp_id))->row_array();
        $my_db = $this->database_model->get_database($web);
        return $my_db;
    }
}
