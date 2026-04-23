<?php
    class db{

    
       private $host="localhost";
        private $db_name="confianz_credito";
        private $user="confianz_credito";
        private $password="*Z)387Bng6agIX";
        private $pdo;




   /*
        private $host="localhost";
        private $db_name="credito";
        private $user="root";
        private $password="";
        private $pdo;
*/



    public function conexion(){
    try{

        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            // LOCAL
            $dsn = "mysql:host=localhost;dbname=credito;charset=utf8";
            $user = "root";
            $password = "";
        } else {
            // PRODUCCIÓN
            $dsn = "mysql:host=localhost;dbname=confianz_credito;charset=utf8";
            $user = "confianz_credito";
            $password = "*Z)387Bng6agIX";
        }

        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->pdo;

    } catch(PDOException $e){
        die("Error de conexión: " . $e->getMessage());
    }
}    





        public function desconectar(){
            $this->pdo = null;
        }
    }


        


?>