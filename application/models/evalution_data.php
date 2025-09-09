<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class evalution_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doLoadDistrict() {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "SELECT * FROM District";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['success'] = 1;
            $data['msg'] = '';
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }

    public function doLoadEvalutionDesignation() {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "SELECT * FROM EvalutionDesignation";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result_array();
            $data['success'] = 1;
            $data['msg'] = '';
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }

    public function doLoadServiceEvalutionHead($evalutiontype) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = " 
                SELECT 
                    H.ServiceHeadID, 
                    ServiceSubHeadID,
                    ServiceHead,
                    SeriveSubHead,
                    Target,
                    Weight
                FROM ServiceEvalutionHead H
                    INNER JOIN ServiceEvalutionSubHead S
                        ON H.ServiceHeadId = S.ServiceHeadId
		WHERE H.HeadType = '$evalutiontype'
                    AND H.Active = 1
                    AND S.Active = 1
                ORDER BY ServiceHead, H.OrderSL

            ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }
        //$query->free_result();
        //return $data;
    }

    public function doLoadServiceEvalutionHead4P($evalutiontype) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "exec usp_doLoadEvalutionHead '$evalutiontype'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }
        //$query->free_result();
        //return $data;
    }

    public function doCreateEvalutionMaster($customercode, $districtcode, $evalutionby, $evalutiondate, $openingdate, $entryby, $ipaddress, $divicestate, $evalutiontype, $serviceareavolume, $serviceopeningtime, $serivcebay) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "exec usp_InsertEvalutionMaster '$customercode','$districtcode',
                    '$evalutionby','$evalutiondate','$openingdate','$entryby',
                    '$ipaddress','$divicestate','$evalutiontype','$serviceareavolume', '$serviceopeningtime', '$serivcebay' ";

        $query = $this->db->query($sql);
        //$query->free_result();
        if ($query !== false) {
            return $query->result_array();
            ;
        } else {
            return false;
        }
    }

    public function doCreateEvalutionDetails($evalutionid,
            $servicehead, $servicesubhead, $servicesubheadid, 
            $target, $actual, $weight, $score, $observations,
            $servicesubsubheadid = '',$requirmentid = '', $evalutionmethodid = '') {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "INSERT INTO ServiceEvalutionDetails VALUES 
                ('$evalutionid',
                '$servicesubheadid','$servicesubsubheadid','$requirmentid',"
                . "'$evalutionmethodid',$target,
                $actual,$weight,$score, '$observations') ";

        $query = $this->db->query($sql);
        //$query->free_result();
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function doCreateServiceEvalutionManpower($evalutionid, $designationid, $manpowercount) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "INSERT INTO ServiceEvalutionManpower VALUES 
                ('$evalutionid','$designationid','$manpowercount') ";

        $query = $this->db->query($sql);
        //$query->free_result();
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function reportevalution($DateFrom, $DateTo, $CustomerCode, $reporttype) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "EXEC usp_reportServiceEvalution '$DateFrom','$DateTo','$CustomerCode','$reporttype'";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function reportServiceEvalutionDetails($evalutionid) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "usp_reportServiceEvalutionDetails $evalutionid";
        $query = $this->db->query($sql);
        
        if ($query !== false) {
            $rows['summery'] = $query->result_array();
            $rows['details'] = $query->next_result();
            $rows['manpower'] = $query->next_result();
        }
        return $rows;
    }

    public function CustomerList($customerlistid, $period) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $period = str_replace('-', '', $period);
        $sql = "SELECT 
                        C.CustomerCode, C.CustomerName, 
                        ISNULL(D.DigitalMarketingScore,0) DigitalMarketingScore
                    FROM Customer C
                        LEFT JOIN (SELECT * FROM DealarDigitalMarketingEvalution WHERE Period = '$period') D
                            ON C.CustomerCode = D.CustomerCode
                    WHERE C.CustomerCode LIKE '%' 
                        AND CustomerType = 'E' 
                        AND LEFT(C.CustomerCode,2) = 'HC' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function doInsertDigitalMarketingEvalution($customercode, $period, $score, $userid, $ip) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "usp_doInsertDigitalMarketingEvalution '$customercode','$period',
            '$score', '$userid', '$ip'";

        $query = $this->db->query($sql);
        //$query->free_result();
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function reportdealarmonthlyevalution($Period, $CustomerCode) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = "EXEC usp_doLoadDealarRanking '$Period','$CustomerCode'";
        $query = $this->db->query($sql);
        $e = $this->db->_error_message();
        //var_dump($e); exit();
        if ($query !== false) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function doLoadEvalutionMethod() {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "SELECT * FROM EvalutionMethod WHERE EvalutionMethodStatus = 'Y' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }
        //$query->free_result();
        //return $data;
    }
    
    public function doLoadEvalutionPart2RequirmentDeails($evalutionid) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "SELECT 
                    D.RequirmentId,
                    RequirmentName,
                    RequirmentDescription 
                FROM ServiceEvalutionDetails  D
                    INNER JOIN EvalutionRequirmentMaster M
                        ON D.RequirmentId = M.RequirmentId
                WHERE EvalutionId = $evalutionid
                        AND Target != Actual
                ORDER BY 1";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }
        //$query->free_result();
        //return $data;
    }
    
    public function doInsertServiceEvalutionCheckPointDetails($evalutionid,
                    $requirmentid, $reason, $whathappen	, 
                    $whattodo, $deadline, $personincharge) {
        $sql = "usp_doInsertServiceEvalutionCheckPointDetails '$evalutionid',
                    '$requirmentid', '$reason', '$whathappen', 
                    '$whattodo', '$deadline', '$personincharge'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return true;
        }else{
            return false;
        }
    }

}
