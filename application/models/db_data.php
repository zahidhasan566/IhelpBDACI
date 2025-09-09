<?php

Class db_data extends CI_Model {

    
	
    public function ProductDealerSale($DateStart,$DateEnd,$ProductCode=null,$Dealer=null) { 
        $sql = " EXEC usp_dbProductWiseSalesComparison '$DateStart', '$DateEnd', '$ProductCode', '$Dealer'  ";
        $data = array();
        $query = $this->db->query($sql);
        if ($query) {                     
            $data['first'] = $query->result_array();                         
            $data['secound'] = $query->next_result();          
            return $data;               
        }else{
            return false;    
        }        
        
    }
    
    public function SalesVsInquery($DateStart,$DateEnd,$ProductCode=null,$Dealer=null) { 
        $sql = " EXEC usp_dbSalesVsInquery '$DateStart', '$DateEnd', '$ProductCode', '$Dealer'  ";
        $data = array();
        $query = $this->db->query($sql);
        if ($query) {                     
            return $query->result_array();                         
        }else{
            return false;    
        }        
        
    }
    
    public function OrderApprovedInvoice($DateStart,$DateEnd,$ProductCode=null,$Dealer=null) { 
        $sql = " EXEC usp_dbOrderApprovedInvoice '$DateStart', '$DateEnd', '$ProductCode', '$Dealer'  ";
        $data = array();
        $query = $this->db->query($sql);
        if ($query) {                     
            return $query->result_array();                         
        }else{
            return false;    
        }        
        
    }
    
    public function ServiceRatio($DateStart,$DateEnd,$ProductCode=null,$Dealer=null) { 
        $sql = " EXEC usp_dbServiceRatio '$DateStart', '$DateEnd', '$ProductCode', '$Dealer'  ";
        //echo $sql;
        $data = array();
        $query = $this->db->query($sql);
        if ($query) {                     
            $data['first'] = $query->result_array();                         
            $data['secound'] = $query->next_result();                         
            return $data;
        }else{
            return false;    
        }        
        
    }
    
     public function DealerOrder($DateStart,$DateEnd,$ProductCode=null,$Dealer=null) { 
        $sql = " EXEC usp_dbDealerOrderProductWise '$DateStart', '$DateEnd', '$ProductCode', '$Dealer'  ";
        $data = array();
        $query = $this->db->query($sql);
        if ($query) {                     
            return $query->result_array();                         
        }else{
            return false;    
        }        
        
    }
    
	public function dayWiseSales($DateStart,$DateEnd,$ProductCode=null,$Dealer=null) { 
        $sql = " EXEC usp_dbDayWiseSales '$DateStart', '$DateEnd', '$ProductCode', '$Dealer'  ";
        $data = array();
        $query = $this->db->query($sql);
        if ($query) {                     
            $data['first'] = $query->result_array();                         
			$data['secound'] = $query->next_result(); 
				return $data;
        }else{
            return false;    
        }        
        
    }
    
    public function dealerstock($ProductCode='',$Dealer='') { 
        $sql = " EXEC usp_dbDealarWiseStock '$ProductCode', '$Dealer'  ";
        $data = array();
        $query = $this->db->query($sql);
        if ($query) {                     
            $data['first'] = $query->result_array();                         
            $data['secound'] = $query->next_result(); 
                return $data;
        }else{
            return false;    
        }        
        
    }
    
    public function weekWiseSales($DateStart,$DateEnd,$ProductCode=null,$Dealer=null) { 
        $sql = " EXEC usp_dbWeekWiseSales '$DateStart', '$DateEnd', '$ProductCode', '$Dealer'  ";
        $data = array();
        $query = $this->db->query($sql);
        if ($query) {                     
            return $query->result_array();                         
        }else{
            return false;    
        }        
        
    }
    
    function SelectQuery($FieldName,$TableName,$Where = null,$Order = null,$limit=null){
        $top = "";
        if($limit !=null) {
            $top =" top $limit "." ";
        }
        $sql = "SELECT $top $FieldName FROM $TableName ";
        if(!empty($Where)){ $sql .= "WHERE $Where "; }
        $sql .= "$Order";
        //echo $sql; 
        //exit();
        $query = $this->db->query($sql);
        if ($query) {
            return $query->result_array();
        } else {
            return false;
        }    
    }
    
    
    
	
    
}
