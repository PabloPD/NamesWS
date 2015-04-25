<?php
session_start();
require_once('lib/nusoap.php'); //include required class for build nnusoap web service server
require 'ModelPDO.php';

  // Create server object
   $server = new soap_server();

   // configure  WSDL
   $server->configureWSDL('practica namesws', 'urn:namesws');

   // Register the method to expose
    $server->register('allnames',                                 // method
        array('var1' => 'xsd:string'),    // input parameters
        array('return' => 'xsd:Array'),                             // output parameters
        'urn:namesws',                                            // namespace
        'urn:namesws#allnames',                                // soapaction
        'rpc',                                                       // style
        'encoded',                                                   // use
        'Comentarios'                                // documentation
    );
    
    $server->register('namesSugerenciasBoys',                                 // method
        array('var1' => 'xsd:string'),    // input parameters
        array('return' => 'xsd:string'),                             // output parameters
        'urn:namesws',                                            // namespace
        'urn:namesws#namesSugerenciasBoys',                                // soapaction
        'rpc',                                                       // style
        'encoded',                                                   // use
        'Comentarios'                                // documentation
    );
    
    
    $server->register('namesSugerenciasGirls',                                 // method
        array('var1' => 'xsd:string'),    // input parameters
        array('return' => 'xsd:string'),                             // output parameters
        'urn:namesws',                                            // namespace
        'urn:namesws#namesSugerenciasGirls',                                // soapaction
        'rpc',                                                       // style
        'encoded',                                                   // use
        'Comentarios'                                // documentation
    );
    
    $server->register('sumarLike',                                 // method
        array('var1' => 'xsd:string'),    // input parameters
        array('return' => 'xsd:string'),                             // output parameters
        'urn:namesws',                                            // namespace
        'urn:namesws#sumarLike',                                // soapaction
        'rpc',                                                       // style
        'encoded',                                                   // use
        'Comentarios'                                // documentation
    );
    
    $server->register('restarLike',                                 // method
        array('var1' => 'xsd:string'),    // input parameters
        array('return' => 'xsd:string'),                             // output parameters
        'urn:namesws',                                            // namespace
        'urn:namesws#restarLike',                                // soapaction
        'rpc',                                                       // style
        'encoded',                                                   // use
        'Comentarios'                                // documentation
    );
   

    // Define the method as a PHP function

    function allnames($var1) {
        
        $oDB = new ModelPDO();
        $oDB = $oDB->getDBO();
        $query = $oDB->query("Select * from nombres order by gusta desc, nombre asc");
        $id = $query->fetchAll();
        
        $_SESSION['arrayboys'] = Array();
        $_SESSION['arraygirls'] = Array();
        $names_boys = Array();
        $names_girls = Array();
        
        foreach ($id as $value) {
            $array = Array();
            array_push($array, $value['nombre']);
            array_push($array, $value['genero']);
            array_push($array, $value['gusta']);
            array_push($array, $value['nogusta']);
            
            if($value['genero']=="hombre"){
                array_push($names_boys, $array);
                array_push($_SESSION['arrayboys'], $value['nombre']);
            }
            else{
                array_push($names_girls, $array);
                array_push($_SESSION['arraygirls'], $value['nombre']);
            }
        }
        
        $total = Array();
        
        array_push($total, $names_boys);
        array_push($total, $names_girls);
        
        return $total;
    }
    
    
    function namesSugerenciasBoys($var1){
        
        if(!isset($_SESSION['arrayboys'])){
            
            $oDB = new ModelPDO();
            $oDB = $oDB->getDBO();
            $query = $oDB->query('SELECT nombre,genero FROM `nombres` where genero = "hombre" order by gusta desc, nombre asc');
            $id = $query->fetchAll();

            $_SESSION['arrayboys'] = Array();

            foreach ($id as $value) {
                if($value['genero']=="hombre"){

                    array_push($_SESSION['arrayboys'], $value['nombre']);
                }
            }
        }

        $hint="";
        if ($var1 !== "") {
            $var1 = strtolower($var1);
            $len=strlen($var1);
            foreach($_SESSION['arrayboys'] as $name) {
                if (stristr($var1, substr($name, 0, $len))) {
                    if ($hint === "") {
                        $hint = $name;
                    } else {
                        $hint .= ", $name";
                    }
                }
            }
        }
        
        return $hint === "" ? "no suggestion" : $hint;
        
    }
    
    
    function namesSugerenciasGirls($var1){
        
        if(!isset($_SESSION['arrayboys'])){
            
            $oDB = new ModelPDO();
            $oDB = $oDB->getDBO();
            $query = $oDB->query('SELECT nombre,genero FROM `nombres` where genero = "mujer" order by gusta desc, nombre asc');
            $id = $query->fetchAll();

            $_SESSION['arraygirls'] = Array();

            foreach ($id as $value) {
                if($value['genero']=="mujer"){

                    array_push($_SESSION['arraygirls'], $value['nombre']);
                }
            }
        }

        $hint="";
        if ($var1 !== "") {
            $var1 = strtolower($var1);
            $len=strlen($var1);
            foreach($_SESSION['arraygirls'] as $name) {
                if (stristr($var1, substr($name, 0, $len))) {
                    if ($hint === "") {
                        $hint = $name;
                    } else {
                        $hint .= ", $name";
                    }
                }
            }
        }
        
        return $hint === "" ? "no suggestion" : $hint;
        
    }
    
    
    function sumarLike($var1){
        
        $oDB = new ModelPDO();
        $oDB = $oDB->getDBO();
        $query = $oDB->query('update nombres set gusta=gusta+1 where nombre="'.$var1.'";');
        
        return "ok";
    }
    
    function restarLike($var1){
        
        $oDB = new ModelPDO();
        $oDB = $oDB->getDBO();
        $query = $oDB->query('update nombres set nogusta=nogusta+1 where nombre="'.$var1.'";');
          
        return "ok";
    }


    // Use the request to (try to) invoke the service
    $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
    $server->service($HTTP_RAW_POST_DATA);