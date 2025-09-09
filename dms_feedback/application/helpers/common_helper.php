<?php
    function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    function get_ip_address(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 
                'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | 
                            FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }

    /**
    *   Validate an email address.
    *   Provide email address (raw input)
    *   Returns TRUE if the email address has the email 
    *   address format and the domain exists.
    */
    function validEmail($email) {
        $isValid = TRUE;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = FALSE;
        } else{
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                // local part length exceeded
                $isValid = FALSE;
            } else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                $isValid = FALSE;
            } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
                // local part starts or ends with '.'
                $isValid = FALSE;
            } else if (preg_match('/\\.\\./', $local)) {
                // local part has two consecutive dots
                $isValid = FALSE;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                // character not valid in domain part
                $isValid = FALSE;
            } else if (preg_match('/\\.\\./', $domain)) {
                // domain part has two consecutive dots
                $isValid = FALSE;
            } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                str_replace("\\\\","",$local))) {
                    // character not valid in local part unless 
                    // local part is quoted
                    if (!preg_match('/^"(\\\\"|[^"])+"$/',
                        str_replace("\\\\","",$local))) {
                        $isValid = FALSE;
                    }
            }                    
        }
        return $isValid;
    }

    function quoteCheck($value) {        
        // Quote if not a number or a numeric string
        if (!is_numeric($value)) {
            $value = mysql_real_escape_string($value);
        }
        return $value;
    }    

    function fileList($dir, $typefile='gif|png') {     	   	
        $data = array();		
        $d =  get_filenames($dir);

        foreach ($d as $file) {
            $filter = '/\.('.$typefile.')$/';
            if(!preg_match($filter, $file)) continue;
            $info = get_file_info($dir.$file);			
            $size = $info['size'];
            $lastmod = date("d/m/Y",($info['date']*1000));
            $data[] = array('name'=>$file, 'size'=>$size,
                'lastmod'=>$lastmod, 'url'=>$dir.$file);
        }
        $o = array('filelist'=>$data);
        return json_encode($o);	
    }    
    
    function passdecode($xpassword) {
        $decode = "";        

        For ($i = strLen($xpassword)-1; $i >= 0; $i--) {
            $decode .= Chr(ord(substr($xpassword, $i, 1)) - 104);
        }
        return $decode;
    }

    function passencode($xpassword) {
        $encode = "";

        For ($i = strLen($xpassword)-1; $i>= 0; $i--) {
            $encode .= Chr(ord(substr($xpassword, $i, 1)) + 104);
        }
        return $encode;
    }
    
    function doPrntSelectOption($array, $valuename, $showname, $selectedvalue = ''){
        $string = '';
        if(!empty($array)){
            foreach($array AS $row){
                if($selectedvalue == $row[$valuename]){
                    $selected = ' selected="selected" ';
                }else{
                    $selected = '';
                }
                $string .= "<option ".$selected." value='".$row[$valuename]."'>".$row[$showname]."</option>";
            }
        }
        return $string;
    }
    
    function mssql_escape($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str= stripslashes(nl2br($str));
        }
        return str_replace("'", "''", $str);
    }
	
	function utf8ize($d) {
		//echo "adf";
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }