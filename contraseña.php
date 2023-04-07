<?php
    require_once('libs/php/funciones.php');
    require_once('libs/php/db_tools.php');
    require_once('htdocs/email/index.php');
    LimpiarEntradas();
    IniciarSesionSegura();
    //session_start();
    if (isset($_SESSION["loguser"])) {
        header('Location:inicio.php');
        exit();
    }
?>
<html>
    <head>
        <title>Restablecer contraseña</title>
        <link rel="stylesheet" type="text/css" href="libs/css/contenedor.css">
    </head>
    <body>
        <div>
            <h2>
                <?php
                try {
                    if (isset($_POST['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
                        if (isset($_POST["btnenviar"]) && isset($_POST["txtCapcha"]) && isset($_POST["txtCapcha"]) && isset($_SESSION["CAPTCHA"])) 
                        {
                            if ($_POST["txtCapcha"] == $_SESSION["CAPTCHA"]) {
                                echo 'Captch correcto --';
                                $conn = ConexionDB();
                                if ($conn != null) {
                                    $tok = SolicitudDB($conn, $_POST['txtUsuario'], $_POST['txtCorreo']);
                                    if (isset($tok)) {
                                        $resultadoc = enviartoken($tok, $_POST['txtCorreo']);
                                        if ($resultadoc = true) {
                                            echo '<script language="javascript">
                                                alert("token enviado por correo")
                                                window.location.href="contraseña.php";
                                                </script>';
                                            exit();
                                        }
                                        else{
                                            echo '<script language="javascript">
                                                alert("fallo al enviar el token, intentelo mas tarde")
                                                window.location.href="index.php";
                                                </script>';
                                            exit();
                                        }
                                    }
                                    else
                                    {
                                        echo '<script language="javascript">
                                        alert("Usted ya tiene una solicitud activa o sus datos son erroneos.")
                                        window.location.href="index.php";
                                        </script>';
                                        exit();
                                    }
                                } 
                            }
                            else
                            {
                                echo 'Captch incorrecto --';
                            }
                        }
                        
                        if (isset($_POST["btnrest"]) && isset($_POST["txtCapcha"]) && isset($_POST["txtCapcha"]) && isset($_SESSION["CAPTCHA"])) 
                        {
                            if ($_POST["txtCapcha"] == $_SESSION["CAPTCHA"]) {
                                echo 'Captch correcto --';
                                $conn = ConexionDB();
                                if ($conn != null) {
                                    $contra = cambiocontraDB($conn, $_POST['txtUsuario'], md5($_POST['txtClave']), $_POST['txtTpken']);
                                    if ($contra) {

                                        echo '<script language="javascript">
                                            alert("Contraseña actualizada")
                                            window.location.href="contraseña.php";
                                            </script>';
                                        exit();
                                    }
                                    else
                                    {
                                        echo '<script language="javascript">
                                        alert("Fallo al actualizar la contraseña")
                                        window.location.href="index.php";
                                        </script>';
                                        exit();
                                    }
                                } 
                            }
                            else
                            {
                                echo 'Captch incorrecto --';
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
                $captcha_text = rand(1000,9999);
                echo 'Captcha Generado: <input class="inputp" id="captcha" value="'.$captcha_text.'" disabled>';
                $_SESSION["CAPTCHA"] = $captcha_text;
                $anticsrf = rand(1000, 9999);
                $_SESSION['anticsrf'] = $anticsrf;
                if (isset($_POST["btntoken"])) 
                {
                    $htmltoken = '<form class="formp" action="" method="post">
                    <h1>Si los datos solicitados son correctos, se cambiara su contraseña.</h1>
                        <br>
                        <div class="form-element">
                            <label for="txtUsuario">Usuario:</label>
                            <input class="inputp" type="text" name="txtUsuario" id="txtUsuario" pattern="[A-Za-z0-9]{1,}" required>
                            <br>
                        </div>
                        <div class="form-element">
                            <label for="txtClave">Nueva contraseña:</label>
                            <input class="inputp" type="password" name="txtClave" id="txtClave"  required>
                            <br>
                        </div>
                        <div class="form-element">
                            <label for="txtTpken">Token:</label>
                            <input class="inputp" type="text" name="txtTpken" id="txtTpken"  required>
                            <br>
                        </div>
                        <div class="form-element">
                            <label for="txtCapcha">captcha:</label>
                            <input class="inputp" type="text" name="txtCapcha" id="txtCapcha" pattern="'.$captcha_text.'" required> 
                        </div>
                        <input type="hidden" name="anticsrf" value="'. $anticsrf .'">
                        <input class="boton" type="submit" name="btnrest" value="Cambiar contraseña">
                    </form>';
                
                }
                ?>
            </h2>
        </div>
        <br>
        <br>
        <a class="boton" href="index.php" >Volver</a>
        <?php
        if (isset($_POST["btntoken"]) && isset($htmltoken)) {
            echo $htmltoken;
        }
        else 
        {
            echo '<form class="formp" action="" method="post">
            <h1>si sus datos coinciden con su cuenta, se le enviara un correo con un token.</h1>
                <br>
                <div class="form-element">
                    <label for="txtUsuario">Usuario:</label>
                    <input class="inputp" type="text" name="txtUsuario" id="txtUsuario" pattern="[A-Za-z0-9]{1,}" required>
                    <br>
                </div>
                <div class="form-element">
                    <label for="txtCorreo">Correo:</label>
                    <input class="inputp" type="email" name="txtCorreo" id="txtCorreo"  required>
                    <br>
                </div>
                <div class="form-element">
                    <label for="txtCapcha">captcha:</label>
                    <input class="inputp" type="text" name="txtCapcha" id="txtCapcha" pattern="'. $captcha_text .'" required> 
                </div>
                <input type="hidden" name="anticsrf" value="'.$anticsrf.'">
                <input class="boton" type="submit" name="btnenviar" value="Enviar">
            </form>
            <form action="" method="post">
                <input type="hidden" name="anticsrf" value="'.$anticsrf.'">
                <input class="boton" type="submit" name="btntoken" value="ya tengo un token">
            </form>';
        }
        ?>
    </body>
</html>