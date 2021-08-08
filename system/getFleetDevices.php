<?php
  header('Content-Type: text/html; charset=ISO-8859-15');
  header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  /*
  session_start();
  if(isset($_SESSION["cuenta"])){

  }else{
    echo "<head><link rel='stylesheet' href='../css/general.css' /></head>";

    die("<div id='alertContainer'><div id='alertMessage'>Debe ingresar al sistema antes de realizar cualquier consulta
      </div></div>"); 
  }
  */

  include('../../config.php');
  $conexion   = @new mysqli($database['hostname'], $database['username'], $database['password'], $database['database'], $database['port']);
  
  if ($conexion->connect_error){
	  die('Error de conexión: ' . $conexion->connect_error); 
  }

  $accID    = $_GET["accID"];
  $fleetID  = $_GET["fleetID"];

  $cars     = array();

  // Buscar todos los dispositivos de la flota

  if ($fleetID =='isAdmin'){
    $sql2="SELECT `deviceID` FROM Device WHERE `accountID`='$accID';";
  }else{
    $sql2="SELECT `deviceID` FROM DeviceList WHERE `accountID`='$accID' AND `groupID`='$fleetID';";
  }
  
  $result2 = $conexion->query($sql2);
  
  if ($result2->num_rows > 0){
    // Llenamos el array CARS con la lista de equipos encontrados
    while($row = $result2->fetch_array(MYSQLI_ASSOC)){
      $cars[]=$row['deviceID'];

    }
    // Identificamos el total de equipos en CARS
    $totalCars = count($cars);

    $track    = array();

    // Realizamos una consulta detallada por cada dispositivo
    for($i = 0; $i < $totalCars; $i++) {
      $consul="SELECT Device.`deviceID`, Device.`uniqueID`, Device.`description`, Device.`licensePlate`, 
      Device.`simPhoneNumber`, EventData.`timestamp`, EventData.`statusCode`, EventData.`latitude`, 
      EventData.`longitude`, EventData.`speedKPH`, EventData.`heading`,EventData.`streetAddress`, 
      EventData.`city`, 'powerOffCommand', 'powerOnCommand', 'optionCommand'
      FROM EventData, Device WHERE EventData.`deviceID`=Device.`deviceID` 
      AND EventData.`accountID`=Device.`accountID` AND EventData.`accountID`='$accID' 
      AND EventData.`deviceID`='".$cars[$i]."' ORDER BY EventData.`timestamp` DESC LIMIT 1;";

      //echo $consul."\n";

      $resultado = $conexion->query($consul);     

      if ($resultado -> num_rows > 0){
        while($fila = $resultado->fetch_array(MYSQLI_ASSOC)){
          
          $track[] = array(
            'deviceID'        => utf8_encode($fila['deviceID']),
            'uniqueID'        => utf8_encode($fila['uniqueID']),
            'description'     => utf8_encode($fila['description']),
            'licensePlate'    => utf8_encode($fila['licensePlate']),
            'simPhoneNumber'  => utf8_encode($fila['simPhoneNumber']),
            'timestamp'       => utf8_encode($fila['timestamp']),
            'statusCode'      => utf8_encode($fila['statusCode']),
            'latitude'        => utf8_encode($fila['latitude']),
            'longitude'       => utf8_encode($fila['longitude']),
            'speedKPH'        => utf8_encode($fila['speedKPH']),
            'heading'         => utf8_encode($fila['heading']),
            'streetAddress'   => utf8_encode($fila['streetAddress']),
            'city'            => utf8_encode($fila['city']),
            'powerOffCommand' => utf8_encode($fila['powerOffCommand']),
            'powerOnCommand'  => utf8_encode($fila['powerOnCommand']),
            'optionCommand'   => utf8_encode($fila['optionCommand'])
          );
        } // Fin de While "llenar $track"
      }  // Fin de IF filas>0
    } // Fin de FOR "Cargar registros"

    //$ArrayPrueba = ordernarArray($track,"timestamp",true);
    echo json_encode($track);


  }else{
    $track[] = array(
        'deviceID'   => "-"
      );
	  echo json_encode($track);

  }
  $conexion->close(); //cerramos la conexión
?>