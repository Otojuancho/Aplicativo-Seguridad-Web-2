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
            }
            if (!isset($_POST["btnver"]) && $_POST["btnver"] != 'VER') {
                header("Location:index.php");
                exit();
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
  <title>Perfil</title>
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
  <h1>Datos Cuenta habiente</h1>
    <?php
        try {
            $conn = ConexionDB();
            if ($conn != null) {
                $listdat = ListarDatosCuentaHDB($conn, $_POST['txtidpersona']);
                foreach ($listdat as $key => $value) {
                    echo '
                    <table  class = "table" >
                        <tr>
                            <td> <label>Nombre: </label> </td> <td> '.$value["Nombre_comp"].'</td>
                        </tr>
                        <tr>
                            <td><label>Tipo documento: </label></td> <td> '.$value["Tipo_doc"].'</td>
                        </tr>
                        <tr>
                            <td><label>Número documento: </label></td> <td> '.$value["Numero_Doc"].'</td>
                        </tr>
                        <tr>
                            <td><label>Genero: </label></td> <td> '.$value["Genero"].'</td>
                        </tr>
                        <tr>
                            <td><label>Usuario: </label></td> <td> '.$value["Usuario"].'</td>
                        </tr>
                        <tr>
                            <td><label>Dias de la cuenta: </label></td> <td> '.$value["dias_creacion"].'</td>
                        </tr>
                        <tr>
                            <td><label>Fecha nacimiento: </label></td> <td> '.$value["Fecha_nac"]->format('Y-m-d H:i:s') . "\n".'</td>
                        </tr>
                        <tr>
                            <td><label>Correo: </label></td> <td> '.$value["Correo"].'</td>
                        </tr>
                        <tr>
                            <td><label>Telefono: </label></td> <td> '.$value["Telefono"].'</td>
                        </tr>
                        <tr>
                            <td><label>Dirreccion: </label></td> <td> '.$value["Direccioncon"].'</td>
                        </tr>
                        <tr>
                            <td><label>Rol: </label></td> <td> '.$value["Rol"].'</td>
                        </tr>
                        <tr>
                            <td><label>Numero cuenta: </label></td> <td> '.$value["Id_cuenta"].'</td>
                        </tr>
                        <tr>
                            <td><label>Tipo cuenta: </label></td> <td> '.$value["Tipo_cuenta"].'</td>
                        </tr>
                        <tr>
                            <td><label>Saldo cuenta: </label></td> <td> $'.$value["Saldo_disponible"].'</td>
                        </tr>
                    </table>
                    '; 
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    ?>
</body>
</html>
