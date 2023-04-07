<?php
    require_once('funciones.php');
    require_once('db_tools.php');
    LimpiarEntradas();
    IniciarSesionSegura();
    if (isset($_SESSION['loguser'])) {
        if (isset($_GET['anticsrf']) && $_GET['anticsrf'] == $_SESSION['anticsrf']) {
            if (isset($_GET["like"]) && $_GET["like"] != '') {
                $like = LikeDB($_GET["like"],$_SESSION["loguser"]);
                echo'<meta http-equiv="refresh" content="0.1" />';
            }
            if (isset($_GET["text"]) && $_GET["text"] != '' && isset($_GET["id"]) && $_GET["id"] != 0 && $_GET["action"] == "enviar_mensaje") {
                $mensaje = RegistrarMensajeDB($_SESSION['loguser'],$_GET["text"],$_GET["id"]);
            }
            if (isset($_GET["id"]) && $_GET["id"] != 0 && $_GET["action"] == "actualizarchat") {
                $mensaje = ListarMensajesEDB($_GET["id"]);
                echo $mensaje;
            }
            
        }
        if (isset($_POST['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
            if ((isset($_POST["text"]) && $_POST["text"] != '' && isset($_POST["id"]) && $_POST["id"] != 0 && $_POST["action"] == "enviar_mensaje")
                || ($_POST["text"] == '' && $_FILES['fulAdjunto']['name'] != '' && isset($_POST["id"]) && $_POST["id"] != 0 && $_POST["action"] == "enviar_mensaje")) {
                if (!isset($_FILES['fulAdjunto']['name']) || $_FILES['fulAdjunto']['name'] == '')
                {
                    $resultadofile = NULL;
                }
                else {
                    $resultadofile = validarArchivo($_FILES['fulAdjunto']);
                }
                $mensajeX = RegistrarMensajeDB($_SESSION['loguser'],$_POST["text"],$_POST["id"],$resultadofile);
                return;
            }
            if (isset($_POST["id"]) && $_POST["id"] != 0 && $_POST["action"] == "actualizarchat") {
                $mensaje = ListarMensajesEDB($_POST["id"]);
                echo $mensaje;
            }
            
        }
    }
    else {
        echo 'Acceso no autorizado';
        http_response_code(401);
        exit();
    }
?>