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
include("functions/F_Login.php");
include("functions/F_Deplacements.php");

// Not found function
$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(404, "Not Found");
        $app->response->sendHeaders();
        echo "This page doesn't exist!";
    }
);

$app->handle();