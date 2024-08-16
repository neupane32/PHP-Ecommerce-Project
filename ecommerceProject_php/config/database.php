<?php
class Database{
    private $host="localhost";
    private $username="root";
    private $password="";
    private $db_name="project";

    public $conn;

    public function getConnection(){
        $this->conn=null;
        $this->conn=new mysqli($this->host,$this->username,$this->password,$this->db_name);

        if($this->conn->connect_error){
            die("Connection error:".$this->conn->connect_error);
        }
        return $this->conn;
    }
}

?>