<?php
    require_once('libs/php/funciones.php');
    require_once('libs/php/db_tools.php');
    LimpiarEntradas();
    IniciarSesionSegura();
    //session_start();
    try {
        if (isset($_SESSION["loguser"])) {
            $conn = ConexionDB();
            if ($conn != null) {
                $foto = Foto($conn, $_SESSION["loguser"]);
                $roles = Roles($conn, $_SESSION["loguser"]);
            }
            foreach ($roles as $key => $value) {
                if ($value['Id_rol'] != 2 ) {
                    header('Location:index.php');
                }
            }
            if (isset($_POST['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
                if (isset($_POST['btnsalir']) && $_POST['btnsalir'] == 'salir') {
                    cerrarsesion();
                    exit();
                }
                if (isset($_POST['btnRegistrar']) && $_POST['btnRegistrar'] == 'Registrar') {
                    if ($_POST['txttipdoc'] == 0) {
                        $_POST['txttipdoc'] = NULL;
                    }
                    if ($_POST['txtgenero'] == 0) {
                        $_POST['txtgenero'] = NULL;
                    }
                    $resultadonumericotel = validarnumeros($_POST['txttelefono']);
                    $resultadonumericodoc = validarnumeros($_POST['txtdocumento']);
                    if (isset($resultadonumericotel) && isset($resultadonumericodoc)) {
                        $conn = ConexionDB();
                        if ($conn != null) {
                            $registrado = RegistrarUsuarioDB($conn, $_POST['txtNombre'], $_POST['txtApellidos'], $_POST['txttipdoc'], $_POST['txtdocumento'], 
                            $_POST['txtgenero'], $_POST['txtUsuario'], md5($_POST['txtClave']), $_POST['txtnacimiento'],  $_POST['txtCorreo'], $_POST['txttelefono'], 
                            $_POST['txtdireccion'], $_POST['txttipocuenta'], $_SESSION['loguser']);
                            if ($registrado == TRUE) {
                                echo '<script language="javascript">
                                alert("usuario registrado")
                                window.location.href="inicio.php";
                                </script>';
                                exit();
                            }
                            else
                            {
                            echo '<script language="javascript">
                                alert("usuario NO registrado")
                                window.location.href="crearcuentahabitante.php";
                                </script>';
                                exit();
                            }
                        }
                    }
                    else
                    {
                        echo '<script language="javascript">
                        alert("Datos invalidos")
                        window.location.href="crearcuentahabitante.php";
                        </script>';
                        exit();
                    }
                }
            }
        }
        else
        {
            header("Location:index.php");
            exit();
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    $anticsrf = rand(1000, 9999);
    $_SESSION['anticsrf'] = $anticsrf;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Crear Cuentahabiente</title>
  <link rel="stylesheet" href="libs/css/contenedor.css?v=<?php echo(rand()); ?>" />
</head>
<body>

  <header>
    <div class="menu">
      <nav>
          <ul>
            <li><a href="inicio.php">Inicio</a></li>
            <!--li><img height="50" src="uploaded_files/images/<?php //echo $foto["Foto"];?>"></li>-->
            <li style="color:rgb(255, 255, 255);">Bienvenido, <strong> <?php echo $foto['nombre'];?></strong></li> 
            <li style="color:rgb(255, 255, 255);">
                <form method="POST">
                    <input type="hidden" name="anticsrf" value="<?php echo $anticsrf; ?>">
                    <input class="boton" type="submit" name="btnsalir" value="salir">
                </form>
            </li>
          </ul>
      </nav>
    </div>
  </header>
   <form class="formp" method="post" enctype="multipart/form-data">
        <a class="boton" href="inicio.php">volver</a>
        <br>
        <div class="form-element">
            <label for="txtNombre">Nombres:</label>
            <input class="inputp" type="text" name="txtNombre" id="txtNombre" pattern="[A-Za-z ]{1,}" required>
        </div>
        <div class="form-element">
            <label for="txtApellidos">Apellidos:</label>
            <input class="inputp" type="text" name="txtApellidos" id="txtApellidos" pattern="[A-Za-z ]{1,}" required>
        </div>
        <div class="form-element">
            <label for="txttipdoc">Tipo documento:</label>
            <select class="selectp" name="txttipdoc" id="txttipdoc">
                <?php
                    try {
                        $conn = ConexionDB();
                        if ($conn != null) {
                            $ltipdocs = ListaTipdocsDB($conn);
                            if (isset($ltipdocs)) {
                                foreach ($ltipdocs as $key => $value) {
                                    echo'
                                        <option value="'.$value["Id_tipodoc"].'">'.$value["Tipo_doc"].'</option>
                                    ';
                                }
                            }
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                ?>
            </select>
        </div>
        <div class="form-element">
            <label for="txtdocumento">Numero de documento:</label>
            <input class="inputp" type="number" name="txtdocumento" id="txtdocumento" pattern="[0-9]{1,}" required>
        </div>
        <div class="form-element">
            <label for="txtgenero">Genero:</label>
            <select class="selectp" name="txtgenero" id="txtgenero">
                <?php
                    try {
                        $conn = ConexionDB();
                        if ($conn != null) {
                            $lgeneros = ListagenerosDB($conn);
                            if (isset($lgeneros)) {
                                foreach ($lgeneros as $key => $value) {
                                    echo'
                                        <option value="'.$value["Id_genero"].'">'.$value["Genero"].'</option>
                                    ';
                                }
                            }
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                ?>
            </select>
        </div>
        <div class="form-element">
            <label for="txtUsuario">Usuario:</label>
            <input class="inputp" type="text" name="txtUsuario" id="txtUsuario" pattern="[A-Za-z0-9]{1,}" required>
        </div>
        <div class="form-element">
            <label for="txtClave">Contraseña:</label>
            <input class="inputp" type="password" name="txtClave" id="txtClave" pattern="[A-Za-z0-9 ]{1,}" required>
        </div>
        <div class="form-element">
            <label for="txtnacimiento">Fecha de nacimiento:</label>
            <input class="inputp" type="date" name="txtnacimiento" id="txtnacimiento" required>
        </div>
        <div class="form-element">
            <label for="txtCorreo">Correo:</label>
            <input class="inputp" type="email" name="txtCorreo" id="txtCorreo" required>
        </div>
        <div class="form-element">
            <label for="txttelefono">Telefono:</label>
            <input class="inputp" type="number" name="txttelefono" id="txttelefono" pattern="[0-9]{1,}" required>
        </div>
        <div class="form-element">
            <label for="txtdireccion">Direccion:</label>
            <input class="inputp" type="text" name="txtdireccion" id="txtdireccion" pattern="[A-Za-z0-9 ]{1,}" required>
        </div>
        <div class="form-element">
            <label for="txttipocuenta">Tipo de cuenta:</label>
            <select class="selectp" name="txttipocuenta" id="txttipocuenta">
                <?php
                    try {
                        $conn = ConexionDB();
                        if ($conn != null) {
                            $ltipocuenta = ListatiposcuentaDB($conn);
                            if (isset($lgeneros)) {
                                foreach ($ltipocuenta as $key => $value) {
                                    echo'
                                        <option value="'.$value["Id_tipo_cuenta"].'">'.$value["Tipo_cuenta"].'</option>
                                    ';
                                }
                            }
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                ?>
            </select>
        </div>
        
        <input type="hidden" name="anticsrf" value="<?php echo $anticsrf; ?>">
        <input class="boton" type="submit" name="btnRegistrar" value="Registrar">
    </form>
</body>
</html>
