<?php
    class db{
        private $host="localhost";
        private $db_name="credito";
        private $user="root";
        private $password="";
        private $pdo;

        public function conexion(){
            try{
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
                $this->pdo = new PDO($dsn, $this->user, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->pdo;
            }catch(PDOException $e){
                return $e->getMessage();
            }
        }

        public function desconectar(){
            $this->pdo = null;
        }
    }


        


?>