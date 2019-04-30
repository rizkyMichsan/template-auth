<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    // get all
    function get_all($table, $sort = null)
    {
        if ($sort == null) {
            $sort = 'DESC';
        } else {
            $sort = 'ASC';
        }
        $ids = 'id';
        $this->db->order_by($ids, $sort);
        $query = $this->db->get($table);
        return $query;
    }

    // get data by id
    function get_where($table, $data)
    {
        $query = $this->db->get_where($table, $data);
        return $query;
    }
    function get_limit($table, $limit)
    {
        $ids = 'id';
        $this->db->order_by($ids, 'DESC');
        $query = $this->db->get($table, $limit);
        return $query;
    }
    function get_join($table1, $table2, $data)
    {
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1 . '.id = ' . $table2 . '.id');
        $this->db->where($data);
        $query = $this->db->get();
        return $query;
    }
    // insert data
    function insert($table, $data)
    {
        $hasil = $this->db->insert($table, $data);
        return $hasil;
    }

    // update data
    function update($table, $id, $data)
    {
        $this->db->where('id', $id);
        $hasil = $this->db->update($table, $data);
        return $hasil;
    }

    // delete data
    function delete($table, $id)
    {
        $ids = 'id';
        $this->db->where($ids, $id);
        $hasil = $this->db->delete($table);
        return $hasil;
    }
}
