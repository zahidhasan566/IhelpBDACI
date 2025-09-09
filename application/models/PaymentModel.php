<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class PaymentModel extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    public function getCustomeInformation($customerMasterCode) {
        $sql = "select *,B.BusinessName From CustomerMapping  cm
                    inner join Customer c on cm.CustomerCode = c.CustomerCode
                    inner join Business B on B.Business = cm.Business
                where CustomerMasterCode = '$customerMasterCode' AND C.Business = 'C'";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];
    }

    public function getMoneyReceiptNo($depot, $business) {
        $sql = "exec usp_generateMoneyReceiptNo '$depot', '$business'";
        $query = $this->db->query($sql);
        if($query && !empty($result = $query->result_array())) {
            return $result[0]['MoneyReceiptNo'];
        }
        return null;
    }

    public function getCustomePayments($customerMasterCode,$business,$dateFrom, $dateTo,$table = 'PaymentTempOnline') {
        if($customerMasterCode=='all') {
            $customerMasterCode = '';
        }
        $dateTo = $dateTo . ' 23:59:59';
        $sql = "select PT.*,B.BankName,Bis.BusinessName,
                    case when Approved<>'Y' then 'Pending' else 'Approved' end as ApproveStatus
                From $table PT               
                join Banks B on B.BankCode = PT.BankCode
                join Business Bis on Bis.Business = PT.Business
                left join Customer C on C.CustomerCode = PT.CustomerCode
        where (PT.CustomerCode='$customerMasterCode' OR '' = '$customerMasterCode') 
        AND (PT.Business='$business' OR ''='$business')
        AND ChequeImage IS NOT NULL
        AND PaymentDate between '$dateFrom' and '$dateTo'";

        // die($sql);
        $query = $this->db->query($sql);
        if($query) {
            return $query->result_array();
        }
        return [];        

    }
    
    
                        
                            
                        
}
    
                        