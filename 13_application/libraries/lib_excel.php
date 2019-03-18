<?php 

class lib_excel{
		
	private $data;

	public function __construct(){
		require APPPATH.'libraries/Excel/reader.php';
		$this->data = new Spreadsheet_Excel_Reader();
		$this->data->setOutputEncoding('CP1251');
	}

	public function __get($var)
	{
		return get_instance()->$var;
	}
	
	public function production($path){
		$this->data->read($path);		

		$count = count($this->data->sheets[0]["cells"]);
		
		$cnt_column = 0;
		$row_group = array();
		foreach($this->data->sheets[0]["cells"] as $key=>$row){

			switch($key){
				case "2":
					$cnt_column = count($row);
				break;
				default:
					$row_1 = array();
					for ($i=1; $i <=$cnt_column; $i++){
							$row_1[] = (isset($row[$i]))? $row[$i] : 0;
					}
					$row_group[] = $row_1;
				break;
			}
		}

		return $row_group;		
		
	}
		

}



 ?>