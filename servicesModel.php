<?php
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
    
    public function saveServices($name,$description,$price,$category,$img){
        $valida = $this->validateServices($name,$description,$price);
        $resultado=['error','Ya existe un producto las mismas características'];
        if(count($valida)==0){
            $sql="INSERT INTO services(name,description,price,category,img) VALUES('$name','$description','$price','$category',$img')";
            mysqli_query($this->conexion,$sql);
            $resultado=['success','Producto guardado'];
        }
        return $resultado;
    }

    public function updateServices($id,$name,$description,$price){
        $existe= $this->getServices($id);
        $resultado=['error','No existe el producto con ID '.$id];
        if(count($existe)>0){
            $valida = $this->validateServices($name,$description,$price);
            $resultado=['error','Ya existe un producto las mismas características'];
            if(count($valida)==0){
                $sql="UPDATE services SET name='$name',description='$description',price='$price' WHERE id='$id' ";
                mysqli_query($this->conexion,$sql);
                $resultado=['success','Producto actualizado'];
            }
        }
        return $resultado;
    }
    
    public function deleteServices($id){
        $valida = $this->getServices($id);
        $resultado=['error','No existe el producto con ID '.$id];
        if(count($valida)>0){
            $sql="DELETE FROM services WHERE id='$id' ";
            mysqli_query($this->conexion,$sql);
            $resultado=['success','Producto eliminado'];
        }
        return $resultado;
    }
    
    public function validateServices($name,$description,$price){
        $services=[];
        $sql="SELECT * FROM services WHERE name='$name' AND description='$description' AND price='$price' ";
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
}