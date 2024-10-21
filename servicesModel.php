<?php
class servicesModel{
    public $conexion;
    public function __construct(){
        $this->conexion = new mysqli('127.0.0.1','root','root','cosmetologia');
        mysqli_set_charset($this->conexion,'utf8');
    }

    public function getServices($id=null){
        $where = ($id == null) ? "" : " WHERE id='$id'";
        $services=[];
        $sql="SELECT * FROM services ".$where;
        $registos = mysqli_query($this->conexion,$sql);
        while($row = mysqli_fetch_assoc($registos)){
            array_push($services,$row);
        }
        return $services;
    }

    public function saveServices($name,$description,$price,$amount,$img){
        $valida = $this->validateServices($name,$description,$price);
        $resultado=['error','Ya existe un producto las mismas características'];
        if(count($valida)==0){
            $sql="INSERT INTO services(name,description,price,amount,img) VALUES('$name','$description','$price','$amount','$img')";
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
}