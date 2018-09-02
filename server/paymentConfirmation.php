<?php

    ini_set('log_errors', 1);
    ini_set('error_log', 'php-error.log');
    error_reporting(E_ALL);

    require_once(__DIR__.'/vendor/autoload.php');
    require_once(__DIR__.'/dbconnection.php');

    $paymentMethodTypeAssociation = array(
        "2" => "CREDIT_CARD",
        "4" => "PSE",
        "5" => "ACH",
        "6" => "DEBIT_CARD",
        "7" => "CASH",
        "8" => "REFERENCED",
        "10" => "BANK_REFERENCED"
    );

    //Recovering data from post request
    $transactionStateMessage = $_POST['response_message_pol'];
    $referencePol = $_POST['reference_pol'];
    $sign = $_POST['sign'];
    $paymentMethodType = $paymentMethodTypeAssociation[$_POST['payment_method_type']];

    //Changes with each transaction try
    $transactionId = $_POST['transaction_id'];

    //Data to validate signature
    $merchantId = $_POST['merchant_id'];
    $referenceSale = $_POST['reference_sale'];
    $value = $_POST['value'];
    $newValue = number_format($value, 1, '.', '');
    $currency = $_POST['currency'];
    $transactionState = $_POST['state_pol'];

    //Validating signature to check data validity
    $apiKey = "ryE6LgHsdp910mO633h6R6DRUl";
    $rawSignature = "$apiKey~$merchantId~$referenceSale~$newValue~$currency~$transactionState";
    $generatedSign = md5($rawSignature);

    if(strtoupper($generatedSign) == strtoupper($sign)) {

        $conexionSql = new DatabaseConnection();
        $mensajeError = "";

        $query = "UPDATE `registro`
        SET `id_transaccion` = '".$transactionId."',
        `referencia_venta` = '".$referencePol."',
        `valor_total` = '".$newValue."',
        `estado_transaccion` = '".$transactionStateMessage."',
        `moneda` = '".$currency."',
        `forma_de_pago` = '".$paymentMethodType."',
        `fecha_confirmacion` = NOW()
        WHERE `referencia_transaccion` = '".$referenceSale."'";

        if(!$conexionSql->query($query)) {
            $mensajeError = "Lo sentimos, no pudimos almacenar tus datos";
            http_response_code(404);
            error_log($mensajeError);
            echo($mensajeError);
        }

        else {

            //Trayendo datos del comprador
            $query = "SELECT * FROM `registro` WHERE `referencia_transaccion`='".$referenceSale."'";
            $result = $conexionSql->query($query);
            if($result->num_rows) {

                $result->data_seek(0);
                $object = $result->fetch_object();
                $nombre = $object->nombre;
                $correo = $object->correo;
                $telefono = $object->telefono;
                $ciudad = $object->ciudad;
                $empresa = $object->empresa;
                $cargo = $object->cargo;
                $pais = $object->pais;
                $cedula = $object->cedula;

                //Enviando Email
                $mailer = new PHPMailerOAuth;

                $mailer->isSMTP();                                      // Set mailer to use SMTP
                $mailer->Host = 'a2plcpnl0934.prod.iad2.secureserver.net';  // Specify main and backup SMTP servers
                $mailer->SMTPAuth = true;                               // Enable SMTP authentication
                $mailer->Username = 'info@eventosmoncada.com.co';   // SMTP username
                $mailer->Password = 'Moncada1816';                           // SMTP password
                $mailer->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mailer->Port = 465;                                    // TCP port to connect to

                $mailer->setFrom('eventos@moncadaabogados.com.co', 'Eventos Moncada');
                $mailer->addAddress('j.sanabria@moncadaabogados.com.co', 'J Sanabria');     // Add a recipient
                $mailer->addAddress('asistente@moncadaabogados.com.co', 'Asistente Moncada');     // Add a recipient
                $mailer->addAddress('miguel9ramos@gmail.com', 'Miguel Ramos');               // Name is optional

                $mailer->isHTML(true);                                  // Set email format to HTML
                $mailer->CharSet = 'UTF-8';
                $mailer->Subject = 'Confirmados evento Compliance 2018 - '.$transactionId;
                $mailer->Body = '<!DOCTYPE html>
                <html>
                    <head>
                        <meta charset="utf-8">
                        <title>Abogados Moncada | Eventos</title>
                    </head>
                    <style media="screen">
                    </style>
                    <body>
                        <section>
                            <p>
                                Se ha confirmado el pago de un nuevo asistente al evento
                                Compliance 2018 con los siguientes datos:
                            </p>
                            <ul>
                                <li><strong>Nombre:</strong> <span>'.$nombre.'</span></li>
                                <li><strong>Email:</strong> <span>'.$correo.'</span></li>
                                <li><strong>Documento:</strong> <span>'.$cedula.'</span></li>
                                <li><strong>Contacto:</strong> <span>'.$telefono.'</span></li>
                                <li><strong>Empresa:</strong> <span>'.$empresa.'</span></li>
                                <li><strong>Cargo:</strong> <span>'.$cargo.'</span></li>
                                <li><strong>Ciudad:</strong> <span>'.$ciudad.'</span></li>
                                <li><strong>País:</strong> <span>'.$pais.'</span></li>
                                <li><strong>Estado transacción:</strong> <span>'.$transactionStateMessage.'</span></li>
                            </ul>
                        </section>
                    </body>
                </html>';

                if(!$mailer->send()) {
                    $mensajeError = 'Mailer Error: ' . $mailer->ErrorInfo;
                    error_log($mensajeError);
                }

            }

            else {
                $mensajeError = "Se generó error al traer los datos del comprador";
                error_log($mensajeError);
            }

            //Desde que se haya hecho la actualización se envía mensaje de éxito
            echo("success operation");
        }
    }
    else {
        http_response_code(404);
        $mensajeError = "Digital signature does not match";
        error_log($mensajeError);
        echo($mensajeError);
    }
?>
