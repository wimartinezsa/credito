
<?php
require_once("../../controller/sociedadController.php");
require_once("../../controller/autenticacionController.php");
$controller_sociedad = new sociedadController();
$controller_autenticacion = new autenticacionController();


   



// Token válido, procesar solicitud
// 🔹 Obtener headers
    $headers = getallheaders();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_sociedad = $_GET['id_sociedad'] ?? null;
    if (!$id_sociedad) {
        http_response_code(400);
        echo json_encode(["error" => "ID de sociedad no proporcionado"]);
        exit;
    }

     // 🔹 Validar que venga Authorization
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token no enviado"
        ]);
        exit;
    }

    // 🔹 Extraer token (Bearer XXXXX)
    $token = str_replace('Bearer ', '', $headers['Authorization']);

    // 🔹 Validar token
    $usuario = $controller_autenticacion->validarToken($token);

    if (!$usuario) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token inválido o expirado"
        ]);
        exit;
    }






    $resultado = $controller_sociedad->disponibleSociedad( $id_sociedad );
    echo json_encode($resultado);
}


?>