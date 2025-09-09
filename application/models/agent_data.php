<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class agent_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
 
    public function doLoadAgentList($userid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT * FROM RideAgent "
                . " WHERE ActiveStatus = 'Y' "
                . " AND DealarCode LIKE '%$userid%'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    public function doCheckDuplicateUser($appusername) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "SELECT 
                    Name,UserName 
                FROM RideAgent 
                WHERE UserName = '$appusername'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doLoadAgentInfo($agentid, $withtoken) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        if ($withtoken == 'Y') {
            $sql = "SELECT 
                            R.*, ISNULL(A.Tocket, '') Token
                    FROM RideAgent R
                            LEFT JOIN RideAgentTocken A
                                    ON R.AgentId = A.AgentId
                            INNER JOIN (SELECT AgentId, MAX(TocketId) TocketId FROM RideAgentTocken GROUP BY AgentId) AA
                                    ON A.TocketId = AA.TocketId
                    WHERE R.Agentid = $agentid ";
        } else {
            $sql = "SELECT 
                        R.*
                FROM RideAgent R
                WHERE R.Agentid = $agentid ";
        }

        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doInsertUpdateRideAgent($name, $dbo, $phoneno, $alternatephoneno, $emailaddress, $address, $nid, $drivinglicenceno, $ridinglocation, $districtcode, $dealarcode, $username, $password, $agentpicture, $activestatus, $entryby, $entryipaddress, $entrydivicestate, $actiontype, $actionid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "usp_doInsertUpdateRideAgent '$name','$dbo','$phoneno','$alternatephoneno',
                        '$emailaddress','$address','$nid','$drivinglicenceno',
                        '$ridinglocation','$districtcode','$dealarcode','$username',
                        '$password','$agentpicture','$activestatus','$entryby',
                        '$entryipaddress','$entrydivicestate','$actiontype','$actionid'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    public function doLoadAgentListSummery($customerlistid, $pagelimit, $page) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "exec usp_doLoadAgentList '$customerlistid', '$pagelimit', '$page' ";

        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['result'] = $query->result_array();
            $data['paging'] = $query->next_result();
            return $data;
        } else {
            return false;
        }
    }
    
    public function reportRideAgentReport($datefrom, $dateto, $customercode){
        $data['success'] = 0;
        $sql = "exec usp_doLoadTestRideDeatils '$customercode', '$datefrom', '$dateto' ";
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }
}
