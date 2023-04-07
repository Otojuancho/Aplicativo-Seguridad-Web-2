<?php
    require_once('libs/php/funciones.php');
    require_once('libs/php/db_tools.php');
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
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="libs/css/contenedor.css">
    </head>
    <body>
        <div>
            <h2>
                <?php
                try {
                    if (isset($_POST['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
                        if (isset($_POST["btnIngresar"]) && isset($_POST["txtCapcha"]) && isset($_POST["txtCapcha"]) && isset($_SESSION["CAPTCHA"])) 
                        {
                            if ($_POST["txtCapcha"] == $_SESSION["CAPTCHA"]) {
                                echo 'Captch correcto --';
                                $conn = ConexionDB();
                                if ($conn != null) {
                                    if (ValidarLoginDB($conn, $_POST['txtUsuario'], md5($_POST['txtClave'])) != "") {
                                        $_SESSION['loguser'] = $_POST['txtUsuario'];
                                        header('Location:inicio.php');
                                        exit();
                                    }
                                    else
                                    {
                                        echo '<script language="javascript">
                                        alert("datos invalidos")
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
                ?>
            </h2>
        </div>
    
        <form class="formp" action="" method="post">
            <br>
            <div class="form-element">
                <label for="txtUsuario">Usuario:</label>
                <input class="inputp" type="text" name="txtUsuario" id="txtUsuario" pattern="[A-Za-z0-9]{1,}" required>
                <br>
            </div>
            <div class="form-element">
                <label for="txtClave">Contraseña:</label>
                <input class="inputp" type="password" name="txtClave" id="txtClave" pattern="[A-Za-z0-9 ]{1,}" required>
                <br>
            </div>
            <div class="form-element">
                <label for="txtCapcha">captcha:</label>
                <input class="inputp" type="text" name="txtCapcha" id="txtCapcha" pattern="<?php echo $captcha_text; ?>" required> 
            </div>
            <input type="hidden" name="anticsrf" value="<?php echo $anticsrf; ?>">
            <input class="boton" type="submit" name="btnIngresar" value="Ingresar">
            
        </form>
        <a class="boton" href="contraseña.php" >He olvidado mi contraseña</a>
    </body>
</html>