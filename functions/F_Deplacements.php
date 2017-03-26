<?php
use Phalcon\Http\Response;

/*************************************************************************************/
/*								  DEPLACEMENT FUNCTIONS							     */
/*																					 */
/*************************************************************************************/

// Retrieves all deplacement for an user
$app->get(
    "/deplacement/{id:[0-9]+}/all",
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