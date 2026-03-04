<?php

class usuarioController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/usuarioModel.php');
        $this->model = new usuarioModel();
    }

    public function insertar($identificacion, $nombres, $direccion, $telefono, $calificacion = null, $observacion = null){
        return $this->model->insertar($identificacion, $nombres, $direccion, $telefono, $calificacion, $observacion);
    }

    public function actualizarUsuario($id_persona, $identificacion, $nombres, $direccion, $telefono, $calificacion = null, $observacion = null){
        return $this->model->actualizar($id_persona, $identificacion, $nombres, $direccion, $telefono, $calificacion, $observacion);
    }

    public function eliminar($id_persona){
        return $this->model->eliminar($id_persona);
    }


    public function buscarId($id_persona){
        return $this->model->buscarId($id_persona);
    }

    public function listarTodos(){
        return $this->model->lstarTodos();
    }

  public function login($email, $password){
        //Aquí puedes implementar la lógica de autenticación, por ejemplo, verificando el email y password en la base de datos.
        $user = $this->model->login($email, $password);
        if ($user) {
            // Si la autenticación es exitosa, puedes iniciar una sesión o devolver un token de autenticación.
             $token = bin2hex(random_bytes(32));
            
            session_start();
            $_SESSION['usuario'] = $user;
            $_SESSION['token'] = $token;
            $this->model->guardarToken($user['id_persona'], $token );
            // return as array; let caller encode to JSON
            return array('token' => $token, 'usuario' => $user['nombres']);
        } else {
            // Si la autenticación falla, puedes devolver un mensaje de error o redirigir al usuario a una página de inicio de sesión.
            return false;
        }
    }

    public function validarToken($token){
        return $this->model->validarToken($token);
    }






}

?>