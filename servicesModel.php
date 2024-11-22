<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';


class servicesModel{
    public $conexion;
    public function __construct(){
        $this->conexion = new mysqli('127.0.0.1','root','root','cosmetologia');
        mysqli_set_charset($this->conexion,'utf8');
    }

    public function getServices() {
        $query = "SELECT * FROM services";
        $result = $this->conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getServicesById($id) {
        $query = "SELECT * FROM services WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getServicesByCategory($category) {
        $query = "SELECT * FROM services WHERE category = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getServicesByName($name) {
        $query = "SELECT * FROM services WHERE name = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getServicesByNameAndCategory($name, $category) {
        $query = "SELECT * FROM services WHERE name = ? AND category = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ss", $name, $category);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getServicesByIdAndCategory($id, $category) {
        $query = "SELECT * FROM services WHERE id = ? AND category = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("is", $id, $category);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function saveServices($name, $category, $price, $description, $information) {
        $valida = $this->validateServices($name, $category);
        $resultado = ['error', 'Ya existe un servicio con ese nombre en esa categoría'];
        if (count($valida) == 0) {
            $sql = "INSERT INTO services (name, description, price, category, information) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ssdss", $name, $description, $price, $category, $information);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $resultado = ['success', 'Servicio creado exitosamente'];
            } else {
                $resultado = ['error', 'Error al crear el servicio'];
            }
            $stmt->close();
        }
        return $resultado;
    }

    public function updateServices($id, $name, $category, $price, $description, $information) {
        $existe = $this->getServices($id);
        $resultado = ['error', 'No existe el servicio con ID ' . $id];
        if (count($existe) > 0) {
            $sql = "UPDATE services SET name = ?, category = ?, price = ?, description = ?, information = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ssdssi", $name, $category, $price, $description, $information, $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $resultado = ['success', 'Servicio actualizado'];
            } else {
                $resultado = ['error', 'Error al actualizar el servicio'];
            }
            $stmt->close();
        }
        return $resultado;
    }
    
    public function deleteServices($id){
        $valida = $this->getServices($id);
        $resultado = ['error', 'No existe el servicio con ID ' . $id];
        
        if (count($valida) > 0) {
            $sql = "DELETE FROM services WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    $resultado = ['success', 'Servicio eliminado'];
                } else {
                    $resultado = ['error', 'Error al eliminar el servicio'];
                }
                $stmt->close();
            } else {
                $resultado = ['error', 'Error al preparar la consulta'];
            }
        }
        
        return $resultado;
    }
    
    public function validateServices($name,$category){
        $services=[];
        $sql="SELECT * FROM services WHERE name='$name' AND category='$category'";
        $registos = mysqli_query($this->conexion,$sql);
        while($row = mysqli_fetch_assoc($registos)){
            array_push($services,$row);
        }
        return $services;
    }

    public function getServiceByCategory($category){
        $services=[];
        $sql = "SELECT * FROM services WHERE category='$category'";
        $registos = mysqli_query($this->conexion,$sql);
        while($row = mysqli_fetch_assoc($registos)){
            array_push($services,$row);
        }
        return $services;
    }


    public function sendEmail($asunto,$email,$message,$name,$tel){
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'eduardoavilat2002@gmail.com';                     //SMTP username
            $mail->Password   = 'fyohvokyieibtuwm';                         //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // ENCRYPTION_SMTPS 464 Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('eduardoavilat2002@gmail.com', 'Equipo Ti Aixa'); //quien lo manda
            $mail->addAddress('eduardoavilat2002@gmail.com', 'Eduardo');     //Add a recipient quien lo recibe

        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body = '
            <h3>Hola, soy ' . $name . '</h3>
            <p><strong>Email:</strong> ' . $email . '</p>
            <p><strong>Teléfono:</strong> ' . $tel . '</p>
            <p><strong>Mensaje:</strong></p>
            <p>' . nl2br($message) . '</p>
            ';
        
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            

            if($mail->send()){
                // $_SESSION['stats'] = "thank you contact us - Team Aixa of web Ti";
                // header("Location: {$_SERVER['HTTP_REFERER']}");
                // exit(0);
                return ['success', 'Mensaje enviado'];
            }else{
                return ['error', 'Error al enviar el mensaje'];
                // $_SESSION['stats'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                // header("Location: {$_SERVER['HTTP_REFERER']}");
                // exit(0);
            }
            // echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>