<?php
use Phalcon\Http\Response;

/*************************************************************************************/
/*								  TEAMS FUNCTIONS									 */
/*																					 */
/*************************************************************************************/

//Retrieves all teams
$app->get(
    "/teams",
    function () use ($app) {
        //Team query
        $phql = "SELECT * FROM API\\Team ORDER BY nom";
        $teams = $app->modelsManager->executeQuery($phql);
        $data = [];
        foreach ($teams as $team) {
            $data[] = [
                "id" => $team->idTeam,
                "name" => $team->nom,
                "score" => $team->score,
            ];
        }
        echo json_encode($data);
    }
);

//Retrieve a team by id
$app->get(
    "/teams/{id:[0-9]+}",
    function ($id) use ($app) {
        $phql = "SELECT * FROM API\\Team WHERE idTeam = :id:";
        $team = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
            ]
        )->getFirst();
        //Create response
        $response = new Response();
        if ($team === false) {
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
                        "id" => $team->idTeam,
                        "name" => $team->nom,
                        "score" => $team->score,
                    ]
                ]
            );
        }
        return $response;
    }
);

//Modify team score
$app->put(
    "/teams/{id:[0-9]+}",
    function ($id) use ($app) {
        $team = $app->request->getJsonRawBody();
        $phql = "UPDATE API\\Teams SET score=:score: WHERE idTeam=:id:";
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
                "name" => $team->nom,
                "score" => $team->score,
            ]
        );
        //Create a response
        $response = new Response();
        //Check if modif was succesful
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    "status" => "OK"
                ]
            );
        } else {
            //Change HTTP status
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

//Get all user in a team
$app->get(
    "/teams/{id:[0-9]+}/all",
    function ($id) use ($app) {
        $phql = "SELECT * FROM API\\Users WHERE idTeam = :id:";
        $users = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
            ]
        );
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                "id" => $user->idUser,
                "lastname" => $user->nom,
                "firstname" => $user->prenom,
                "pseudo" => $user->pseudo,
                "mail" => $user->mail,
                "creationDate" => $user->creationDate,
                "score" => $user->score,
            ];
        }
        echo json_encode($data);
    }
);