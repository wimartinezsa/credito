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

         
            $stament = $this->PDO->prepare("SELECT 
    s.id_sociedad,
    s.caja,
    s.sociedad,
    GROUP_CONCAT(p.nombres SEPARATOR ', ') AS administrador
FROM sociedades s
LEFT JOIN administradores a ON a.sociedad = s.id_sociedad
LEFT JOIN personas p ON p.id_persona = a.persona
GROUP BY 
    s.id_sociedad, s.caja, s.sociedad");
            $stament->execute();

            return $stament->fetchAll(PDO::FETCH_ASSOC);
}





public function listarEncargadosSociedadesId($id_sociedad){

 
            $stament = $this->PDO->prepare("
              SELECT a.id_administrador,p.nombres,p.rol FROM sociedades s
              JOIN administradores a ON a.sociedad=s.id_sociedad
              JOIN personas p ON p.id_persona=a.persona
              WHERE a.sociedad=?");
            $stament->execute([$id_sociedad]);

            return $stament->fetchAll(PDO::FETCH_ASSOC);
}



public function listarSociedadesEncargados(){

           // session_start();
            $user = $_SESSION['usuario'];
            $stament = $this->PDO->prepare("
            SELECT s.id_sociedad,s.sociedad FROM sociedades s
            JOIN administradores a ON a.sociedad=s.id_sociedad
            WHERE a.persona=?");
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
            SELECT id_persona, identificacion 
            FROM personas 
            WHERE id_persona = ?
        ");
        $stmt_persona->execute([$encargado]);

        $persona = $stmt_persona->fetch(PDO::FETCH_ASSOC);

        if (!$persona) {
            throw new Exception("La persona no existe");
        }

        // =========================
        // 2. ACTUALIZAR PERSONA (ROL + PASSWORD)
        // =========================
        $stmt_persona_update = $this->PDO->prepare("
            UPDATE personas 
            SET rol = ?, password = ?
            WHERE id_persona = ?
        ");

        $stmt_persona_update->execute([
            $rol,
            $persona['identificacion'], // 🔐 buena práctica
            $encargado
        ]);

        // =========================
        // 3. VALIDAR SI YA ES ADMIN
        // =========================
        $stmt_validar = $this->PDO->prepare("
            SELECT * FROM administradores 
            WHERE sociedad = ? AND persona = ?
        ");
        $stmt_validar->execute([$id_sociedad, $encargado]);

        if ($stmt_validar->fetch()) {
            throw new Exception("La persona ya está asignada a esta sociedad");
        }

        // =========================
        // 4. INSERTAR ADMINISTRADOR
        // =========================
        $stmt_admin = $this->PDO->prepare("
            INSERT INTO administradores (sociedad, persona)
            VALUES (?, ?)
        ");

        $stmt_admin->execute([
            $id_sociedad,
            $encargado
        ]);

        $this->PDO->commit();

        return "Encargado asignado correctamente";

    } catch (Exception $e) {

        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

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


public function eliminarEncargadoSociedad($id_admin){

    $stament = $this->PDO->prepare("
        DELETE FROM administradores 
        WHERE id_administrador = :id
    ");

    $stament->bindParam(':id', $id_admin, PDO::PARAM_INT);
    $stament->execute();

    return "Encargado de la sociedad eliminado con éxito";
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