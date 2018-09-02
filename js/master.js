$(document).ready(function() {
    // ASKING FOR SIGNATURE
    $.ajax({
            url:   '/server/signatureGenerator.php',
            success:  function (response) {
                const jsonResponse = JSON.parse(response);
                $("#signature").val(jsonResponse.signature);
                $("#referenceCode").val(jsonResponse.reference_code);
            }
    });

    // SENDIND DATA TO SAVE IN DB
    $("#boton_enviar").click(function( event ) {

        $("#imagen_carga").css("display", "block");
        var isReadyToSend = true;
        $('.required').each(function() {
            if ( $(this).val() === '' )
                isReadyToSend = false;
        });

        if(isReadyToSend){
            event.preventDefault();
            $.ajax({
                url: '/server/registro.php', // url where to submit the request
                type : "POST", // type of action POST || GET
                data : $("form.formulario").serialize(), // post data || get data
                success : function(result) {
                    console.log(result);
                    if(result.error){
                        $("#formulario_mensaje_error").text(result.error);
                    }
                    else{
                        $("#imagen_carga").css("display", "none");
                        $("form.formulario").submit();
                    }
                },
                error: function(xhr, resp, text) {
                    console.log("ERROR EN PETICIÓN **")
                    console.log(xhr, resp, text);
                }
            });
        }
        $("#imagen_carga").css("display", "none");
    });
});
