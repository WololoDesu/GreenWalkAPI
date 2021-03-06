<?php
use Phalcon\Http\Response;

/*************************************************************************************/
/*								  USERS FUNCTIONS									 */
/*																					 */
/*************************************************************************************/

// Retrieves all users
$app->get(
    "/users",
    function () use ($app) {
        //User query
        $phql = "SELECT * FROM API\\Users ORDER BY nom";
        $users = $app->modelsManager->executeQuery($phql);
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                "id" => $user->idUser,
                "nom" => $user->nom,
                "prenom" => $user->prenom,
                "pseudo" => $user->pseudo,
                "mail" => $user->mail,
                "creationDate" => $user->creationDate,
                "connexionDate" => $user->connexionDate,
                "score" => $user->score,
                "idTeam" => $user->idTeam,
            ];
        }
        echo json_encode($data);
    }
);

// Searches for users with $key in their name or email or pseudo
$app->get(
    "/users/search/{key}",
    function ($key) use ($app) {
        $phql = "SELECT * FROM API\\Users WHERE nom LIKE :key: OR prenom LIKE :key: 
                      OR mail LIKE :key: OR pseudo LIKE :key: ORDER BY nom";
        $users = $app->modelsManager->executeQuery(
            $phql,
            [
                "key" => "%" . $key . "%"
            ]
        );
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                "id" => $user->idUser,
                "nom" => $user->nom,
                "prenom" => $user->prenom,
                "pseudo" => $user->pseudo,
                "mail" => $user->mail,
                "creationDate" => $user->creationDate,
                "connexionDate" => $user->connexionDate,
                "score" => $user->score,
                "idTeam" => $user->idTeam,
            ];
        }
        echo json_encode($data);
    }
);

// Retrieves user based on primary key
$app->get(
    "/users/{id:[0-9]+}",
    function ($id) use ($app) {
        $phql = "SELECT * FROM API\\Users WHERE idUser = :id:";
        $user = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
            ]
        )->getFirst();
        // Create a response
        $response = new Response();
        if ($user === false) {
            $response->setJsonContent(
                [
                    "status" => "NOT-FOUND"
                ]
            );
        } else {
            $response->setJsonContent(
                [
                    "status" => "FOUND",
                    "data" => [
                        "id" => $user->idUser,
                        "nom" => $user->nom,
                        "prenom" => $user->prenom,
                        "pseudo" => $user->pseudo,
                        "mail" => $user->mail,
                        "creationDate" => $user->creationDate,
                        "connexionDate" => $user->connexionDate,
                        "score" => $user->score,
                        "idTeam" => $user->idTeam,
                    ]
                ]
            );
        }
        return $response;
    }
);

// Adds a new user
$app->post(
    "/users",
    function () use ($app) {
        $user = $app->request->getJsonRawBody();
        $phql = "INSERT INTO API\\Users (nom, prenom, pseudo, mail, password, creationDate, connexionDate,score)
                VALUES (:nom:, :prenom:, :pseudo:, :mail:, :password:, :date:, :date:, 100)";
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                "nom" => $user->nom,
                "prenom" => $user->prenom,
                "pseudo" => $user->pseudo,
                "mail" => $user->mail,
                "password" => $user->password,
                "date" => date("Y-m-d"),
            ]
        );
        // Create a response
        $response = new Response();
        // Check if the insertion was successful
        if ($status->success() === true) {
            // Change the HTTP status
            $response->setStatusCode(201, "Created");
            $id = $status->getModel()->idUser;
            $response->setJsonContent(
                [
                    "status" => "OK",
                    "data" => $id,
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, "Conflict");
            // Send errors to the client
            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(
                [
                    "status" => "ERROR",
                    "messages" => $errors,
                ]
            );
        }
        return $response;
    }
);

// Updates user based on primary key
$app->put(
    "/users/{id:[0-9]+}",
    function ($id) use ($app) {
        $user = $app->request->getJsonRawBody();
        $phql = "UPDATE API\\Users SET nom=:nom:, prenom=:prenom:, mail=:mail:, password=:password:, score=:score:,
                  idTeam=:idTeam: WHERE idUser=:id:";
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
                "nom" => $user->nom,
                "prenom" => $user->prenom,
                "mail" => $user->mail,
                "password" => $user->password,
                "score" => $user->score,
                "idTeam" => $user->idTeam,
            ]
        );
        // Create a response
        $response = new Response();
        // Check if the insertion was successful
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    "status" => "OK"
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, "Conflict");
            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(
                [
                    "status" => "ERROR",
                    "messages" => $errors,
                ]
            );
        }
        return $response;
    }
);

//Modify password
$app->put(
    "/users/{id:[0-9]+}/changepass",
    function ($id) use ($app) {
        $user = $app->request->getJsonRawBody();
        $phql = "UPDATE API\\Users SET password=:password: WHERE idUser=:id:";
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
                "password" => $user->password,
            ]
        );
        // Create a response
        $response = new Response();
        // Check if the insertion was successful
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    "status" => "OK"
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, "Conflict");
            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(
                [
                    "status" => "ERROR",
                    "messages" => $errors,
                ]
            );
        }
        return $response;
    }
);


//Modify team
$app->put(
    "/users/{id:[0-9]+}/teams",
    function ($id) use ($app) {
        $user = $app->request->getJsonRawBody();
        $phql = "UPDATE API\\Users SET idTeam=:idTeam: WHERE idUser=:id:";
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
                "idTeam" => $user->idTeam,
            ]
        );
        // Create a response
        $response = new Response();
        // Check if the insertion was successful
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    "status" => "OK"
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, "Conflict");
            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(
                [
                    "status" => "ERROR",
                    "messages" => $errors,
                ]
            );
        }
        return $response;
    }
);

//Modify score
$app->put(
    "/users/{id:[0-9]+}/score",
    function ($id) use ($app) {
        $user = $app->request->getJsonRawBody();
        $phql = "UPDATE API\\Users SET score=:score: WHERE idUser=:id:";
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
                "score" => $user->score,
            ]
        );
        // Create a response
        $response = new Response();
        // Check if the insertion was successful
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    "status" => "OK"
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, "Conflict");
            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(
                [
                    "status" => "ERROR",
                    "messages" => $errors,
                ]
            );
        }
        return $response;
    }
);

//Modify connexion date
$app->put(
    "/users/{id:[0-9]+}/date",
    function ($id) use ($app) {
        $user = $app->request->getJsonRawBody();
        $phql = "UPDATE API\\Users SET connexionDate=:date: WHERE idUser=:id:";
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
                "date" => date("Y-m-d"),
            ]
        );
        // Create a response
        $response = new Response();
        // Check if the insertion was successful
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    "status" => "OK"
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, "Conflict");
            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(
                [
                    "status" => "ERROR",
                    "messages" => $errors,
                ]
            );
        }
        return $response;
    }
);

// Deletes client based on primary key
$app->delete(
    "/users/{id:[0-9]+}",
    function ($id) use ($app) {
        $phql = "DELETE FROM API\\Users WHERE idUser=:id:";
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
            ]
        );
        // Create a response
        $response = new Response();
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    "status" => "OK"
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, "Conflict");
            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(
                [
                    "status" => "ERROR",
                    "messages" => $errors,
                ]
            );
        }
        return $response;
    }
);

