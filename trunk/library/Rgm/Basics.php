<?php
class Rgm_Basics{
    /*
    Returns array of 12 months in the year.
    Used to populate Zend_Form_Element_Select for month field.
    */
    
    public function getMonths(){
        $months =array(
                 array('value'=>'Jan','key'=>'01'),
                 array('value'=>'Fab','key'=>'02'),
                 array('value'=>'Mar','key'=>'03'),
                 array('value'=>'Apr','key'=>'04'),
                 array('value'=>'May','key'=>'05'),
                 array('value'=>'Jun','key'=>'06'),
                 array('value'=>'Jul','key'=>'07'),
                 array('value'=>'Aug','key'=>'08'),
                 array('value'=>'Sep','key'=>'09'),
                 array('value'=>'Oct','key'=>'10'),
                 array('value'=>'Nov','key'=>'11'),
                 array('value'=>'Dec','key'=>'12')
            );
        return $months;
    }
    /*
    Returns array of 31 dates in a month.
    Used to populate Zend_Form_Element_Select for date field.
    */
    public function getDates(){
        $dates=array();
        for ($i=1; $i<=31; $i++){
            $date=array(0=>'Date') + array('value'=>($i<10?'0'.$i:$i),'key'=>($i<10?'0'.$i:$i));
            array_push($dates,$date);
        }
        return $dates;
    } 
    /*
    Returns array of years in YYYY format.
    Used to populate Zend_Form_Element_Select for year field.
    */
    public function getYears($fromYear,$toYear){
        $years=array();
        for ($i=$fromYear; $i<=$toYear; $i++){
            $year=array(0=>'Year') + array('value'=>$i,'key'=>$i);
            array_push($years,$year);
        }
        return $years;
    } 
    
    
    /*
    Returns array of different search options to search a devotee.
    Used to populate search select box in devotee list page.
    */
    public function getDevoteeSearchOptions(){
        $searchOptions =array(
            'LEGAL_NAME'=>'Legal Name',
            'INITIATED_NAME'=>'Initiated Name',
            'SEARCH_NAME'=>'Search Name',
            'EMAIL'=>'Email',
            'MOBILE'=>'Mobile',
            'DOB'=>'Dob',
            'PRES_ADDRESS'=>'Present Address',
            'PRES_CITY'=>'Present City',
            'PRES_STATE'=>'Present State',
            'PRES_COUNTRY'=>'Present Country',
            'PRES_ZIP'=>'Present Zipcode',
            'PERM_ADDRESS'=>'Permanent Address',
            'PERM_CITY'=>'Permanent City',
            'PERM_STATE'=>'Permanent State',
            'PERM_COUNTRY'=>'Permanent Country',
            'PERM_ZIP'=>'Permanent Zipcode',
            'PAN'=>'PAN'
        );
        return $searchOptions;
    }
    
    /*
    
    */
    
    public function getBloodGroupsAsoArr(){
        return array(
            'A +Ve'=>'A +Ve',
            'AB +Ve'=>'AB +Ve',
            'AB -Ve'=>'AB -Ve',
            'B +Ve'=>'B +Ve',
            'B -Ve'=>'B -Ve',
            'O +Ve'=>'O +Ve',
            'O -Ve'=>'O -Ve'
        );
    }
    public function getBloodGroupsIds(){
        return array('A +Ve','AB +Ve','AB -Ve','B +Ve','B -Ve','O +Ve','O -Ve');
    }
/*
Returns array of different counselling status options
Used to populate counselling status select box in the Add New devotee Form
*/
	public function encodeDiacritics($str){
		$retstr="";
		if($str==null)
			return $retstr;
        $j = mb_strlen($str);
        for ($k = 0; $k < $j; $k++) {
            $char = mb_substr($str, $k, 1);
			$ascii=ord($char);
			if (($ascii>=65) && ($ascii<=90))
				$retstr .= $ascii;
			else if($ascii==32)
				$retstr .= " ";
			else if (($ascii>=97) && ($ascii<=122))
				$retstr .= ($ascii-32);
			else if (($ascii>=48) && ($ascii<=57))
				$retstr .= ($ascii+7);
        }
		return $retstr;
	}

	public function removeDicritic($str)
	{
		$retstr="";
		if($str==null)
			return $retstr;
        $j = mb_strlen($str);
        for ($k = 0; $k < $j; $k++) {
            $char = mb_substr($str, $k, 1);
			$ascii=ord($char);
			if (($ascii>=65) && ($ascii<=90))
				$retstr .= $char;
			else if($ascii==32)
				$retstr .= " ";
			else if (($ascii>=97) && ($ascii<=122))
				$retstr .= $char;
			else if (($ascii>=48) && ($ascii<=57))
				$retstr .= $char;
        }
		return $retstr;
	}
}
?>
