<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
    public $database_model;
    public $itg_model;
    public $theme;
    private $tb_name;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('itg_model');
        $this->database_model = new Databases_model();
        $this->itg_model = new Itg_model();
        $this->theme = new Theme();
        $this->tb_name = 'tb_users';
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
            $view = 'user/index';
            $data = array(
                'site' => site_url('user/get'),
                'site_view' => site_url('user/view'),
                'site_save' => site_url('user/save'),
                'site_delete' => site_url('user/delete')
            );
            $this->theme->_load_theme($view, $data);
        else:
            show_404();
        endif;
    }

    public function login()
    {
        if ($_POST) {
            $where = array('username' => $_POST['username'], 'password' => md5($_POST['password']));
            $u = $this->db->select('user_id,name,username,permission')->get_where('tb_users', $where)->row_array();
            $s = $this->db->select('egp_id')->get_where('tb_domain_egp', array('database_id' => $u['permission']))->row_array();
            $s = $s ? $s : array();
            $u = $u ? $u : array();
            $new_session = array_merge($u, $s);
            $this->session->set_userdata($new_session);
            if ($this->session->permission == 0):
                $site = site_url('manage');
            else:
                $egp_id = $this->session->egp_id;
                $site = site_url("egp/{$egp_id}");
            endif;
            $message = $u ? 'ยินดีต้อนรับ' : 'ชื่อผู้ใช้ หรือ รหัสผ่านผิดพลาด!!';
            echo "<script>alert('{$message}'); window.location='{$site}'</script>";
        } else {
            show_404();
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $site = site_url('manage');
        echo "<script>window.location='{$site}'</script>";
    }

    public function get()
    {
        $table_name = $this->tb_name;
        $search = $_POST['search'];
        $page = $_POST['page'];
        $limit = $_POST['limit'];
        $offset = ($page - 1) * $limit;
        $data = array();
        $all = $this->db->like('username', $search)->limit($limit, $offset)->get($table_name)->result_array();
        $pages = ceil($this->db->like('username', $search)->get($table_name)->num_rows() / $limit);
        $my_data = array();
        foreach ($all as $key => $value) {
            $my_data[$key]['list'] = $key + 1 + $offset;
            $my_data[$key]['username'] = $value['username'];
            $my_data[$key]['name'] = $value['name'];
            $my_data[$key]['field'] = $value['user_id'];
        }
        $data['all'] = $my_data;
        $data['page'] = $pages === 0 ? 1 : $pages;
        return m_json_encode($data);
    }

    public function view()
    {
        if ($_POST) {
            $table_name = $this->tb_name;
            $field = $_POST['field'];
            if ($field === '') {
                $data = $this->db->select('username,name,user_id as field,password_fix as password,permission')->get($table_name)->row_array();
                foreach ($data as $key => $value) {
                    $data[$key] = '';
                }
            } else {
                $data = $this->db->select('username,name,user_id as field,password_fix as password,permission')
                    ->get_where($table_name, array('user_id' => $field))->row_array();
            }
            $data['domain'] = $this->db->select('database_id as domain_id,egp_name as name')->get_where('tb_domain_egp', array('database_status' => 1))->result_array();
            return m_json_encode($data);
        } else {
            show_404();
        }
        exit();
    }

    public function save()
    {
        $table_name = $this->tb_name;
        $field = $_POST['field'];
        if ($_POST) {
            $data = array(
                'name' => $_POST['name'],
                'username' => $_POST['username'],
                'password' => md5($_POST['password']),
                'password_fix' => $_POST['password'],
                'permission' => $_POST['permission'],
            );
            if ($field === '') {
                $result = $this->itg_model->insert($table_name, $data);
            } else {
                $field = array('user_id' => $field);
                $result = $this->itg_model->update($table_name, $field, $data);
            }
            return m_json_encode($result);
        } else {
            show_404();
        }
        exit();
    }

    public function delete()
    {
        if ($_POST) {
            $table_name = $this->tb_name;
            $field = $_POST['field'];
            $where = array('user_id' => $field);
            $result = $this->itg_model->delete($table_name, $where);
            return m_json_encode($result);
        } else {
            show_404();
        }
        exit();
    }

    public function test()
    {
        $where = array('username' => 'test1', 'password' => md5('1234'));
        $new_session = $this->db->select('user_id,name,username,permission')->get_where('tb_users', $where)->row_array();
        $s = $this->db->select('egp_id')->get_where('tb_domain_egp', array('database_id' => $new_session['permission']))->row_array();
        $s = $s ? $s : array();
        var_dump(array_merge($new_session, $s));
    }
}