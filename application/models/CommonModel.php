<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CommonModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @uses get all District or specific District by district Code
     * @param null $districtCode
     * @return array
     */
    public function getDistrict($districtCode = null) {
        $sql = "SELECT * FROM District";
        if($districtCode) {
            $districtCode =  mssql_escape($districtCode);
            $sql .= " where DistrictCode= '$districtCode'";
        }
        $sql .= " order by DistrictName";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }

    /**
     * @uses get all upazilla or specific upazilla by upazilla code
     * @param null $upazillaCode
     * @return array
     */
    public function getUpazilla($upazillaCode = null) {
        $sql ="SELECT DistrictCode, ThanaCode UpazillaCode,ThanaName UpazillaName FROM Thana";
        if($upazillaCode) {
            $upazillaCode = mssql_escape($upazillaCode);
            $sql .= " where ThanaCode = '$upazillaCode'";
        }
        $sql .= " order by 3";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
     }

    /**
     * @uses get  all upazilla list or upazila list under a district code
     * @param null $districtCode
     * @return array
     */
    public function getUpazillaByDistrictCode($districtCode = null) {
        $sql ="SELECT DistrictCode, ThanaCode UpazillaCode, ThanaName UpazillaName FROM Thana";
        if($districtCode) {
            $districtCode =  mssql_escape($districtCode);
            $sql .= " where ThanaCode= '$districtCode'";
        }
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }

}
