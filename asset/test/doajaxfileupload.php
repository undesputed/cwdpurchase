<?php
	$error = "";
	$msg = "";
	$fileElementName = 'file';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{
			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = '. . .No file was uploaded.';
				break;
			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES['file']['tmp_name']) || $_FILES['file']['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded...';
	}else 
	{
			$msg .= " File Name: " . $_FILES['file']['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES['file']['tmp_name']);
			
			$dirname = '\_'.$_GET['did'];
			$path = str_replace('AjaxFileUploader','images',getcwd());
			if(!file_exists($path.$dirname)){
				mkdir($path.$dirname);
			}
			
			//include_once('../../php_lib/php_lib.php');
			//$app = new php_lib();
			//move_uploaded_file($_FILES['fileToUpload']['tmp_name'] , "../images/_".$_GET['did'].'/'.$_FILES['fileToUpload']['name']);
			//$app->mysql_query('update point_location_rpt set image = "upload_image/images/_'.$_GET['did'].'/'.$_FILES['fileToUpload']['name'].'" where td_id = '.$_GET['did']);
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES['fileToUpload']);
			
	}
	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "'\n";
	echo "}";	
?>