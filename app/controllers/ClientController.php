<?php

class ClientController extends \Phalcon\Mvc\Controller {
    public function getClients() {
        $this->authentication->validaToken();
        echo json_encode(Client::find([
                'columns' => ['id', 'name', 'email']
            ]), JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
    }

    public function createClient() {
        $this->authentication->validaToken();
        $data = $this->request->getJsonRawBody();
        $client = new Client();
        $client->setEmail($data->email);
        $client->setName($data->name);
        $client->setPassword(hash('sha256', $data->password));

        if ($client->create()) {
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false]);
        }
    }

    public function updateClient($id) {
        $this->authentication->validaToken();
        $client = Client::findFirst('id = ' . $id);
        if (!$client) {
            exit(json_encode(['ok' => false, 'error' => "This client doesn't exist"]));
        }

        $data = $this->request->getJsonRawBody();
        if (isset($data->name)) {
            $client->setName($data->name);
        }
        if (isset($data->email)) {
            $client->setEmail($data->email);
        }

        if ($client->update()) {
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false, 'error' => "The client couldn't be updated."]);
        }
    }

    public function deleteClient($id) {
        $this->authentication->validaToken();
        $client = Client::findFirst('id = ' . $id);
        if (!$client) {
            exit(json_encode(['ok' => false, 'error' => "This client doesn't exist"]));
        }

        if ($client->delete()) {
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false, 'error' => "The client couldn't be deleted."]);
        }
    }

}
