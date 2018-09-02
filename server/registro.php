<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    require_once(__DIR__.'/vendor/autoload.php');
    require_once(__DIR__.'/dbconnection.php');

    $conexionSql = new DatabaseConnection();
    $errorMessage = '';

    $buyerFullName = $_POST['buyerFullName'];
    $buyerEmail = $_POST['buyerEmail'];
    $telephone = $_POST['telephone'];
    $city = $_POST['ciudad'];
    $enterprise = $_POST['empresa'];
    $position = $_POST['cargo'];
    $country = $_POST['pais'];
    $document = $_POST['documento'];
    $referenceCode = $_POST['referenceCode'];

    if($buyerFullName != '' && $buyerEmail != '' && $telephone != '' &&
        $document != '' && $city != '' && $enterprise != '' && $position != '' &&
        $country != '' && $referenceCode != '') {

            //Guardando en la base de datos
            $query = "INSERT INTO `registro`
            (`id`,`nombre`,`correo`,`telefono`,`ciudad`,`empresa`,`cargo`,`pais`,`cedula`,`referencia_transaccion`
            ,`id_transaccion`,`referencia_venta`,`valor_total`,`estado_transaccion`,`moneda`,`forma_de_pago`
            ,`fecha_confirmacion`,`fecha_registro`)
            VALUES
            (NULL,'".$buyerFullName."','".$buyerEmail."','".$telephone."','".$city."','".$enterprise."','".$position."'
            ,'".$country."','".$document."','".$referenceCode."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NOW())";

            if(!$conexionSql->query($query)) {
                $errorMessage = "Lo sentimos, no pudimos almacenar tus datos";
                //$errorMessage = 'No se pudieron almacenar los datos: '.$conexionSql->error;
                //throw new Exception($errorMessage, $conexionSql->errno);
            }
    }
    else {
        $errorMessage = "Lo sentimos, no recibimos todos los datos requeridos para hacer el registro";
    }

    //Creando el objeto de respuesta
    $response = new StdClass();
    if ($errorMessage !== '') $response->error = $errorMessage;
    else $response->error =  false;
    header('Content-Type: application/json');
    echo(json_encode($response));
?>
