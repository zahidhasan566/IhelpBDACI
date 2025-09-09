<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class csi_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doLoadCSIDetails($datefrom, $dateto, $customercode) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();

        $sql = "EXEC usp_doLoadCSIDetails '$datefrom','$dateto 23:59:59.000','$customercode'";
        $query = $this->db->query($sql);
        if ($query !== false) {
            $data['csidetails'] = $query->result_array();
            $data['question'] = $query->next_result();
            $data['summerydata'] = $query->next_result();

            $pattern = '/[^A-Za-z0-9\. -#@]/';
			for ($i=0; $i < count($data['csidetails']); $i++) {
				$d = preg_replace($pattern, '', $data['csidetails'][$i]['DealerName']);
				$data['csidetails'][$i]['DealerName'] = $d;       
                $d = preg_replace($pattern, '', $data['csidetails'][$i]['Chassisno']);
				$data['csidetails'][$i]['Chassisno'] = $d;  
                $d = preg_replace($pattern, '', $data['csidetails'][$i]['ScheduleTitle']);
				$data['csidetails'][$i]['ScheduleTitle'] = $d;           
                $d = preg_replace($pattern, '', $data['csidetails'][$i]['Technician_Name']);
				$data['csidetails'][$i]['Technician_Name'] = $d; 
                $d = preg_replace($pattern, '', $data['csidetails'][$i]['Bay']);
				$data['csidetails'][$i]['Bay'] = $d; 
                $d = preg_replace($pattern, '', $data['csidetails'][$i]['CSIRemarks']);
				$data['csidetails'][$i]['CSIRemarks'] = $d; 
			}

            //echo  "<pre />"; print_r($data); exit();
            return $data;
        } else {
            return false;
        }
    }

}
