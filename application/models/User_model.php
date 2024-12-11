<?php

class User_model extends CI_Model {
    function get($where = array()) {
        if($where) {
            return $this->db->get_where("users",$where);
        } else {
            return $this->db->get("users");
        }
    }

    function set_shop($data) {
        $this->db->where("id",1);
        $this->db->update("shop_info",$data);
    }

    function set_user($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("users",$data);
    }

      // Retrieve all users or filter based on conditions
    function get_users($where = array()) {
        if ($where) {
            return $this->db->get_where("users", $where)->result();
        } else {
            return $this->db->get("users")->result();
        }
    }

    // Retrieve a single user by ID
    function get_user_by_id($id) {
        return $this->db->get_where("users", array("id" => $id))->row();
    }

    function get_role_by_id($id) {
        return $this->db->get_where("roles", array("roleid" => $id))->row();
    }

    // Insert a new user
    function insert_user($data) {
        $this->db->insert("users", $data);
        return $this->db->insert_id();
    }

    function insert_role($data) {
        $this->db->insert("roles", $data);
        return $this->db->insert_id();
    }

    // Update a user by ID
    function update_user($id, $data) {
        $this->db->where("id", $id);
        $this->db->update("users", $data);
    }

    function update_role($id, $data) {
        $this->db->where("roleid", $id);
        $this->db->update("roles", $data);
    }

    // Delete a user by ID
    function delete_user($id) {
        $this->db->delete("users", array("id" => $id));
    }

    function delete_role($id) {
        $this->db->delete("roles", array("roleid" => $id));
    }

    function get_role($id = 0) {
        if(!$id) {
            $this->db->order_by("roleid","ASC");
            return $this->db->get("roles");
        } else {
            return $this->db->get_where("roles",['roleid' => $id]);
        }
    }
}