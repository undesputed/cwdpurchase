<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Populate_array {

	var $heading = array();
	var $body;
	var $hide    = array();
	var $column  = array();
	var $overide = array();


	public function __construct(){
			
	}

	public function __get($var){
		return get_instance()->$var;
	}


	function _row(){
		$arg = func_get_args();
		if(empty($arg)){
			return false;
		}
		$type_desc = array();
		$row_data  = array();		
		foreach($arg[0] as $row){			
				$row_data = array();
				$this->heading = array();
				foreach($row as $k=>$inner){
					if($k==$arg[1] && !is_numeric($k)){
						continue;						
					} 
					$row_data[$k] = $inner;	
					$this->heading[] = $k;
				}
				$type_desc[$row[$arg[1]]][]= $row_data;	
		}
		if(!empty($arg[2])){
			//array_unshift($type_desc, $arg[2][key($arg[2])]);
			$type_desc = array(key($arg[2])=> $arg[2][key($arg[2])]) + $type_desc;
			//$type_desc[key($arg[2])] = $arg[2][key($arg[2])];

		}		
		return $type_desc;

	}


	function _header(){

		$default = array(
			'style'=>'',
			'class'=>'',
			);
		$header = array();
		foreach ($this->heading as $key => $value){

			if(!empty($this->hide)){

				if(in_array($value, $this->hide)){
					$default['style'] = "display:none";					
				}else{
					$default['style'] = "";
				}
			}

			if (!empty($this->overide)) {
				if(array_key_exists($value, $this->overide)){
					$value = $this->overide[$value];
					$default['class'] = 'heading';
				}
			}


			$default['data'] = $value;
			$header[] = $default;

		}
		return $header;

	}

	function sub_header(){
		$arg = func_get_args();
		if(empty($arg)){
			return false;
		}
		if(!isset($arg[1])){
			$minus = (!empty($this->hide))? count($this->hide) : 0 ;
			$cnt   = count($this->heading) - $minus ;
		}else{
			$cnt = $arg[1];
		}
		
		$head_data = array();
		for ($i=0; $i < $cnt ; $i++) {
			switch ($i) {
				case 0:
					$head_data[] = $arg[0];
					break;
				
				default:
					$head_data[] = "";
					break;
			}
		}

		return $head_data;

	}

	function generate_row($row){
		$return_row = array();
		foreach ($row as $key => $value) {
			if(!empty($this->hide)){
				if(in_array($key, $this->hide)){
					$style = "display:none";
				}else{
					$style = "";
				}
			}
			if(array_key_exists($key, $this->populate_array->column)){
				if($this->populate_array->column[$key]=="TOTAL"){
					$value = "<span style='margin-left:7em;'></span> TOTAL";
				}else{
					$value = $value;
				}
			}
			$return_row[] = array('data'=>$value,'style'=>$style);

		}
		return $return_row;
	}




	








}