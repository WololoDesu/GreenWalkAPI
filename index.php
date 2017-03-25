<?php
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

// Use Loader() to autoload our models
$loader = new Loader();

$loader->registerNamespaces(
    [
        "API" => __DIR__ . "/models/",
    ]
);

$loader->register();

$di = new FactoryDefault();

// Set up the database service
$di->set(
    "db",
    function () {
        return new PdoMysql(
            [
                "host" => "localhost",
                "username" => "root",
                "password" => "",
                "dbname" => "greenwalk",
                "charset" => "utf8",
            ]
        );
    }
);

// Create and bind the DI to the application
$app = new Micro($di);

// Part included
include("functions/F_Users.php");
include("functions/F_Teams.php");
include("functions/F_Transports.php");
include("functions/F_Achievements.php");

//login
$app->post(
    "/login",
    function () use ($app) {
        $logs = $app->request->getJsonRawBody();
        $phql = "SELECT idUser, password FROM API\\Users WHERE mail = :login: OR pseudo =:login:";
        $user = $app->modelsManager->executeQuery(
            $phql,
            [
                "login" => $logs->login,
            ]
        )->getFirst();
        if ($user === false || $logs->password != $user->password) {
            $data[] = [
                "status" => false,
                "reason" => "Incorrect login or password",
            ];
        } else {
            $data[] = [
                "status" => true,
                "id" => $user->idUser,
            ];
        }
        echo json_encode($data);
    }
);

// Not found function
$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(404, "Not Found");
        $app->response->sendHeaders();
        echo "This page doesn't exist!";
    }
);

$app->handle();