<?php 

require_once 'Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read('exceltestsheet.xls');
 
$conn = mysql_connect("hostname","username","password");
mysql_select_db("database",$conn);
 
for ($x = 2; $x < = count($data->sheets[0]["cells"]); $x++) {
    $name = $data->sheets[0]["cells"][$x][1];
    $extension = $data->sheets[0]["cells"][$x][2];
    $email = $data->sheets[0]["cells"][$x][3];
    $sql = "INSERT INTO mytable (name,extension,email) 
        VALUES ('$name',$extension,'$email')";
    echo $sql."\n";
    mysql_query($sql);
}

?>