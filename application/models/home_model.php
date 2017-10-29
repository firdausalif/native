<?php
class Home_model extends CI_Model{

    function is_UserExist($where){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($where);
        $query = $this->db->get()->num_rows();
        if($query >0){
            return true;
        }else{
            return false;
        }
    }

    function is_UserExistNotMe($where, $me){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($where);
        $this->db->where('username !=', $me);
        $query = $this->db->get()->num_rows();
        if($query >0){
            return true;
        }else{
            return false;
        }
    }

    function get_users_pass($username){
        $query = "select password from users where username = '".$username."'";
        return $this->db->query($query)->row();
    }

    function getTable($table){
        $this->db->select('*');
        $this->db->from($table);
        $query = $this->db->get();
        if($query->num_rows() == 0){
            return false;
        }else{
            return $query;
        }
    }

    public function updateTable($table, $data, $where){
        $this->db->where($where);
        return $this->db->update($table, $data);
    }
}