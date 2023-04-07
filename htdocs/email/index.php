<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
function enviarmailextracto($correo, $listaextracto){

$mail = new PHPMailer(true);
    try {
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pruebasdatos123@gmail.com';
        $mail->Password = 'rzaxliziozbqgppr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('pruebasdatos123@gmail.com', 'Banco Chia');
        $mail->addAddress($correo['Correo'], 'Receptor');
        //$mail->addCC('concopia@gmail.com');

        //$mail->addAttachment('docs/dashboard.png', 'Dashboard.png');

        $mail->isHTML(true);
        $mail->Subject = 'Prueba desde GMAIL';
        $mail->Body = '<!DOCTYPE html>
        <html>
        <body>
        <h1 align="center">Extracto Mensual</h1>
        <table style="table-layout: fixed; width: 50%; border-collapse: collapse; border: 3px solid purple;" align="center">
        <tr>
            <th>Fecha Movimiento</th>
            <th>Movimiento</th>
            <th>Valor</th>
        </tr>';
        $mail->Body .= $listaextracto;
        $mail->Body .= '
        </body>
		</table>
		</body>
		</html>';
        $mail->send();

        echo 'Correo enviado';
        return true;
    } catch (Exception $e) {
        echo 'Mensaje ' . $mail->ErrorInfo;
        return false;
    }
}
function enviarmailreporte($correo, $listaextracto){

    $mail = new PHPMailer(true);
    try {
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pruebasdatos123@gmail.com';
        $mail->Password = 'PruebasDatos2711';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('pruebasdatos123@gmail.com', 'Banco Chia');
        $mail->addAddress($correo['Correo'], 'Receptor');
        //$mail->addCC('concopia@gmail.com');

        //$mail->addAttachment('docs/dashboard.png', 'Dashboard.png');

        $mail->isHTML(true);
        $mail->Subject = 'Prueba desde GMAIL';
        $mail->Body = '<!DOCTYPE html>
        <html>
        <body>
        <h1 align="center">Extracto Mensual</h1>
        <table style="table-layout: fixed; width: 50%; border-collapse: collapse; border: 3px solid purple;" align="center">
        <tr>
            <th>Fecha Movimiento</th>
            <th>Movimiento</th>
            <th>Valor</th>
        </tr>';
        $mail->Body .= $listaextracto;
        $mail->Body .= '
        </body>
        </table>
        </body>
        </html>';
        $mail->send();

        echo 'Correo enviado';
        return true;
    } catch (Exception $e) {
        echo 'Mensaje ' . $mail->ErrorInfo;
        return false;
    }
}
function enviartoken($token, $correo){

    $mail = new PHPMailer(true);
    try {
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pruebasdatos123@gmail.com';
        $mail->Password = 'rzaxliziozbqgppr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('pruebasdatos123@gmail.com', 'Banco Chia');
        $mail->addAddress($correo, 'Receptor');
        //$mail->addCC('concopia@gmail.com');

        //$mail->addAttachment('docs/dashboard.png', 'Dashboard.png');

        $mail->isHTML(true);
        $mail->Subject = 'token restablecimiento de contraseña';
        $mail->Body = '<!DOCTYPE html>
        <html>
        <body>
        <h1 align="center">TOKEN</h1>
        <table style="table-layout: fixed; width: 50%; border-collapse: collapse; border: 3px solid purple;" align="center">
        <tr>
            <th>SU TOKEN</th>
        </tr>
        <tr>
            <td align="center">'.$token['Token'].'</td>
        </tr>
        </table>
        </body>
        </html>';
        $mail->send();

        echo 'Correo enviado';
        return true;
    } catch (Exception $e) {
        echo 'Mensaje ' . $mail->ErrorInfo;
        return false;
    }
}
