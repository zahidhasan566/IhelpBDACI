
To Trace Query Error
===================
try {
        $query = $this->db->query($sql);            
        return $query->result_array();
    } catch (\Throwable $th) {
        log_message('error', $this->db->_error_message()." ==== IN: ".__FILE__." Line:".__LINE__);
        show_error(QUERY_ERROR_MESSAGE);
    }