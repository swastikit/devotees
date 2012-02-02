<?php
class ComboController extends Zend_Controller_Action
{
    public function init()
    {
    }
    public function indexAction()
    {
    	$this->_helper->layout->disableLayout();
		$q=$_SERVER['QUERY_STRING'];
		$f=explode('&',$q);
		$output='';
		$contentType='';
		foreach ($f as $i => $value) {
			$file_name=$f[$i];
			if($i===0){
				$pos = strpos($file_name, '.css');
				if($pos>0){
					$contentType='text/css';
				}else{
					$pos = strpos($file_name, '.js');	
					if($pos > 0){
						$contentType='application/x-javascript';	
					}	
				}	
			}			
			$file=$_SERVER['DOCUMENT_ROOT']."/".$file_name;
			if (file_exists($file_name)) {
				$output=$output . file_get_contents($file);
			}			
		}
		$this->getResponse()
			->clearHeaders()
		    ->setHeader('Content-Type',$contentType)
		    ->appendBody($output);
    }
}

