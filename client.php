<?php
session_start();
require_once('lib/nusoap.php'); //include required class for build nnusoap web service server


$wsdl="http://localhost/Ajax_NamesWS/server.php?wsdl";  // SOAP Server
$client=new soapclient($wsdl) or die("Error");


$var1 = $_POST["var1"];

if($var1 =="cargar"){
    $response = $client->__call('allnames',array("var1")) or die("Error");  //Send two inputs strings. {1} DECODED CONTENT {2} FILENAME
    print(json_encode($response));
}
elseif ($var1 =="sugerenciasboys") {
    $response = $client->__call('namesSugerenciasBoys',array($_POST["var2"])) or die("Error");  //Send two inputs strings. {1} DECODED CONTENT {2} FILENAME
    echo $response;
}
elseif ($var1 =="sugerenciasgirls") {
    $response = $client->__call('namesSugerenciasGirls',array($_POST["var2"])) or die("Error");  //Send two inputs strings. {1} DECODED CONTENT {2} FILENAME
    print($response);
}
elseif ($var1 =="like") {
    $response = $client->__call('sumarLike',array($_POST["var2"])) or die("Error");  //Send two inputs strings. {1} DECODED CONTENT {2} FILENAME

}
elseif ($var1 =="dislike") {
    $response = $client->__call('restarLike',array($_POST["var2"])) or die("Error");  //Send two inputs strings. {1} DECODED CONTENT {2} FILENAME

}


     
     


