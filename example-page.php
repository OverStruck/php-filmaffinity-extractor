<?php 
    /**
     * @author OverStruck (Juanix.net, OverStruck.com, github.com/overstruck)
     * @version 0.1
     * @license http://opensource.org/licenses/MIT
     * @example example-page.php simple example on how to use this class
     * in an AJAX request using jquery...
     * 
     * Please note that the html fields are basically the property
     * names of the json response object, and these properties names depend
     * on the fields filmaffinity uses, so if they use something like
     * "website2" for some films but not for all of the, you might or might not
     * get that property in the json object and thus, you should try to use 
     * the object like like we did here, by looping through it instead of hard-coding
     * property names
     */
?>
<!DOCTYPE html>
<html>
<head>
    <title>FilmaffinityExtractor</title>
    <meta charset="UTF-8">
</head>

<body>
    <input type="text" id="filmID" placeholder="Ingresa ID o URL" size="100">
    <button id="b1">Obtener Datos</button>
    <div id="info">

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">
        var INFO = $("#info");

        $("#b1").click(load);

        function load() {
            INFO.html("CARGANDO...");

            var filmID = $("#filmID").val();
            var connection = $.get('example-script.php', {
                "id": filmID
            }, checkResponse, "json");

            connection.fail(function(xhr, status, error) {
            	INFO.html('Error Desconocido: <br><br>' + error + '<br>Respuesta del servidor:<br>' + xhr.responseText);
            });
        }

        function checkResponse(response) {
            if (response.error) {
                var msg = response.msg ? response.msg : 'Error: ID Vacio o mal formado :/';
                msg = '<h1 style="color:red">' + msg + '</h1>';
                INFO.html(msg);
            } else {
                proccessResponse(response);
            }
        }

        function proccessResponse(response) {
            var html = '';
            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    var value = response[key];
                    html += "<div><b>" + key + "</b> " + value + "</div>";
                }
            }
            INFO.html(html);
        }
    </script>
</body>

</html>