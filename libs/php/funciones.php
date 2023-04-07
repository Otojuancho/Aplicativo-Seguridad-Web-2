<?php
    function MostrarErrores()
    {
        error_reporting(E_ALL);
        ini_set('display_errors','1');
    }
    function validarfoto($a)
    {
        try {
            $nuevonombrefile = NULL;
            if (isset($a) && $a != '') {
                $extension = pathinfo($a['name'], PATHINFO_EXTENSION);
                $extension = strtolower($extension);
                $extension_correcta = ($extension == 'jpg' or $extension == 'jpeg' or $extension == 'gif' or $extension == 'png');
                if ($extension_correcta) {
                    $nuevonombrefile = md5(time().$a['name']).'.'.$extension;
                    $ruta_destino_archivo = "C:\wampportable\UniServerZ\www\proyectoEPD1\uploaded_files\images/{$nuevonombrefile}";
                    if (move_uploaded_file($a['tmp_name'], $ruta_destino_archivo)) {
                        if($extension == 'jpg'){
                            $img = imagecreatefromjpeg($ruta_destino_archivo);
                            imagejpeg($img, $ruta_destino_archivo, 100);
                            imagedestroy($img);
                        }
                        if($extension == 'gif'){
                            $img = imagecreatefromgif($ruta_destino_archivo);
                            imagegif($img, $ruta_destino_archivo);
                            imagedestroy($img);
                        }
                        if($extension == 'png'){
                            $img = imagecreatefrompng($ruta_destino_archivo);
                            imagepng($img, $ruta_destino_archivo);
                            imagedestroy($img);
                        }
                    }
                }
            }
            return $nuevonombrefile;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function validarnumeros($a)
    {
        $datos = NULL;
        if (preg_match("/^[0-9]*$/",$a)) {
            $datos = true;
        }
        return $datos;
    }
    function limpiezadatos($a)
    {
        try {
            $patron = array('/<script>.*<\/script>/');
            if (is_array($a)) {
                foreach ($a as $key2 => $value) {
                    $a[$Key2] = preg_replace($patron,'', $value);
                    $a[$Key2] = htmlspecialchars($value);
                }
                return $a;
            }
            $a = preg_replace($patron,'', $a);
            $a = htmlspecialchars($a);
            return $a;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function LimpiarEntradas()
    {
        try {
            //code...
            if (isset($_POST)) {
                foreach ($_POST as $key => $value) {
                    $_POST[$key] = limpiezadatos($value);
                }
            }
            if (isset($_GET)) {
                foreach ($_GET as $key => $value) {
                    $_GET[$key] = limpiezadatos($value);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function IniciarSesionSegura()
    {
        //obliga a la sesion a utilizar solo cookeis
        //habilitar este ajuste previene ataques que implican pasar el id de sesion por la url
        if (ini_set('session.use_only_cookies',1) === FALSE) 
        {
            $action = "error";
            $error = "no puedo iniciar una sesion segura (ini_set)";
        }
        //obtenemos los parametors de la cookie de sesion
        $cookieParams = session_get_cookie_params();
        //sesion publicaciones 
        $path = $cookieParams["path"];
        //inicio y control de la sesion
        $secure = false;
        $httponly = true;
        $samesite = "strict";
        session_set_cookie_params([
            'lifetime' => $cookieParams["lifetime"],
            'path' => $path,
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite
        ]);
        session_start();
        session_regenerate_id(TRUE);
    }
    function cerrarsesion()
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
        }
            
        // Finalmente, destruir la sesión.
        session_destroy();
        header('Location:index.php');
    }
     function validarArchivo($a)
    {
        try {
            $nuevonombrefile = NULL;
            if (isset($a) && $a != '') {
                $extension = pathinfo($a['name'], PATHINFO_EXTENSION);
                $extension = strtolower($extension);
                $extension_correcta = ($extension == 'jpg' or $extension == 'jpeg' or $extension == 'gif' or $extension == 'png'or $extension == 'txt'or $extension == 'doc'or $extension == 'docx'
                                        or $extension == 'pdf' or $extension == 'xlsx' or $extension == 'pptx' or $extension == 'mp4' or $extension == 'mp3');
                if ($extension_correcta) {
                    $nuevonombrefile = md5(time().$a['name']).'.'.$extension;
                    $ruta_destino_archivo = "C:\wampportable\UniServerZ\www\proyectoEPD1\uploaded_files\archivos/{$nuevonombrefile}";
                    if (move_uploaded_file($a['tmp_name'], $ruta_destino_archivo)) {
                        if($extension == 'jpg'){
                            $img = imagecreatefromjpeg($ruta_destino_archivo);
                            imagejpeg($img, $ruta_destino_archivo, 100);
                            imagedestroy($img);
                        }
                        if($extension == 'gif'){
                            $img = imagecreatefromgif($ruta_destino_archivo);
                            imagegif($img, $ruta_destino_archivo);
                            imagedestroy($img);
                        }
                        if($extension == 'png'){
                            $img = imagecreatefrompng($ruta_destino_archivo);
                            imagepng($img, $ruta_destino_archivo);
                            imagedestroy($img);
                        }
                    }
                }
            }
            return $nuevonombrefile;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
?>