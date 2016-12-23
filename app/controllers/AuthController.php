<?php

class AuthController extends \Phalcon\Mvc\Controller {

   public function login() {
       $data = $this->request->getJsonRawBody();
       $client = Client::findFirst([
           'email = ?0',
           'bind' => [$data->email]
        ]);
       if(!$client) {
           exit(json_encode(['ok' => false, 'error' => "User not found!"]));
       }
       
       if($client->getPassword() !== hash('sha256', $data->password)) {
           exit(json_encode(['ok' => false, 'error' => "Password not correct!"]));
       }
       
       $token = $this->authentication->generaToken($client);
       echo json_encode(['ok' => true, 'token' => $token]);
   }

}
