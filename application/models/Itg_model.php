<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 26/6/2561
 * Time: 16:25
 */

class Itg_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($table, $data)
    {
        $this->db->trans_begin();
        $this->db->set($data);
        $this->db->insert($table);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function update($table, $id, $data)
    {
        $this->db->trans_begin();
        $this->db->update($table, $data, $id);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function delete($table, $id)
    {
        $this->db->trans_begin();
        $this->db->delete($table, $id);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}