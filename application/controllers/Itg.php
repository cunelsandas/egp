<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Itg extends CI_Controller
{
    public $database_model;
    public $itg_model;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('itg_model');
        $this->database_model = new Databases_model();
        $this->itg_model = new Itg_model();
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
        $bg = isset($_GET['bg-code']) && $_GET['bg-code'] != '' ? "#{$_GET['bg-code']}" : 'transparent';
        $table_name = 'tb_domain_egp';
        $egp = $this->db->select('egp_id')->get_where($table_name, array('egp_id' => $egp_id, 'database_status' => 1, 'egp_date_exp >' => date('Y-m-d')))->num_rows();
        $data = array();
        if ($egp):
            $data['egp_type'] = $this->db->order_by("listno", "asc")->get('tb_egp_types')->result_array();
            $data['site_url'] = site_url("itg/get_egp/$egp_id");
            $data['bg_color'] = "{$bg}";
            $this->load->view('egp/index', $data);
        else:
            $this->load->view('egp/register');
        endif;
    }

    public function get_egp($egp_id = null)
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
        $my_con = $con->like('egp_name', $search)->where('egp_type', $type)->where('egp_status', 1);
        if ($pages):
            $data = $my_con->get($table_egp)->num_rows();
            $my_page = ceil($data / $limit);
            $data = $my_page == 0 ? 1 : $my_page;
        else:
            $data = $my_con->order_by('egp_date DESC,egp_id DESC')->limit($limit, $offset)->get($table_egp)->result_array();
            foreach ($data as $key => $datum) {
                $my_data[$key]['title'] = $datum['egp_name'];
                $my_data[$key]['link'] = $datum['egp_link'];
                $my_data[$key]['date'] = date_thai($datum['egp_date']);
                $my_data[$key]['list'] = ($offset + $key + 1);
            }
            $data = $my_data;
        endif;
        return $data;
    }

    public function test()
    {
        $this->load->view('egp_view/test');
    }
}
