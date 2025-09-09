<?php
    Class NumToWord {
        private $mInWord            = '';
        private $mblnTeen           = true;
        private $OneS               = array();
        private $Tens               = array();
        private $BDigit             = array();
        private $HundredsInTk       = array();
        private $BHundredsInTk      = array();
        private $Teens              = array();
        private $HundredsInDollar   = array();
 
        
        private $lMillionCount              = 0;
        private $mConvIn                    = 0;
        private $mblnRound                  = false;
        private $mAddRound                  = false;
        private $sZeros                     = '';
        private $sRoundedNum                = '';
        private $mblnDN                     = false;
        private $mblnSetBreak               = false;
        private $mblnCurrencyText           = false;
        private $mblnDisplayOnly            = false;
        private $mAddCurrencyText           = false;
        private $mDecimalTextBeforeOnly     = false;         
        
        function __construct() { 
            $this->mblnDisplayOnly = 0; 
            $this->mblnDN = 0; 
            $this->mConvIn = 0; 
            $this->mAddRound = 0; 
            $this->mblnRound = 0; 
            $this->mAddCurrencyText = 0; 
            $this->mDecimalTextBeforeOnly = 0;
            
            $this->mInWord = '';
            $this->sZeros = '';
            $this->sRoundedNum = '';
            $this->lMillionCount = 1;
            $this->mblnTeen = false;
            
            $this->OneS = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine');
            
            $this->BDigit = array(
                0=>'', 1=>'GK', 2=>'`yB', 3=>'wZb', 4=>'Pvi', 5=>'cuvP', 6=>'Qq', 7=>'mvZ', 8=>'AvU', 9=>'bq', 10=>'`k',
                11=>'GMvi', 12=>'evi', 13=>'‡Zi', 14=>'‡PŠÏ', 15=>'c‡bi', 16=>'‡lvj', 17=>'m‡Zi', 18=>'AvVvi', 19=>'Dwbk', 20=>'wek',
                21=>'GKzk', 22=>'evBk', 23=>'‡ZBk', 24=>'‡PvweŸk', 25=>'cuwPk', 26=>'QvweŸk', 27=>'mvZvk', 28=>'AvUvk', 29=>'DbwÎk', 30=>'wÎk',
                31=>'GKwÎk', 32=>'ewÎk', 33=>'‡ZwÎk', 34=>'‡PŠwÎk', 35=>'cqwÎk', 36=>'QwÎk', 37=>'mvBwÎk', 38=>'AvUwÎk', 39=>'DbPwjøk', 40=>'Pwjøk',
                41=>'GKPwjøk', 42=>'weqvwjøk', 43=>'‡ZZvwjøk', 44=>'Pzqvwjøk', 45=>'cqZvwjøk', 46=>'wQPwjøk', 47=>'mvZPwjøk', 48=>'AvUPwjøk', 49=>'DbcÂvk', 50=>'cÂvk',
                51=>'GKvbœ', 52=>'evqvbœ', 53=>'wZcvbœ', 54=>'Pzqvbœ', 55=>'cÂvbœ', 56=>'Qvàvbœ', 57=>'mvZvbœ', 58=>'AvUvbœ', 59=>'DblvU',60=>'lvU',
                61=>'GKlwÆ', 62=>'evlwÆ', 63=>'‡ZlwÆ', 64=>'‡PŠlwÆ', 65=>'cuqlwÆ', 66=>'‡QlwÆ', 67=>'mvZlwÆ', 68=>'AvUlwÆ', 69=>'DbmËi', 70=>'mËi',
                71=>'GKvËi', 72=>'evnvËi', 73=>'wZnvËi', 74=>'‡PvqvËi', 75=>'cuPvËi', 76=>'wQqvËi', 77=>'mvZvËi', 78=>'AvUvËi', 79=>'DbAvwk', 80=>'Avwk', 
                81=>'GKvwk', 82=>'weivwk', 83=>'wZivwk', 84=>'Pzivwk', 85=>'cuPvwk', 86=>'wQqvwk', 87=>'mvZvwk', 88=>'AvUvwk', 89=>'DbbeŸB', 90=>'beŸB',         
                91=>'GKvbeŸB', 92=>'weivbeŸB', 93=>'wZivbeŸB', 94=>'PzivbeŸB', 95=>'cuPvbeŸB', 96=>'wQqvbeŸB', 97=>'mvZvbeŸB', 98=>'AvUvbeŸB', 99=>'wbivbeŸB'
            );
            
            $this->Teens = array(
                0=>'', 1=>'Eleven', 2=>'Twelve', 3=>'Thirteen', 4=>'Fourteen', 5=>'Fifteen', 6=>'Sixteen', 7=>'Seventeen', 8=>'Eighteen', 9=>'Nineteen'
            );

            $this->Tens = array(
                0=>'', 1=>'Ten', 2=>'Twenty', 3=>'Thirty', 4=>'Forty', 5=>'Fifty', 6=>'Sixty', 7=>'Seventy', 8=>'Eighty', 9=>'Ninety'
            );
            $this->HundredsInTk = array(
                0=>'Hundred', 1=> 'Thousand', 2=>'Lac', 3=>'Core'
            ); 
            $this->BHundredsInTk = array(
                0=>'kZ', 1=>'nvRvi', 2=>'j¶', 3=>'‡KvwU'
            );
            $this->HundredsInDollar = array(
                0=>'Hundred', 1=>'Thousand', 2=>'Million', 3=>'Billion', 4=>'Trillion', 5=>'Quadrillion', 6=>'Quintillion', 
                7=>'Sextillion', 8=>'Septillion', 9=> 'Octillion', 10=>'Nonillion', 11=>'Decillion', 12=>'Zillion'
            );
        }

        public function GetRoundDecimal() {
            return $this->mblnRound;
        }
                    
        public function LetRoundDecimal($bRound =  false){
            $this->mblnRound = $bRound;
        }

        public function GetConvertIn() {
            return $this->mConvIn;
        }

        public function LetConvertIn($CI = 0){
            $this->mConvIn = $CI;
        }

        public function GetDisplayRound(){
            return  $this->mAddRound;
        }

        public function LetDisplayRound($bDR = false){
            $this->mAddRound = $bDR;
        }

        public function GetDisplayNegative() {
            return $this->mblnDN;
        }

        public function LetDisplayNegative($bDN = false) {
            $this->mblnDN = $bDN;
        }

        public function GetAddTextOnly() {
            return $this->mblnDisplayOnly;
        }

        public function LetAddTextOnly($bOnly = false) {
            $this->mblnDisplayOnly = $bOnly;
        }
       
        public function GetAddCurrencyText() {
            return $this->mAddCurrencyText;
        }

        public function LetAddCurrencyText($bAddCurText = false) {
            $this->mAddCurrencyText = $bAddCurText;
        }

        
        public function GetDecimalTextBeforeOnly() {
            return $this->mDecimalTextBeforeOnly;
        }

        public function LetDecimalTextBeforeOnly($DTBeforeOnly = false) {
            $this->mDecimalTextBeforeOnly = $DTBeforeOnly;
        }

        private function RoundUp(&$sRoundNum =''){
            $sTmp = '';
            $iRounded = 0;
            
            $iRounded = substr($sRoundNum, -1) + 1;
            $sTmp = substr($sRoundNum, 0, -1);
            if ($iRounded > 9){ 
                $sZeros = '0' . $sZeros;
            } else {
                $this->sRoundedNum = $sTmp.''.$iRounded;
                $sTmp = '';
            }
                
            if ($sTmp != '') {
                $this->RoundUp($sTmp);
            }
        }
        
        private function GetInWord($NewNumber = ''){ 
            static $TensCount   = 0;           
            $modResult          = 0;
            $curResult          = 0;
            $NumberLen          = 0;
            $Paisa              = '';
            $iPos               = 0;
            $sFirst             = '';
            $sSecond            = '';
            //
            
            while (substr($NewNumber, 0, 1) == '0') {
                if(substr($NewNumber, 0, 1) == '0'){
                    $NewNumber = substr($NewNumber, 1) ;
                } 
            }
                         
            $NumberLen = strlen($NewNumber);
            $TensCount = $NumberLen;
            switch($this->mConvIn) {
                case 0:
                    switch($TensCount) {
                        case 1: case 2: case 3:
                            $modResult = $NewNumber % pow(10, ($TensCount - 1));
                            $curResult = ($NewNumber-$modResult)/pow(10 , ($TensCount - 1));
                            break;
                            
                        case 4: case 5: case 6:
                            $modResult = $NewNumber % 1000;
                            $curResult = ($NewNumber-$modResult) / 1000;
                            break;
                            
                        case 7: case 8: case 9:
                            $modResult = $NewNumber % 1000000;
                            $curResult = ($NewNumber-$modResult) / 1000000;
                            break;
                            
                        case 10: case 11: case 12:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 9);
                            $sSecond = substr($NewNumber, -9);
                            $this->GetInWord($sFirst);
                            
                            if (trim($this->mInWord) != 'Zero'){
                                if (trim($this->mInWord) != '') {
                                    $this->mInWord .= ' '.$this->HundredsInDollar[3];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;
                        case 13: case 14: case 15:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 12);
                            $sSecond = substr($NewNumber, -12);
                            $this->GetInWord($sFirst);
                            
                            if (trim($this->mInWord) != 'Zero') {
                                if (trim($this->mInWord) != '') {
                                    $this->mInWord .= ' '.$this->HundredsInDollar[4];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;
                        case 16: case 17: case 18:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 15);
                            $sSecond = substr($NewNumber, -15);
                            $this->GetInWord($sFirst);
                            
                            if(trim($this->mInWord) != 'Zero'){
                                if (trim($this->mInWord) != '') {
                                    $this->mInWord .= ' '.$this->HundredsInDollar[5];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            
                        case 19: case 20: case 21:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 18);
                            $sSecond = substr($NewNumber, -18);
                            $this->GetInWord($sFirst);
                            if (trim($this->mInWord) != 'Zero') {
                                if (trim($this->mInWord) != '') {
                                    $this->mInWord .= ' '.$this->HundredsInDollar[6];
                                }
                            }    
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;
                        case 22: case 23: case 24:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 21);
                            $sSecond = substr($NewNumber, -21);
                            $this->GetInWord($sFirst);
                            if (trim($this->mInWord) != 'Zero') {
                                if(trim($this->mInWord) != '') {
                                    $this->mInWord .= ' '.$this->HundredsInDollar[7];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                           break;
                           return;
                        case 25: case 26: case 27:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 24);
                            $sSecond = substr($NewNumber, -24);
                            $this->GetInWord($sFirst);
                            if (trim($this->mInWord) != 'Zero'){
                                if (trim($this->mInWord) != ''){
                                    $this->mInWord .= ' '.$this->HundredsInDollar[8];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;
                        case 28: case 29: case 30:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 27);
                            $sSecond = substr($NewNumber, -27);
                            $this->GetInWord($sFirst);
                            if (trim($this->mInWord) != 'Zero') {
                                if (trim($this->mInWord) != '') {
                                    $this->mInWord .= ' '.$this->HundredsInDollar[9];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;
                        case 31: case 32: case 33:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 30);
                            $sSecond = substr($NewNumber, -30);
                            $this->GetInWord($sFirst);
                            if (trim($this->mInWord) != 'Zero'){
                                if (trim($this->mInWord) != '') {
                                    $this->mInWord .= ' '.$this->HundredsInDollar[10];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;
                        case 34: case 35: case 36:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 33);
                            $sSecond = substr($NewNumber, -33);
                            $this->GetInWord($sFirst);
                            if (trim($this->mInWord) != 'Zero'){
                                if (trim($this->mInWord) != ''){
                                    $this->mInWord .= ' '.$this->HundredsInDollar[11];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;
                            
                        default:    
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 36);
                            $sSecond = substr($NewNumber, -36);
                            $this->GetInWord($sFirst);
                            if (trim($this->mInWord) != 'Zero'){
                                if (trim($this->mInWord) != ''){
                                    $this->mInWord .= ' '.$this->HundredsInDollar[12];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;
                            
                    }
                    break;
                    
                case 1:
                    switch ($TensCount) {
                        case 1: case 2: case 3: case 4: case 6: 
                            $modResult = $NewNumber % pow(10, ($TensCount - 1));
                            $curResult = ($NewNumber-$modResult)/pow(10, ($TensCount - 1));
                            break;
                            
                        case 5: case 7:
                            $modResult = $NewNumber % pow(10, ($TensCount - 2));
                            $curResult = ($NewNumber-$modResult)/pow(10, ($TensCount - 2));
                            break;
                            
                        default:
                            $sFirst = substr($NewNumber, 0, strlen($NewNumber) - 7);
                            $sSecond = substr($NewNumber, -7);
                            
                            $this->GetInWord($sFirst);
                            if (trim($this->mInWord) != 'Zero'){
                                if (trim($this->mInWord) != ''){
                                    $this->mInWord .= ' '.$this->HundredsInTk[3];
                                }
                            }
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            break;
                            return;                        
                    }
/*                case 2
                    switch ( $TensCount
                        case 2
                            $curResult = $NewNumber
                        case 5, 7
                            $modResult = $NewNumber Mod 10 ^ ($TensCount - 2)
                            $curResult = $NewNumber \ 10 ^ ($TensCount - 2)
                            
                        case Is > 7
                            $sFirst = substr($NewNumber, strlen($NewNumber) - 7)
                            $sSecond = substr($NewNumber, 7)
                            
                            $this->GetInWord($sFirst);
                            $this->mInWord = trim($this->mInWord . ' ' . IIf(trim($this->mInWord) = 'Zero', '', IIf(trim($this->mInWord) = '', '', $BHundredsInTk(3))))
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            Exit Sub
                        case else
                            $modResult = $NewNumber Mod 10 ^ ($TensCount - 1)
                            $curResult = $NewNumber \ 10 ^ ($TensCount - 1)
                    }
                 case 3
                    switch ( $TensCount
        //                case 2
        //                    $curResult = $NewNumber
                        case 5, 7
                            $modResult = $NewNumber Mod 10 ^ ($TensCount - 2)
                            $curResult = $NewNumber \ 10 ^ ($TensCount - 2)
                            
                        case Is > 7
                            $sFirst = substr($NewNumber, strlen($NewNumber) - 7)
                            $sSecond = substr($NewNumber, 7)
                            
                            $this->GetInWord($sFirst);
                            $this->mInWord = trim($this->mInWord . ' ' . IIf(trim($this->mInWord) = 'Zero', '', IIf(trim($this->mInWord) = '', '', $BHundredsInTk(3))))
                            if ($sSecond != 0) {
                                $this->GetInWord($sSecond);
                            }
                            Exit Sub
                        case else
                            $modResult = $NewNumber Mod 10 ^ ($TensCount - 1)
                            $curResult = $NewNumber \ 10 ^ ($TensCount - 1) 
                    }                                                      */
            };
                    
            switch ($TensCount) {
                case 1:
                    if ($this->mblnTeen == false) {
                        if ($this->mConvIn != 2) {
                            if (substr($this->mInWord, -1) == '-') {
                                $this->mInWord = trim($this->mInWord.$this->OneS[$curResult]);
                            } else {
                                $this->mInWord = trim($this->mInWord.' '.$this->OneS[$curResult]);
                            }
                        } else {
                            if (substr($this->mInWord, -1) == '-') {
                                $this->mInWord = trim($this->mInWord.$this->BDigit[$curResult]);
                            } else {
                                $this->mInWord = trim($this->mInWord.' '.$this->BDigit[$curResult]);
                            }
                        }
                        
                    }
                    break;
                    
                case 2:
                    if ($this->mConvIn != 2) {
                        if (($curResult == 1) && ($modResult >= 1)) {
                            $this->mInWord = trim($this->mInWord.' '.$this->Teens[$modResult]);
                            $this->mblnTeen = true;
                        } else {
                            $this->mInWord = trim($this->mInWord.' '.$this->Tens[$curResult]);
                            if ($modResult > 0) {
                                $this->mInWord = trim($this->mInWord.'-');
                            }
                        }
                    } else {
                        $this->mInWord = trim($this->mInWord.' '.$this->BDigit[$curResult]);
                        if ($modResult > 0) {
                            $this->mInWord = trim($this->mInWord.'-');
                        }
                    }
                    break;
                    
                default:
                    if ($curResult != 0) {
                        $TensCount = $TensCount - 2;
                        $this->GetInWord($curResult);
                        $this->mblnTeen = false;
                    }
                    $strHun = '';
                    switch ($this->mConvIn) {
                        case 0:
                            switch ($NumberLen) {
                                case 3:
                                    $strHun = $this->HundredsInDollar[0];
                                    break;
                                    
                                case 4: case 5: case 6:
                                    $strHun = $this->HundredsInDollar[1];
                                    break;
                                    
                                case 7: case 8: case 9:
                                    $strHun = $this->HundredsInDollar[2];
                                    break;
                                    
                                default:
                                    $strHun = '';
                                    break;
                            }
                            break;
                        case 1:
                            switch ($NumberLen) {
                                case 3:
                                    $strHun = $this->HundredsInTk[0];
                                    break;
                                    
                                case 4: case 5:
                                    $strHun = $this->HundredsInTk[1];
                                    break;
                                    
                                case 6: case 7:
                                    $strHun = $this->HundredsInTk[2];
                                    break;
                                    
                                case Is >= 8:                                    
                                    if (trim($this->mInWord) != 'Zero') {
                                        if (trim($this->mInWord) != ''){
                                            $strHun = $this->HundredsInTk[3];
                                        }
                                    }
                            }
                            break;
                            
                        case 2:
                            switch ($NumberLen) {
                                case 3:
                                    $strHun = $this->BHundredsInTk[0];
                                    break;
                                    
                                case 4: case 5:
                                    $strHun = $this->BHundredsInTk[1];
                                    break;
                                    
                                case 6: case 7:
                                    $strHun = $this->BHundredsInTk[2];
                                    break;
                                    
                                case Is >= 8:
                                    if (trim($this->mInWord) != 'Zero') {
                                        If (trim($this->mInWord) != '') {                                        
                                            $strHun = $this->BHundredsInTk[3];
                                        }
                                    }
                            }
                    }
                    $this->mInWord = trim($this->mInWord.' '.$strHun);
            }
            
            if ($modResult != 0) {
                $TensCount = $TensCount - 1;
                $this->GetInWord($modResult);
                $this->mblnTeen = false;
            }
        }

        public Function GetInText($sNumber = '') {
            switch ($this->mConvIn) {
                case 0:
                    $this->SetNumberUS($sNumber);
                    break;
                case 1:
                    $this->SetNumberTkEng($sNumber);
                    break;
/*                case 2:
                    $this->SetNumberTkBng($sNumber)
                case 3:
                    $this->SetNumberMath($sNumber)
*/            
            }
            return $this->mInWord;
        }

        private Function SetNumberUS($NewNumber = ''){
            $iPos           = 0;
            $sDecimal       = '';
            $iDecimal       = 0; 
            $sTmp           = '';
                      
            if ($NewNumber == '') {
                $this->mInWord = '';
                exit();
            }
            $this->mblnTeen         = false;
            $this->mInWord          = '';
            $sRoundedNum            = '';
            $sZeros                 = '';
            $lMillionCount          = 0;
            
            while (substr($NewNumber, 0, 1) == '0') {
                if(substr($NewNumber, 0, 1) == '0'){
                    $NewNumber = substr($NewNumber, 1) ;
                } 
            }
            
            if (strlen($NewNumber) > 32767) {
                $this->mInWord = 'Too learge number;';
                exit();
            }
            
            if (substr($NewNumber, 1) == '-' ||
                substr($NewNumber, 1) == '+' ||
                substr($NewNumber, 1) == '.') {
                    if (strlen($NewNumber) == 1) {
                        exit();
                    }
            }
                
            if (!((substr($NewNumber, 1) == '-' ||
                substr($NewNumber, 1) == '+' ||
                substr($NewNumber, 1) == '.'))) {
                if (!is_numeric(substr($NewNumber, 1))) {
                    $this->mInWord = 'Invalid number;';
                    exit();
                }
            else
                if (strlen($NewNumber) > 1) {
                    if (substr($NewNumber, 2, 1) != '.') {
                        if (!is_numeric(substr($NewNumber, 2, -1))) {
                            $this->mInWord = 'Invalid number;';
                            exit();
                        }
                    }
                }
            }
            
            if (strlen($NewNumber) > 1) {
                if (!is_numeric(substr($NewNumber, 1))) {
                    if (substr($NewNumber, 1) != '.') {
                       $this->mInWord = 'Invalid number;';
                       exit();
                    }
                }
            }
            
            
            while (substr($NewNumber, 0) == '0') {
                if(substr($NewNumber, 0) == '0'){
                    $NewNumber = substr($NewNumber, 1) ;
                } 
            }
            if ($NewNumber == '') exit();
            
            $mblnSetBreak = false;
            if (substr($NewNumber, 1) == '-') {
                if ($mblnDN = true) {
                    $mblnSetBreak = true;
                }
            }
            if (substr($NewNumber, 1) == '-') {
                $NewNumber = substr($NewNumber, 1);
            }
            if (substr($NewNumber, 1) == '+') {
                $NewNumber = substr($NewNumber, 1);
            }
            
            $iPos = strpos($NewNumber, '-');
            if ($iPos !== false) {
                if ($iPos !== 0) {
                    $this->mInWord = 'Invalid number;';
                    exit();
                }
            }
            $iPos = strpos($NewNumber, '+');
            if ($iPos !== false) {
                if ($iPos !== 0) {
                    $this->mInWord = 'Invalid number;';
                    exit();
                }
            }
            
            
            $iPos = strpos($NewNumber, '.');
            if ($iPos !== false) {
                $sDecimal = substr($NewNumber, $iPos + 1, 2);
                if (strlen($sDecimal) == 1) {
                    $sDecimal = $sDecimal.'0';
                }
                $NewNumber = substr($NewNumber, 0, $iPos);
                
                $iDecimal = (int) ($sDecimal);
                if ($this->mblnRound) {
                    if ($sDecimal >= 50) {
                        if ($NewNumber != '') {
                            $this->RoundUp($NewNumber);
                        }
                        if ($this->sRoundedNum == '') {$this->sRoundedNum = '1';}
                        $NewNumber = $this->sRoundedNum.$sZeros;
                        $sDecimal = 100 - ($sDecimal);
                    }
                }
                else {
                    if (strlen($sDecimal) == 1) {
                        $sDecimal = $sDecimal.'0';
                    }
                    $NewNumber = substr($NewNumber, 0, $iPos);
                }
            }

            if (strlen($NewNumber) != 0) {
                $this->GetInWord($NewNumber);
                if ($mAddCurrencyText) {
                    $sTmp = 'US$ ';
                }
                else {
                    $sTmp = '';
                }
                $sTmp = $sTmp.trim($this->mInWord);
            }
            else {
                if ($sDecimal == 0) {
                    if ($this->mAddCurrencyText) {
                        $this->mInWord = 'US$ ';
                    }
                    else {
                        $this->mInWord = '';
                    }
                    $this->mInWord = $this->mInWord.'Zero';
                    
                    if ($this->mblnDisplayOnly) {
                        $this->mInWord = $this->mInWord.' Only.';
                    }
                    exit();
                }
            }
            
            $this->mInWord = '';
            if ($sDecimal != 0) {
                $this->GetInWord($sDecimal);
            }
            if ($sTmp == '' && $this->mInWord == '') {exit();}
            
            if ($this->mInWord == '') {
                if ($sTmp != '') {
                    if ($this->mblnDisplayOnly) {
                        $sTmp = $sTmp.' Only.';
                    }
                }
            }
            else {
                if (trim($this->mInWord) != 'Zero') {
                    if ($this->mblnRound) {
                        if ($this->mAddRound) {
                            if ($sTmp == '') {
                                if ($this->mAddCurrencyText) {
                                    $sTmp = 'US$ ';
                                }
                                else {
                                    $sTmp = '';
                                }
                                $sTmp = $sTmp.'Zero';
                                if ($this->mblnDisplayOnly) {
                                    $sTmp = $sTmp.' Only';
                                }
                                $sTmp = $sTmp.' ('.trim($this->mInWord);
                                $sTmp = $sTmp.' Cent';
                                $sTmp = $sTmp.' Rounded '.(($iDecimal >= 50)? 'Up)':'Down)');
                            }
                            else {
                                if ($this->mblnDisplayOnly) {
                                    $sTmp .= ' Only';
                                }
                                $sTmp .= ' ('.trim($this->mInWord);
                                if ($this->mAddCurrencyText){                                    
                                    $sTmp .=' Cent';                                                                        
                                }
                                $sTmp .=' Rounded '.(($iDecimal >= 50)? 'Up)':'Down)');
                            }
                        }
                        else {
                            if ($sTmp == '') {
                                if ($this->mAddCurrencyText) {
                                    $sTmp = 'US$ ';
                                }
                                else {
                                    $sTmp = '';
                                }
                                $sTmp = $sTmp.'Zero'.(($this->mblnDisplayOnly)?' Only':'');
                            }
                            else {
                                $sTmp = $sTmp.(($this->mblnDisplayOnly)? ' Only.':'');
                            }
                        }
                    }
                    else {
                        if ($sTmp == '') {
                            if ($this->mDecimalTextBeforeOnly) {
                                $sTmp .=trim($this->mInWord);
                                if ($this->mAddCurrencyText) {
                                    if ($this->mConvIn = 1) {
                                        $sTmp .=' Paisa ';
                                    } else {
                                        $sTmp .=' Cent ';
                                    }
                                    if ($this->mblnDisplayOnly){
                                        $sTmp .= 'Only';
                                    }
                                }
                            }
                        }
                        else {                            
                            $sTmp .=(($this->mAddCurrencyText)? ' And ':' Point ');
                            if ($mDecimalTextBeforeOnly) {
                                if ($this->mAddCurrencyText) {
                                    $sTmp .= trim($this->mInWord).' Cent ';
                                }
                                $sTmp .=  (($this->mblnDisplayOnly)? 'Only.':'');
                            }
                            else {
                                if ($this->mAddCurrencyText) {
                                    $sTmp .= trim($this->mInWord).' Cent ';
                                }
                                else {
                                    $sTmp .= trim($this->mInWord);
                                }
                                $sTmp .= (($this->mblnDisplayOnly)? 'Only.':'');
                            }
                        }
                    }
                }
            }
            if ($mblnSetBreak == true) {
                $sTmp = '(' . $sTmp . ')';
            }
            $this->mInWord = $sTmp;            
        }

        private Function SetNumberTkEng($NewNumber = '') {
            $iPos               = 0 ;
            $sDecimal           = '';
            $iDecimal           = 0;
            $sTmp               ='';                       
            
            if ($NewNumber == '') {
                $this->mInWord = '';
                exit();
            }
            $this->mblnTeen     = false;
            $this->mInWord      = '';
            $sRoundedNum        = '';
            $sZeros             = '';
            $lMillionCount      = 0;
            
            if (strlen($NewNumber) > 32767) {
                $this->mInWord = 'Learge number';
                exit();
            }
            
            if (substr($NewNumber, 1) == '-' ||
                substr($NewNumber, 1) == '+' ||
                substr($NewNumber, 1) == '.') {
                if (strlen($NewNumber) == 1) {
                    $this->mInWord = 'Invalid Number';
                    exit();
                }
            }
                
            if (!(substr($NewNumber, 1) == '-' ||
                substr($NewNumber, 1) == '+' ||
                substr($NewNumber, 1) == '.')) {
                if (!is_numeric(substr($NewNumber, 1))) {
                    $this->mInWord = 'Invalid Number';
                    exit();
                }
            } else {
                if (strlen($NewNumber) > 1) {
                    if (substr($NewNumber, 2, 1) != '.') {
                        if (!is_numeric(substr($NewNumber, 2, 1))) {
                            $this->mInWord = 'Invalid Number';
                            exit();
                        }
                    }
                }
            }
            
            if (strlen($NewNumber) > 1) {
                if (!is_numeric(substr($NewNumber, 1))) {
                    if (substr($NewNumber, 1) != '.') {
                        $this->mInWord = 'Invalid Number';
                        exit();
                    }
                }
            }
            
            if ($NewNumber == '') {Exit();}
            
            while (substr($NewNumber, 0, 1) == '0') {
                if(substr($NewNumber, 0, 1) == '0'){
                    $NewNumber = substr($NewNumber, 1);
                } 
            }
            
            if ($NewNumber == '') {Exit();} 
            
            $this->mblnSetBreak = false;
            if (substr($NewNumber, 1) == '-') {
                if ($this->mblnDN == true) {
                    $this->mblnSetBreak = true;
                }
            }
            if (substr($NewNumber, 1) == '-') {
                $NewNumber = substr($NewNumber, 1);
            }
            if (substr($NewNumber, 1) == '+') {
                $NewNumber = substr($NewNumber, 1);
            }
            
            $iPos = strpos($NewNumber, '-');
            if ($iPos !== false) {
                if ($iPos !== 0) {
                    $this->mInWord = 'Invalid number;';
                    exit();
                }
            }
            $iPos = strpos($NewNumber, '+');
            if ($iPos !== false) {
                if ($iPos !== 0) {
                    $this->mInWord = 'Invalid number;';
                    exit();
                }
            }
            
            
            $iPos = strpos($NewNumber, '.');
            if ($iPos !== false) {
                $sDecimal = substr($NewNumber, $iPos + 1, 2);
                if (strlen($sDecimal) == 1) {
                    $sDecimal = $sDecimal.'0';
                }
                $NewNumber = substr($NewNumber, 0, $iPos);
                
                $iDecimal = (int) ($sDecimal);
                if ($this->mblnRound) {
                    if ($sDecimal >= 50) {
                        if ($NewNumber != '') {
                            $this->RoundUp($NewNumber);
                        }
                        if ($this->sRoundedNum == '') {$this->sRoundedNum = '1';}
                        $NewNumber = $this->sRoundedNum.$sZeros;
                        $sDecimal = 100 - ($sDecimal);
                    }
                }
                else {
                    if (strlen($sDecimal) == 1) {
                        $sDecimal = $sDecimal.'0';
                    }
                    $NewNumber = substr($NewNumber, 0, $iPos);
                }
            }

            if (strlen($NewNumber) != 0) {
                $this->GetInWord($NewNumber);
                if ($this->mAddCurrencyText) {
                    $sTmp = 'Tk ';
                }
                else {
                    $sTmp = '';
                }
                $sTmp = $sTmp.trim($this->mInWord);
            }
            else {
                if ($sDecimal == 0) {
                    if ($this->mAddCurrencyText) {
                        $this->mInWord = 'Tk ';
                    }
                    else {
                        $this->mInWord = '';
                    }
                    $this->mInWord = $this->mInWord.'Zero';
                    
                    if ($this->mblnDisplayOnly) {
                        $this->mInWord .= ' Only.';
                    }
                   exit();
                }
            }
            
            $this->mInWord = '';
            if ($sDecimal != 0) {
                $this->GetInWord($sDecimal);
            }
            if (($sTmp == '') && ($this->mInWord == '')) {exit();}
            
            if ($this->mInWord == '') {
                if ($sTmp != '') {
                    if ($this->mblnDisplayOnly) {
                        $sTmp .= ' Only.';
                    }
                }
            }
            else {
                if (trim($this->mInWord) != 'Zero') {
                    if ($this->mblnRound) {
                        if ($this->mAddRound) {
                            if ($sTmp == '') {
                                if ($this->mAddCurrencyText) {
                                    $sTmp = 'Tk ';
                                } else {
                                    $sTmp = '';
                                }
                                $sTmp = $sTmp.'Zero';
                                if ($this->mblnDisplayOnly) {
                                    $sTmp = $sTmp.' Only';
                                }
                                $sTmp .= ' ('.trim($this->mInWord);
                                $sTmp .= ' Paisa';
                                $sTmp .= ' Rounded '.(($iDecimal >= 50)? 'Up)':'Down)');
                            } else {
                                if ($this->mblnDisplayOnly) {
                                    $sTmp .= ' Only';
                                }
                                $sTmp .= ' ('.trim($this->mInWord);
                                if ($this->mAddCurrencyText) {
                                    $sTmp .= ' Paisa';
                                }
                                $sTmp .= ' Rounded '.(($iDecimal >= 50)? 'Up)':'Down)');
                            }
                        } else {
                            if ($sTmp == '') {
                                if ($this->mAddCurrencyText) {
                                    $sTmp = 'Tk ';
                                } else {
                                    $sTmp = '';
                                }
                                $sTmp .= 'Zero'.($this->mblnDisplayOnly? ' Only':'');
                            } else {
                                $sTmp .= ($this->mblnDisplayOnly? ' Only.': '');
                            }
                                
                        }
                    } else {
                        if ($sTmp == '') {
                            if ($this->mDecimalTextBeforeOnly) {                                
                                $sTmp = trim($this->mInWord);
                                if ($this->mAddCurrencyText){
                                    $sTmp .= ' Paisa ';
                                }
                                if ($this->mblnDisplayOnly) {
                                    $sTmp .= ' Only';
                                }
                            }
                        } else {
                            $sTmp .= (($this->mAddCurrencyText)? ' And ':' Point ');
                            if ($this->mDecimalTextBeforeOnly) {
                                if ($this->mAddCurrencyText) {
                                    $sTmp .= trim($this->mInWord) .' Paisa ';
                                } else {
                                    $sTmp .= trim($this->mInWord).' ';
                                }
                                $sTmp .= (($this->mblnDisplayOnly)? 'Only.':'');
                            } else {
                                if ($this->mAddCurrencyText) {
                                    $sTmp .= trim($this->mInWord).' Paisa ';
                                } else {
                                    $sTmp .= trim($this->mInWord).' ';
                                }
                                $sTmp .= (($this->mblnDisplayOnly)? 'Only.':'');
                            }
                        }
                    }
                }
            }
            if ($this->mblnSetBreak == true) {
                $sTmp = '('.$sTmp.')';
            }
            $this->mInWord = $sTmp;           
        }

/*        private Function SetNumberTkBng(ByVal $NewNumber As String)
            Dim $iPos        As Integer
            Dim $sDecimal    As String
            Dim $iDecimal    As Integer
            Dim $sTmp        As String
            
            On Error GoTo ErrHandle
            
            if $NewNumber = '' {
                $this->mInWord = ''
                Exit Function
            }
            $this->mblnTeen = false
            $this->mInWord = ''
            $sRoundedNum = ''
            $sZeros = ''
            $lMillionCount = 0
            
            if strlen($NewNumber) > 32767 {
                Err.Raise LARGE_NUMBER, 'Set Number', ERROR_LARGE_NUMBER
            }
            
            if (substr($NewNumber, 1) = '-' Or _
                substr($NewNumber, 1) = '+' Or _
                substr($NewNumber, 1) = '.') {
                if strlen($NewNumber) = 1 {
                    Exit Function
                }
            }
                
            if Not (substr($NewNumber, 1) = '-' Or _
                substr($NewNumber, 1) = '+' Or _
                substr($NewNumber, 1) = '.') {
                if Not is_numeric(substr($NewNumber, 1)) {
                    Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
                }
            else
                if strlen($NewNumber) > 1 {
                    if substr($NewNumber, 2, 1) != '.' {
                        if Not is_numeric(substr($NewNumber, 2, 1)) {
                            Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
                        }
                    }
                }
            }
            
            if strlen($NewNumber) > 1 {
                if Not is_numeric(substr($NewNumber, 1)) {
                    if substr($NewNumber, 1) != '.' {
                        Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
                    }
                }
            }
            
            if $NewNumber = '' { Exit Function
            Do While substr($NewNumber, 1) = '0'
                $NewNumber = IIf(substr($NewNumber, 1) = '0', substr($NewNumber, strlen($NewNumber) - 1), $NewNumber)
            Loop
            if $NewNumber = '' { Exit Function
            
            $mblnSetBreak = false
            if substr($NewNumber, 1) = '-' {
                if $mblnDN = true {
                    $mblnSetBreak = true
                }
            }
            $NewNumber = IIf(substr($NewNumber, 1) = '-', substr($NewNumber, strlen($NewNumber) - 1), $NewNumber)
            $NewNumber = IIf(substr($NewNumber, 1) = '+', substr($NewNumber, strlen($NewNumber) - 1), $NewNumber)
            
            $iPos = InStr($NewNumber, '-')
            if $iPos != 0 {
                Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
            }
            $iPos = InStr($NewNumber, '+')
            if $iPos != 0 {
                Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
            }
            
            if strlen($NewNumber) < 300 {
                if substr($NewNumber, 1) != '.' {
                    if Not is_numeric($NewNumber) {
                        Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
                    }
                }
            }
            
            $iPos = InStr($NewNumber, '.')
            if $iPos != 0 {
                $sDecimal = substr($NewNumber, $iPos + 1, 2)
                if strlen($sDecimal) = 1 {
                    $sDecimal = $sDecimal . '0'
                }
                $NewNumber = substr($NewNumber, $iPos - 1)
                
                $iDecimal = Val($sDecimal)
                if $mblnRound {
                    if Val($sDecimal) >= 50 {
                        if $NewNumber != '' {
                            Call RoundUp($NewNumber)
                        }
                        if $sRoundedNum = '' { $sRoundedNum = '1'
                        $NewNumber = $sRoundedNum . $sZeros
                        $sDecimal = 100 - Val($sDecimal)
                    }
                else
                    if strlen($sDecimal) = 1 {
                        $sDecimal = $sDecimal . '0'
                    }
                    $NewNumber = substr($NewNumber, $iPos - 1)
                }
            }

            if strlen($NewNumber) != 0 {
                Call GetInWord($NewNumber)
                if $mAddCurrencyText {
                    $sTmp = 'UvKv'
                else
                    $sTmp = ''
                }
                if $mDecimalTextBeforeOnly {
                    $sTmp = trim($this->mInWord) . ' ' . $sTmp
                else
                    $sTmp = $sTmp . ' ' . trim($this->mInWord)
                }
            else
                if Val($sDecimal) = 0 {
                    if $mAddCurrencyText {
                        $this->mInWord = 'UvKv '
                    else
                        $this->mInWord = ''
                    }
                    $this->mInWord = 'k~Y¨ ' . $this->mInWord
                    if $mblnDisplayOnly {
                        $this->mInWord = $this->mInWord . ' ' . 'gvÎ'
                    }
                    Exit Function
                }
            }
            
            $this->mInWord = ''
            if Val($sDecimal) != 0 {
                Call GetInWord($sDecimal)
            }
            if $sTmp = '' And $this->mInWord = '' { Exit Function
            
            if $this->mInWord = '' {
                if $sTmp != '' {
                    if $mblnDisplayOnly {
                        $sTmp = $sTmp . ' gvÎ'
                    }
                }
            else
                if trim($this->mInWord) != 'k~Y¨' {
                    if $mblnRound {
                        if $mAddRound {
                            $sTmp = $sTmp . IIf($mblnDisplayOnly, ' gvÎ', '') . ' (Avmbœ gvb)'
                        else
                            if $sTmp = '' {
                                $sTmp = $sTmp . 'k~Y¨' . IIf($mblnDisplayOnly, ' gvÎ', '')
                            else
                                $sTmp = $sTmp . IIf($mblnDisplayOnly, ' gvÎ', '')
                            }
                        }
                    else
                        if $sTmp = '' {
                            if $mDecimalTextBeforeOnly {
                                $sTmp = trim($this->mInWord) . IIf($mAddCurrencyText, ' cqmv ', '') . IIf($mblnDisplayOnly, 'gvÎ', '')
                            else
                                $sTmp = IIf($mAddCurrencyText, ' cqmv ', '') . trim($this->mInWord) . 'gvÎ'
                            }
                        else
                            $sTmp = $sTmp . ' Ges'
                            if $mDecimalTextBeforeOnly {
                                if $mAddCurrencyText {
                                    $sTmp = $sTmp . ' ' . trim($this->mInWord) . ' cqmv '
                                }
                                $sTmp = $sTmp . IIf($mblnDisplayOnly, 'gvÎ', '')
                            else
                                if $mAddCurrencyText {
                                    $sTmp = $sTmp . ' ' . trim($this->mInWord) . ' cqmv '
                                else
                                    $sTmp = $sTmp . trim($this->mInWord)
                                }
                                $sTmp = $sTmp . IIf($mblnDisplayOnly, ' gvÎ', '')
                            }
                        }
                    }
                }
            }
            if $mblnSetBreak = true {
                $sTmp = '(' . $sTmp . ')'
            }
            $this->mInWord = $sTmp           
        }

        public function Get BanglaFont() As String
            BanglaFont = m_BanglaFont
        }

        public function Get BFontSize() As Double
            BFontSize = m_BFontSize
        }

        public function Let BanglaFont(ByVal vData As String)
            m_BanglaFont = vData
        }

        public function Let BFontSize(ByVal vData As Double)
            m_BFontSize = vData
        }


        private Function SetNumberMath(ByVal $NewNumber As String)
            Dim $iPos        As Integer
            Dim $sDecimal    As String
            Dim $iDecimal    As Integer
            Dim $sTmp        As String
            
            On Error GoTo ErrHandle
            
            if $NewNumber = '' {
                $this->mInWord = ''
                Exit Function
            }
            $this->mblnTeen = false
            $this->mInWord = ''
            $sRoundedNum = ''
            $sZeros = ''
            $lMillionCount = 0
            
            if strlen($NewNumber) > 32767 {
                Err.Raise LARGE_NUMBER, 'Set Number', ERROR_LARGE_NUMBER
            }
            
            if (substr($NewNumber, 1) = '-' Or _
                substr($NewNumber, 1) = '+' Or _
                substr($NewNumber, 1) = '.') {
                if strlen($NewNumber) = 1 {
                    Exit Function
                }
            }
                
            if Not (substr($NewNumber, 1) = '-' Or _
                substr($NewNumber, 1) = '+' Or _
                substr($NewNumber, 1) = '.') {
                if Not is_numeric(substr($NewNumber, 1)) {
                    Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
                }
            else
                if strlen($NewNumber) > 1 {
                    if substr($NewNumber, 2, 1) != '.' {
                        if Not is_numeric(substr($NewNumber, 2, 1)) {
                            Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
                        }
                    }
                }
            }
            
            if strlen($NewNumber) > 1 {
                if Not is_numeric(substr($NewNumber, 1)) {
                    if substr($NewNumber, 1) != '.' {
                        Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
                    }
                }
            }
            
            if $NewNumber = '' { Exit Function
            
            Do While substr($NewNumber, 1) = '0'
                $NewNumber = IIf(substr($NewNumber, 1) = '0', substr($NewNumber, strlen($NewNumber) - 1), $NewNumber)
            Loop
            if $NewNumber = '' { Exit Function
            
            $mblnSetBreak = false
            if substr($NewNumber, 1) = '-' {
                if $mblnDN = true {
                    $mblnSetBreak = true
                }
            }
            $NewNumber = IIf(substr($NewNumber, 1) = '-', substr($NewNumber, strlen($NewNumber) - 1), $NewNumber)
            $NewNumber = IIf(substr($NewNumber, 1) = '+', substr($NewNumber, strlen($NewNumber) - 1), $NewNumber)
            
            $iPos = InStr($NewNumber, '-')
            if $iPos != 0 {
                Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
            }
            $iPos = InStr($NewNumber, '+')
            if $iPos != 0 {
                Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
            }
            
            if strlen($NewNumber) < 300 {
                if substr($NewNumber, 1) != '.' {
                    if Not is_numeric($NewNumber) {
                        Err.Raise INVALID_NUMBER_FORMAT, 'Set Number', ERROR_INVALID_NUMBER
                    }
                }
            }
            
            $iPos = InStr($NewNumber, '.')
            if $iPos != 0 {
                $sDecimal = substr($NewNumber, strlen($NewNumber) - $iPos)
        //        if strlen($sDecimal) = 1 {
        //            $sDecimal = $sDecimal . '0'
        //        }
                $NewNumber = substr($NewNumber, $iPos - 1)
                
                $iDecimal = Val($sDecimal)
                if $mblnRound {
                    if Val($sDecimal) >= 50 {
                        if $NewNumber != '' {
                            Call RoundUp($NewNumber)
                        }
                        if $sRoundedNum = '' { $sRoundedNum = '1'
                        $NewNumber = $sRoundedNum . $sZeros
                        $sDecimal = 100 - Val($sDecimal)
                    }
                else
        //            if strlen($sDecimal) = 1 {
        //                $sDecimal = $sDecimal . '0'
        //            }
                    $NewNumber = substr($NewNumber, $iPos - 1)
                }
            }
            Dim i       As Integer
            
            if strlen($NewNumber) != 0 {
                Call GetInWord($NewNumber)
        //        if $mAddCurrencyText {
        //            $sTmp = 'US$ '
        //        else
        //            $sTmp = ''
        //        }
                $sTmp = $sTmp . trim($this->mInWord)
            else
                if Val($sDecimal) = 0 {
                    if $mAddCurrencyText {
                        $this->mInWord = 'US$ '
                    else
                        $this->mInWord = ''
                    }
                    $this->mInWord = $this->mInWord . 'Zero'
                    
                    if $mblnDisplayOnly {
                        $this->mInWord = $this->mInWord . ' Only.'
                    }
                    Exit Function
                }
            }
            Dim sTmpDecimal     As String
            if strlen($NewNumber) != 0 {
                $this->mInWord = ''
            else
                if Val($sDecimal) != 0 {
                    $this->mInWord = 'Zero Point'
                else
                    $this->mInWord = ''
                }
            }
            
            if Val($sDecimal) != 0 {
                For i = 1 To strlen($sDecimal)
                    Call GetInWord(Mid($sDecimal, i, 1))
                Next
            }
            if $sTmp = '' And $this->mInWord = '' { Exit Function
            
            if $this->mInWord = '' {
                if $sTmp != '' {
                    if $mblnDisplayOnly {
                        $sTmp = $sTmp . ' Only.'
                    }
                }
            else
                if trim($this->mInWord) != 'Zero' {
                    if $mblnRound {
                        if $mAddRound {
                            if $sTmp = '' {
                                if $mAddCurrencyText {
                                    $sTmp = 'US$ '
                                else
                                    $sTmp = ''
                                }
                                $sTmp = $sTmp . 'Zero'
                                if $mblnDisplayOnly {
                                    $sTmp = $sTmp . ' Only'
                                }
                                $sTmp = $sTmp . ' (' . trim($this->mInWord)
                                $sTmp = $sTmp . ' Cent'
                                $sTmp = $sTmp . ' Rounded ' . IIf($iDecimal >= 50, 'Up)', 'Down)')
                            else
                                $sTmp = $sTmp . IIf($mblnDisplayOnly, ' Only', '') . ' (' . trim($this->mInWord) . IIf($mAddCurrencyText, IIf($mConvIn = 1, ' $Paisa', ' Cent'), '') . ' Rounded ' . IIf($iDecimal >= 50, 'Up)', 'Down)')
                            }
                        else
                            if $sTmp = '' {
                                if $mAddCurrencyText {
                                    $sTmp = 'US$ '
                                else
                                    $sTmp = ''
                                }
                                $sTmp = $sTmp . 'Zero' . IIf($mblnDisplayOnly, ' Only', '')
                            else
                                $sTmp = $sTmp . IIf($mblnDisplayOnly, ' Only.', '')
                            }
                        }
                    else
                        if $sTmp = '' {
                            $sTmp = IIf($mDecimalTextBeforeOnly, trim($this->mInWord) . IIf($mAddCurrencyText, IIf($mConvIn = 1, ' $Paisa ', ' Cent '), '') . IIf($mblnDisplayOnly, 'Only', ''), IIf($mAddCurrencyText, IIf($mConvIn = 1, ' $Paisa ', ' Cent '), '') . trim($this->mInWord) . IIf($mblnDisplayOnly, ' Only.', ''))
                        else
                            
                            $sTmp = $sTmp . IIf($mAddCurrencyText, ' And ', ' Point ')
                            if $mDecimalTextBeforeOnly {
                                if $mAddCurrencyText {
                                    $sTmp = $sTmp . trim($this->mInWord) . ' Cent '
                                }
                                $sTmp = $sTmp . IIf($mblnDisplayOnly, 'Only.', '')
                            else
                                if $mAddCurrencyText {
                                    $sTmp = $sTmp . trim($this->mInWord) . ' Cent '
                                else
                                    $sTmp = $sTmp . trim($this->mInWord)
                                }
                                $sTmp = $sTmp . IIf($mblnDisplayOnly, 'Only.', '')
                            }
                        }
                    }
                }
            }
            if $mblnSetBreak = true {
                $sTmp = '(' . $sTmp . ')'
            }
            $this->mInWord = $sTmp
            Exit Function
       
        } */
    }
    /*
    // End of class Number to Word
    */