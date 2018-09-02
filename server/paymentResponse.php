<?php
$ApiKey = "ryE6LgHsdp910mO633h6R6DRUl";
$merchant_id = $_REQUEST['merchantId'];
$referenceCode = $_REQUEST['referenceCode'];
$TX_VALUE = $_REQUEST['TX_VALUE'];
$New_value = number_format($TX_VALUE, 1, '.', '');
$currency = $_REQUEST['currency'];
$transactionState = $_REQUEST['transactionState'];
$firma_cadena = "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
$firmacreada = md5($firma_cadena);
$firma = $_REQUEST['signature'];
$reference_pol = $_REQUEST['reference_pol'];
$cus = $_REQUEST['cus'];
$extra1 = $_REQUEST['description'];
$pseBank = $_REQUEST['pseBank'];
$lapPaymentMethod = $_REQUEST['lapPaymentMethod'];
$transactionId = $_REQUEST['transactionId'];

if ($_REQUEST['transactionState'] == 4 ) {
    $estadoTx = "aprobada";
}

else if ($_REQUEST['transactionState'] == 6 ) {
    $estadoTx = "rechazada";
}

else if ($_REQUEST['transactionState'] == 104 ) {
    $estadoTx = "Error";
}

else if ($_REQUEST['transactionState'] == 7 ) {
    $estadoTx = "pendiente";
}

else {
    $estadoTx=$_REQUEST['mensaje'];
}


if (strtoupper($firma) == strtoupper($firmacreada)) {
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="img/ico" href="/favicon.ico">
        <link rel="stylesheet" href="../css/respuesta.css" media="screen" charset="utf-8">
        <meta charset="utf-8">
        <title>Moncada Abogados | Eventos</title>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments)};
          gtag('js', new Date());

          gtag('config', 'UA-107171538-1');
        </script>
    </head>
    <body>
        <section id="respuesta" >
            <div class="contenedor_general">
                <div class="informacion">
                    <div class="informacion_empresarial">
                        <img src="../img/response/logo_evento.png">
<!--                         <hr> -->
                        <p class="nombre_foro"><span>FORO</span> LEGAL Y EMPRESARIAL.</p>
                    </div>
                    <div class="bienvenida">
                        <p>Bienvenido.</p>
                    </div>
                </div>
                <hr id="primer_divisor">
                <div class="informacion informacion_estado">
                    <div class="estado">
                        <p class="regular">Transacción</p>
                        <p class="bold"><?php echo $estadoTx; ?></p>
                        <?php
                        if($_REQUEST['transactionState'] == 4 ) {
                        ?>
                            <img src="../img/response/chulo.png">
                        <?php
                        }
                        ?>

                    </div>
                    <div class="bienvenida_fecha">
                        <p class="regular">Bienvenido, nos vemos</p>
                        <p class="last">el próximo <span>15 y 16 de Noviembre</span></p>
                    </div>
                </div>
                <hr id="segundo_divisor">
                <div class="informacion">
                    <div class="info_transaccion">
                        <p class="titulo_informacion"> ID de la transacción:</span>
                        <p class="resultado_transaccion"> <?php echo $transactionId; ?> </p>
                    </div>
                    <div class="info_transaccion">
                        <p class="titulo_informacion"> Referencia de venta:</span>
                        <p class="resultado_transaccion"> <?php echo $reference_pol; ?> </p>
                    </div>
                    <div class="info_transaccion">
                        <p class="titulo_informacion"> Referencia de transacción:</span>
                        <p class="resultado_transaccion"> <?php echo $referenceCode; ?> </p>
                    </div>
                    <div class="info_transaccion">
                        <p class="titulo_informacion"> Valor total:</span>
                        <p class="resultado_transaccion"> $<?php echo number_format($TX_VALUE); ?> </p>
                    </div>
                    <div class="info_transaccion">
                        <p class="titulo_informacion"> Moneda:</span>
                        <p class="resultado_transaccion"> <?php echo $currency; ?> </p>
                    </div>
                    <div class="info_transaccion">
                        <p class="titulo_informacion"> Descripción:</span>
                        <p class="resultado_transaccion"> <?php echo ($extra1); ?></p>
                    </div>
                    <div class="info_transaccion">
                        <p class="titulo_informacion"> Entidad:</span>
                        <p class="resultado_transaccion"> <?php echo ($lapPaymentMethod); ?> </p>
                    </div>
                </div>
                <div class="volver">
                    <a href="../index.html" id="boton_volver">volver</a>
                    <img src="../img/response/flecha_der_for.png">
                </div>
            </div>
        </section>
    </body>
</html>

<?php
}
else
{
?>
    <h1>Error validando firma digital.</h1>
<?php
}
?>
