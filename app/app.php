<?php
use Phalcon\Mvc\Micro\Collection as MicroCollection;

/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});

/**
 * Not found handler
 */
$app->notFound(function () {
    $this->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo json_encode(['ok' => false, ' error' => 'Service not found']);
});

$auth = new MicroCollection();

$auth->setHandler(new AuthController());
$auth->setPrefix('/auth');

$auth->post('/login', 'login');

$app->mount($auth);

$clients = new MicroCollection();

$clients->setHandler(new ClientController());
$clients->setPrefix('/clients');

$clients->get('/', 'getClients');
$clients->post('/', 'createClient');
$clients->put('/{id:[0-9]+}', 'updateClient');
$clients->delete('/{id:[0-9]+}', 'deleteClient');

$app->mount($clients);
