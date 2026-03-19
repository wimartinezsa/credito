<?php

class sociedadModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }




public function listarTodasSociedades(){

         
            $stament = $this->PDO->prepare("SELECT * FROM sociedades left join personas on encargado=id_persona ");
            $stament->execute();

            return $stament->fetchAll(PDO::FETCH_ASSOC);
}




public function listarSociedadesEncargados(){

           // session_start();
            $user = $_SESSION['usuario'];
            $stament = $this->PDO->prepare("SELECT * FROM sociedades WHERE encargado = ?");
            $stament->execute([$user['id_persona']]);

            return $stament->fetchAll(PDO::FETCH_ASSOC);
}
    


public function listarPerosnasEncargados(){
      try {
            $stament = $this->PDO->prepare("SELECT * FROM personas ");
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
            } 
            catch (Exception $e) {
            return $e->getMessage();
            }
}





public function asignarEncargadoSociedad($id_sociedad, $encargado, $rol){

    try {     

        if (empty($id_sociedad) || empty($encargado) || empty($rol)) {
            throw new Exception("Datos incompletos");
        }

        $this->PDO->beginTransaction();

        // =========================
        // 1. VALIDAR PERSONA
        // =========================
        $stmt_persona = $this->PDO->prepare("
            SELECT id_persona FROM personas WHERE id_persona = ?
        ");
        $stmt_persona->execute([$encargado]);

        if (!$stmt_persona->fetch()) {
            throw new Exception("La persona no existe");
        }

        // =========================
        // 2. VALIDAR SOCIEDAD
        // =========================
        $stmt_sociedad = $this->PDO->prepare("
            SELECT id_sociedad FROM sociedades WHERE id_sociedad = ?
        ");
        $stmt_sociedad->execute([$id_sociedad]);

        if (!$stmt_sociedad->fetch()) {
            throw new Exception("La sociedad no existe");
        }

        // =========================
        // 1. SE CONSULTA EL USUARIO
        // =========================

        $stmt_persona = $this->PDO->prepare("
        SELECT identificacion FROM personas WHERE id_persona = ? ");
        $stmt_persona->execute([ $encargado]);
        $resultado = $stmt_persona->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            throw new Exception("Persona no encontrada");
        }

        $identificacion = $resultado['identificacion'];



        // =========================
        // 2. ACTUALIZAR PERSONA (ROL)
        // =========================
        $stmt_persona_update = $this->PDO->prepare("
            UPDATE personas 
            SET rol = ?,
            password=?
            WHERE id_persona = ?
        ");

        $stmt_persona_update->execute([
            $rol,
            $identificacion,
            $encargado
        ]);

        // =========================
        // 4. ACTUALIZAR SOCIEDAD
        // =========================
        $stmt_sociedad_update = $this->PDO->prepare("
            UPDATE sociedades 
            SET encargado = ?
            WHERE id_sociedad = ?
        ");

        $stmt_sociedad_update->execute([
            $encargado,
            $id_sociedad
        ]);

        $this->PDO->commit();

        return "Encargado asignado correctamente";

    } catch (Exception $e) {

        $this->PDO->rollBack();
        return $e->getMessage();
    }
}    
        




        public function registrarSociedades($nombre, $valor){
            $stament = $this->PDO->prepare("INSERT INTO sociedades (sociedad,caja) VALUES (:nombre,:valor)");
            $stament->bindParam(':nombre', $nombre);
            $stament->bindParam(':valor', $valor);  
            $stament->execute();
            return true;
        }

        public function buscarSociedad($id){
            $stament = $this->PDO->prepare("SELECT * FROM sociedades WHERE id_sociedad = :id");
            $stament->bindParam(':id', $id);
            $stament->execute();
            return $stament->fetch(PDO::FETCH_ASSOC);
        }   



public function adicionarSociedad($id_sociedad,$nombre, $valor){

    try {

        if (!is_numeric($valor) || $valor <= 0) {
            throw new Exception("El valor debe ser mayor a 0");
        }

        $this->PDO->beginTransaction();

        // =========================
        // 1. OBTENER CAJA ACTUAL
        // =========================
        $stmt_sociedad = $this->PDO->prepare("
            SELECT caja FROM sociedades WHERE id_sociedad = ?
        ");
        $stmt_sociedad->execute([$id_sociedad]);
        $resultado = $stmt_sociedad->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            throw new Exception("Sociedad no encontrada");
        }

        $caja = $resultado['caja'];
        $nuevaCaja = $caja + $valor;

        // =========================
        // 2. REGISTRAR MOVIMIENTO
        // =========================
        $stmt_movimiento = $this->PDO->prepare("
            INSERT INTO movimientos 
            (fecha, sociedad, valor, caja, tipo, estado) 
            VALUES (CURDATE(), ?, ?, ?, ?, ?)
        ");

        $stmt_movimiento->execute([
            $id_sociedad,
            $valor,
            $nuevaCaja,
            "adicion",
            "ejecutado"
        ]);

        // =========================
        // 3. ACTUALIZAR CAJA
        // =========================
        $stmt_sociedad_update = $this->PDO->prepare("
            UPDATE sociedades 
            SET caja = caja + ? , sociedad=?
            WHERE id_sociedad = ?
        ");

        $stmt_sociedad_update->execute([
            $valor,
            $nombre,
            $id_sociedad
        ]);

        $this->PDO->commit();

        return "Adición realizada con éxito";

    } catch (Exception $e) {

        $this->PDO->rollBack();
        return $e->getMessage();
    }
}






        public function disponibleSociedad($id_sociedad){

        $stament = $this->PDO->prepare("SELECT caja
        FROM sociedades s
        WHERE s.id_sociedad = :id_sociedad;");
        $stament->bindParam(':id_sociedad', $id_sociedad);
        $stament->execute();
        return $stament->fetch(PDO::FETCH_ASSOC);
        }
        
}




?>