<?php
	
	header("Content-type: application/json");
	
	$json			= file_get_contents("php://input");
	$obj 			= json_decode($json, true);
	
	//RECUPERER DATA
	$data 			= base64_decode(json_encode($obj["data"]));

	// RECUPERER rxinfo -> 0 -> rssi
	$rxInfo		= JsonEncode($obj, "rxInfo");
	write("TMP.txt", $rxInfo);
	$objRXINFO 		= getContent('TMP.txt');
	$objRXINFOv2 		= strReplace($objRXINFO);
	$objRXINFOv2decoded 	= json_decode($objRXINFOv2, true);
	$rssi			= JsonEncode($objRXINFOv2decoded, "rssi");
	clear('TMP.txt');
	
	
	//CONNEXION A PHPADMIN
	$server		= "localhost:3306";
	$username 		= "mysql";
	$password		= "sql";
	$DB			= "loraserverDB";
	
	$conn			= mysqli_connect($server, $username, $password, $DB);
	if (!$conn){
		die ("Connection failed :" .mysqli_connect_error());
	}
	
	echo "Connected successfully";
	//echo gettype($array['data']);
	
	
	$insert 	= "insert into `loraTable` (value, rssi) VALUES ('$data', '$rssi')";
	//$conn->query($insert);
	mysqli_query($conn, $insert);
	$conn->close();
	
	
function JsonEncode($obj, $string): string
{
	$encode = json_encode($obj[$string]);
	return $encode;
}

	
function write ($file, $toWrite) 
{
	$fp 		= @fopen($file, "a");
	ftruncate($fp, 0);
	fwrite($fp, $toWrite);	
	fclose($fp);
}

function clear ($file)
{
	$fp 		= @fopen($file, "a");
	ftruncate($fp, 0);
	fclose($fp);
}

function getContent($file): string 
{
	$fp		= @fopen($file, "a");
	$string		= file_get_contents($file);
	fclose($fp);
	return $string; 
} 

function strReplace($file): string 
{
	$string	= str_replace(array('[', ']'),'', $file);
	return $string;
}	
	
	
/*
	ligne 7//$rxInfo 		= json_encode($obj["rxInfo"]);
	
	$fp 		= @fopen('TXT.txt', "a");
	ftruncate($fp, 0);
	fwrite($fp,$data);			
	$line		= file_get_contents("TXT.txt");
	ftruncate($fp, 0);
	fclose($fp);
	
	write('TXT.txt', $data);
	$line		= file_get_contents("TXT.txt");
	clear('TXT.txt');
	
	
	$fp2 		= @fopen('TMP.txt', "a");
	ftruncate($fp2, 0);
	fwrite($fp2, $rxInfo);	
			
	$test0			= file_get_contents("TMP.txt");
	$test 			= str_replace(array('[', ']'),'', $test0);
	$obj2			= json_decode($test, true);
	$rssi 			= json_encode($obj2["rssi"]);
	ftruncate($fp2, 0);
	fwrite($fp2, $rssi);			
	fclose($fp2);	
*/
	
	
	
?>
