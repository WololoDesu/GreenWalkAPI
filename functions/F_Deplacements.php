<?php
use Phalcon\Http\Response;

/*************************************************************************************/
/*								  DEPLACEMENT FUNCTIONS							     */
/*																					 */
/*************************************************************************************/

// Retrieves all deplacement for an user
$app->get(
    "/deplacement/{id:[0-9]+}",
    function ($id) use ($app) {
        //User query
        $phql = "SELECT * FROM API\\Deplacements WHERE idUser = :id:";
        $dpls = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
            ]
        );
        $data = [];
        foreach ($dpls as $dpl) {
            $data[] = [
                "id" => $dpl->idDeplacement,
                "distance" => $dpl->distance,
                "temps" => $dpl->temps,
                "idTransport" => $dpl->idTransport,
                "co2Sauve" => $dpl->co2Sauve,
                "deplacementDate" => $dpl->deplacementDate,
            ];
        }
        echo json_encode($data);
    }
);

// Retrieves all deplacement for an user and a transport way
$app->get(
    "/deplacement/{id:[0-9]+}/{trsprt:[0-9]+}",
    function ($id, $trsprt) use ($app) {
        //User query
        $phql = "SELECT * FROM API\\Deplacements WHERE idUser = :id: AND idTransport = :trsprt:";
        $dpls = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
                "trsprt" => $trsprt,
            ]
        );
        $data = [];
        foreach ($dpls as $dpl) {
            $data[] = [
                "id" => $dpl->idDeplacement,
                "distance" => $dpl->distance,
                "temps" => $dpl->temps,
                "co2Sauve" => $dpl->co2Sauve,
                "deplacementDate" => $dpl->deplacementDate,
            ];
        }
        echo json_encode($data);
    }
);

// Retrieves all deplacement for an user and given dates
$app->get(
    "/deplacement/{id:[0-9]+}/{date1}/{date2}",
    function ($id, $date1, $date2) use ($app) {
        //User query
        $phql = "SELECT * FROM API\\Deplacements
        WHERE idUser = :id: AND deplacementDate >= :date1: AND deplacementDate <= :date2:";
        $dpls = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
                "date1" => $date1,
                "date2" => $date2,
            ]
        );
        $data = [];
        foreach ($dpls as $dpl) {
            $data[] = [
                "id" => $dpl->idDeplacement,
                "distance" => $dpl->distance,
                "temps" => $dpl->temps,
                "idTransport" => $dpl->idTransport,
                "co2Sauve" => $dpl->co2Sauve,
                "deplacementDate" => $dpl->deplacementDate,
            ];
        }
        echo json_encode($data);
    }
);

//Insert deplacement in base
$app->post(
    "/deplacement",
    function () use ($app) {
        $depl = $app->request->getJsonRawBody();

        //Requete 1 : Ajout du déplacement
        $rqstDepl = "INSERT INTO API\\Deplacements (distance, temps, idUser, idTransport, co2Sauve, deplacementDate)
                     VALUES (:distance:, :temps:, :id:, :idTransport:, :co2Sauve:, :date:)";

        $rqstGetInfo = "SELECT API\\Users.score AS score, API\\Transports.tauxSauve AS tauxSauve, API\\Transports.multiplicateur AS mult FROM API\\Users
                        INNER JOIN API\\Deplacements ON API\\Users.idUser = API\\Deplacements.idUser
                        INNER JOIN API\\Transports ON API\\Deplacements.idTransport = API\\Transports.idTransport
                        WHERE API\\Users.idUser = :id: AND API\\Transports.idTransport = :idTransport:";
        $rqstGetAch = "SELECT IF(count(idAchievement) = 0, 1, count(idAchievement)) as c FROM API\\Obtenir WHERE idUser=:id:";

        $rqstMajScore = "UPDATE API\\Users SET score=:score: WHERE idUser=:id:";

        $rqstTeamId = "SELECT idTeam FROM API\\Users WHERE idUser = :id:";

        $rqstTeamScore = "SELECT score FROM API\\Teams WHERE idTeam=:id:";

        $rqstMajScoreTeam = "UPDATE API\\Teams SET score=:scoreTeam: WHERE idTeam=:id:";

        //Insert deplacement in database
        $status = $app->modelsManager->executeQuery(
            $rqstDepl,
            [
                "distance" => $depl->distance,
                "temps" => $depl->temps,
                "id" => $depl->id,
                "idTransport" => $depl->idTransport,
                "co2Sauve" => $depl->co2Sauve,
                "date" => date("Y-m-d"),
            ]
        );

        //Update player score
        $infos = $app->modelsManager->executeQuery(
            $rqstGetInfo,
            [
                "id" => $depl->id,
                "idTransport" => $depl->idTransport,
            ]
        )->getFirst();

        $nbAchi = $app->modelsManager->executeQuery(
            $rqstGetAch,
            [
                "id" => $depl->id,
            ]
        )->getFirst()->c;

        //Maj team score
        $oldValueTeam = $app->modelsManager->executeQuery(
            $rqstTeamScore,
            [
                "id" => $depl->id,
            ]
        )->getFirst()->score;


        //Calculate score
        $newScore = floor($infos->tauxSauve * $depl->distance * $infos->mult * ($nbAchi + 1 / (log($nbAchi))));
        $updatedScore = $infos->score + $newScore;

        //Get user team id
        $idTeam = $app->modelsManager->executeQuery(
          $rqstTeamId,
            [
                "id" => $depl->id,
            ]
        )->getFirst()->idTeam;

        //Insert new score in db
        $status1 = $app->modelsManager->executeQuery(
            $rqstMajScore,
            [
                "id" => $idTeam,
                "score" => $updatedScore,
            ]
        );

        $tempScore = $oldValueTeam + $newScore;

        $status2 = $app->modelsManager->executeQuery(
            $rqstMajScoreTeam,
            [
                "id" => $idTeam,
                "scoreTeam" => $tempScore,
            ]
        );

        // Create a response
        $response = new Response();
        // Check if the insertion was successful
        if ($status->success() === true) {
            // Change the HTTP status
            $response->setStatusCode(201, "Created");
            $id = $status->getModel()->idDeplacement;
            $response->setJsonContent(
                [
                    "status" => "OK",
                    "data" => $id,
                    "scoreBefore" => $infos->score,
                    "newScore" => $newScore,
                    "scoreAfter" => $updatedScore,
                    "scoreTeam" => $oldValueTeam + $newScore,
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