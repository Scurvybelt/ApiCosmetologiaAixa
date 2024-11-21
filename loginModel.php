<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

class loginModel {
    private $conexion;
    
    public function __construct() {
        $this->conexion = new mysqli('127.0.0.1', 'root', 'root', 'cosmetologia');
        if ($this->conexion->connect_error) {
            throw new Exception("Connection failed: " . $this->conexion->connect_error);
        }
    
        mysqli_set_charset($this->conexion, 'utf8mb4');
    }
    
    public function getUsers() {
        $query = "SELECT * FROM users";
        $result = $this->conexion->query($query);
        if ($result === false) {
            throw new Exception("Error fetching users: " . $this->conexion->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function saveUsers($user, $password, $email) {
        if (empty($user) || empty($password) || empty($email)) {
            return ['error', 'Todos los campos son obligatorios'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error', 'Formato de correo electrónico inválido'];
        }
        
        $stmt = $this->conexion->prepare("SELECT id FROM users WHERE user = ? OR email = ?");
        $stmt->bind_param("ss", $user, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['error', 'Ya existe un usuario con ese nombre o correo electrónico'];
        }
        
        // Hash (PASSWORD_BCRYPT o PASSWORD_ARGON2)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->conexion->prepare("INSERT INTO users (user, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user, $hashed_password, $email);
        
        if ($stmt->execute()) {
            return ['success', 'Usuario guardado exitosamente'];
        } else {
            return ['error', 'Error al guardar el usuario: ' . $stmt->error];
        }
    }
    
    public function updateUser($id, $username, $email, $is_admin = null, $active = null) {
        if (empty($username) || empty($email)) {
            return ['error', 'Todos los campos son obligatorios'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error', 'Formato de correo electrónico inválido'];
        }
        
        $stmt = $this->conexion->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $existe = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        if (count($existe) === 0) {
            return ['error', 'No existe el usuario con ID ' . $id];
        }
        
        $stmt = $this->conexion->prepare("SELECT id FROM users WHERE (user = ? OR email = ?) AND id != ?");
        $stmt->bind_param("ssi", $username, $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['error', 'Ya existe un usuario con ese nombre o correo electrónico'];
        }
        
        if ($is_admin !== null && $active !== null) {
            $stmt = $this->conexion->prepare("UPDATE users SET user = ?, email = ?, is_admin = ?, active = ? WHERE id = ?");
            $stmt->bind_param("ssbii", $username, $email, $is_admin, $active, $id);
        } elseif ($is_admin !== null) {
            $stmt = $this->conexion->prepare("UPDATE users SET user = ?, email = ?, is_admin = ? WHERE id = ?");
            $stmt->bind_param("ssbi", $username, $email, $is_admin, $id);
        } elseif ($active !== null) {
            $stmt = $this->conexion->prepare("UPDATE users SET user = ?, email = ?, active = ? WHERE id = ?");
            $stmt->bind_param("ssii", $username, $email, $active, $id);
        } else {
            $stmt = $this->conexion->prepare("UPDATE users SET user = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $email, $id);
        }
        
        if ($stmt->execute()) {
            return ['success', 'Usuario actualizado'];
        } else {
            return ['error', 'Error al actualizar el usuario: ' . $stmt->error];
        }
    }
    
    public function updateUserPassword($id, $current_password, $new_password) {
        if (empty($id) || empty($current_password) || empty($new_password)) {
            return ['error', 'Todos los campos son obligatorios'];
        }
        
        $stmt = $this->conexion->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['error', 'Usuario no encontrado'];
        }
        
        $user = $result->fetch_assoc();
        
        if (!password_verify($current_password, $user['password'])) {
            return ['error', 'Contraseña actual incorrecta'];
        }
        
        // Hash (PASSWORD_BCRYPT o PASSWORD_ARGON2)
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt = $this->conexion->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hashed_password, $id);
        
        if ($stmt->execute()) {
            return ['success', 'Contraseña actualizada exitosamente'];
        } else {
            return ['error', 'Error al actualizar la contraseña: ' . $stmt->error];
        }
    }
    
    public function deleteUser($id) {
        if (empty($id)) {
            return ['error', 'ID de usuario no proporcionado'];
        }
        
        $stmt = $this->conexion->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $valida = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        if (count($valida) === 0) {
            return ['error', 'No existe el usuario con ID ' . $id];
        }
        
        $admin_count_stmt = $this->conexion->prepare("SELECT COUNT(*) as admin_count FROM users WHERE is_admin = 1");
        $admin_count_stmt->execute();
        $admin_count_result = $admin_count_stmt->get_result()->fetch_assoc();
        
        if ($admin_count_result['admin_count'] <= 1 && $valida[0]['is_admin'] == 1) {
            return ['error', 'No se puede eliminar el último usuario administrador'];
        }
        
        $stmt = $this->conexion->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success', 'Usuario eliminado'];
        } else {
            return ['error', 'Error al eliminar el usuario: ' . $stmt->error];
        }
    }
    
    // Validación de usuario (LogIn)
    public function validateUser($username, $password) {
        if (empty($username) || empty($password)) {
            return ['error', 'Nombre de usuario y contraseña son obligatorios'];
        }
        
        $stmt = $this->conexion->prepare("SELECT * FROM users WHERE user = ? AND active = 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['error', 'Usuario no encontrado o inactivo'];
        }
        
        $user = $result->fetch_assoc();
        
        if (!password_verify($password, $user['password'])) {
            return ['error', 'Contraseña incorrecta'];
        }
        
        $update_stmt = $this->conexion->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
        $update_stmt->bind_param("i", $user['id']);
        $update_stmt->execute();
        
        unset($user['password']);
        
        return [
            'success', 
            'Inicio de sesión exitoso', 
            'user' => $user
        ];
    }
}
?>