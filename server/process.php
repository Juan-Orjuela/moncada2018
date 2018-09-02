<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once(__DIR__.'/vendor/autoload.php');
    require_once(__DIR__.'/conexionbd.php');

    $conexionSql = new DatabaseConnection();

    $errorMessage = '';

    $datos = file_get_contents('php://input');
    if($datos !== '') {
        $datosJSON = json_decode( $datos );
        $check = $datosJSON->check;
        $nombre = $datosJSON->nombre;
        $entidad = $datosJSON->entidad;
        $mail = $datosJSON->mail;
        $cargo = $datosJSON->cargo;
        $documento = $datosJSON->documento;
        $contacto = $datosJSON->contacto;
    } else $check = '0';

    if($check === '1') {
        //Guardando en la base de datos
        $query = "INSERT INTO `inscripciones`
        (`id`,`nombre`,`entidad`,`mail`,`cargo`,`documento`,`telefono`,`fecha`)
        VALUES
        (NULL,'".$nombre."','".$entidad."','".$mail."','".$cargo."','".$documento."','".$contacto."',NOW())";

        if(!$conexionSql->query($query)) {
            $errorMessage = "Lo sentimos, no pudimos almacenar tus datos";
            //$errorMessage = 'No se pudieron almacenar los datos: '.$conexionSql->error;
            //throw new Exception($errorMessage, $conexionSql->errno);
        }

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
        $mailer->addAddress('asistente@moncadaabogados.com.co', 'Asistente Moncada');     // Add a recipient
        $mailer->addAddress('alejotorres@bibrand.co', 'Alejandro Torres');     // Add a recipient
        $mailer->addAddress('moncadaabogados@moncadaabogados.com.co', 'Abogados Moncada');     // Add a recipient
        $mailer->addAddress('miguel9ramos@gmail.com', 'Miguel Ramos');               // Name is optional

        $mailer->isHTML(true);                                  // Set email format to HTML
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = 'Post inscritos';
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
                        Se ha registrado un nuevo usuario en el evento con los siguientes datos:
                    </p>
                    <ul>
                        <li><strong>Nombre:</strong> <span>'.$nombre.'</span></li>
                        <li><strong>Entidad:</strong> <span>'.$entidad.'</span></li>
                        <li><strong>Email:</strong> <span>'.$mail.'</span></li>
                        <li><strong>Cargo:</strong> <span>'.$cargo.'</span></li>
                        <li><strong>Documento:</strong> <span>'.$documento.'</span></li>
                        <li><strong>Contacto:</strong> <span>'.$contacto.'</span></li>
                    </ul>
                </section>
            </body>
        </html>';

        if(!$mailer->send())
            $errorMessage = 'Mailer Error: ' . $mailer->ErrorInfo;

        //Setting response
        $response = new StdClass();
        if ($errorMessage !== '') $response->error = $errorMessage;
        else $response->error =  false;
        header('Content-Type: application/json');
        echo(json_encode($response));

    }
?>
