<?php

/**
 * Description of Authentication
 *
 * @author arturo
 */
class Authentication extends Phalcon\Mvc\User\Component {
    /**
     * Valida que en la cabecera HTTP "Authentication" haya aun token de autenticación válido
     * @return Array asociativo con los datos del usuario (decodificados). Si hay error -> id => -1 y error => "Mensaje de error" 
     */
    public function validaToken() {
        $error = '';
        
        if(!array_key_exists('Authorization',getallheaders())) {
            //return [id => -1, 'error' => 'No se ha iniciado sesión en la aplicación'];
            $error = 'No se ha iniciado sesión en la aplicación';
        } else {
            $authorization = getallheaders()['Authorization'];
            $trozos = explode(' ', $authorization);
            $auth = $trozos[1];
            try {
                $decoded = \Firebase\JWT\JWT::decode($auth, base64_decode($this->config->application->jwtKey), ['HS256']);
            } catch(\Firebase\JWT\SignatureInvalidException $e) {
                //return [id => -1, 'error' => 'El token de autenticación no es válido'];
                $error = 'No se ha iniciado sesión en la aplicación';
            } catch(\Firebase\JWT\ExpiredException $e) {
                //return [id => -1, 'error' => 'El token de autenticación ha caducado'];
                $error = 'La sesión ha caducado';
            }
        }
        
        if($error !== '') {
            $this->response->setStatusCode(403, "Not Authenticated");
            $this->response->setContent(json_encode(['ok' => false, 'error' => $error]));
            $this->response->send();
            exit();
        }
       
        return $decoded;
    }
    
    /**
     * Genera un token para la autenticación del usuario en la aplicación
     * @param $usuario Objeto del modelo con los datos del usuario autenticado
     * @return Token generado con JWT. Caduca al mes.
     */
    public function generaToken(Client $usuario) {
        $tiempo = time();
        
        $token = \Firebase\JWT\JWT::encode(
            [
                'exp' => $tiempo + 60*60*24*30, // Caduca a los 30 días
                'id' => $usuario->getId(), 
                'name' => $usuario->getName(), 
                'email' => $usuario->getEmail(),
            ], 
            base64_decode($this->config->application->jwtKey), // Clave JWT
            'HS256'     // Algoritmo de codificación del token
        );
        
        return $token;
    }
    
    public function renuevaToken() {
        $decoded = $this->validaToken();
        
        if($decoded->id < 0) {
            return ['ok' => false, 'error' => $decoded->error];
        }
        
        $tiempo = time();
        
        $token = \Firebase\JWT\JWT::encode(
            [
                'iat' => $tiempo, // Fecha y hora que se genera el token
                'exp' => $tiempo + 60*60*24*30, // Caduca a los 30 días
                'id' => $decoded->id, 
                'nombre' => $decoded->name, 
                'email' => $decoded->email
            ], 
            base64_decode($this->config->application->jwtKey), // Clave JWT
            'HS256'     // Algoritmo de codificación del token
        );
        
        return ['ok' => true, 'token' => $token];
    }
}
