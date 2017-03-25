<?php
use Phalcon\Http\Response;

/*************************************************************************************/
/*								  TRANSPORT FUNCTIONS							     */
/*																					 */
/*************************************************************************************/

// Retrieves all transport
$app->get(
    "/transports",
    function () use ($app) {
        //User query
        $phql = "SELECT * FROM API\\moyendetransport ORDER BY nom";
        $trsprts = $app->modelsManager->executeQuery($phql);
        $data = [];
        foreach ($trsprts as $trsprt) {
            $data[] = [
                "id" => $trsprt->idTransport,
                "name" => $trsprt->nom,
                "multiplicator" => $trsprt->multiplicateur,
                "savedRate" => $trsprt->tauxSauve,
            ];
        }
        echo json_encode($data);
    }
);

//Get transport by id
$app->get(
    "/transports/{id:[0-9]+}",
    function ($id) use ($app) {
        $phql = "SELECT * FROM API\\moyendetransport WHERE idTransport = :id:";
        $trsprt = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
            ]
        )->getFirst();
        // Create a response
        $response = new Response();
        if ($trsprt === false) {
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
                        "id" => $trsprt->idTransport,
                        "name" => $trsprt->nom,
                        "multiplicator" => $trsprt->multiplicateur,
                        "savedRate" => $trsprt->tauxSauve,
                    ]
                ]
            );
        }
        return $response;
    }
);