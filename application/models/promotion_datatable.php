<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promotion_datatable extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    var $table = "ProductPromo as p";
    var $select_column = "p.PromoId,p.PromoName,p.PromoStartDate,p.PromoEndDate,pb.BrandName,p.BrandCode,p.EntryDate,p.Active";

    function make_query(){
        $sql = "SELECT ".$this->select_column." from ".$this->table." join ProdBrand pb on pb.BrandCode = p.BrandCode";
        if(isset($_POST["search"]["value"]))
        {
            $sql .= " where p.PromoName like '".$_POST['search']['value']."%' ";
        }
        if(isset($_POST["order"]))
        {
            $explode = explode(',',$this->select_column);
            $sql .= " order by ".$explode[$_POST['order']['0']['column']]." ".$_POST['order']['0']['dir'];
        }
        else
        {
            $sql .= " order by p.EntryDate desc";
        }
        return $sql;
    }

    function make_datatables(){
        $sql = $this->make_query();
        if(strtolower($_POST["length"]) != -1)
        {
            $sql .= " offset ".$_POST['start']." rows fetch next ".$_POST['length']." rows only ";
        }
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_filtered_data() {
        $sql = $this->make_query();
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    function get_all_data() {
        $sql = "SELECT ".$this->select_column." from ".$this->table." join ProdBrand pb on pb.BrandCode = p.BrandCode";
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
}