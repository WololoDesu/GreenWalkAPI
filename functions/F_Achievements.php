<?php
use Phalcon\Http\Response;

/*************************************************************************************/
/*								  TRANSPORT FUNCTIONS							     */
/*																					 */
/*************************************************************************************/


// Retrieves all achievements
$app->get(
    "/achievements",
    function () use ($app) {
        //User query
        $phql = "SELECT * FROM API\\Achievements ORDER BY nom";
        $achievements = $app->modelsManager->executeQuery($phql);
        $data = [];
        foreach ($achievements as $achievement) {
            $data[] = [
                "id" => $achievement->idAchievement,
                "nom" => $achievement->nom,
                "description" => $achievement->description,
                "multiplicateur" => $achievement->multiplicateur,
            ];
        }
        echo json_encode($data);
    }
);

//Get achievement by id
$app->get(
    "/achievements/{id:[0-9]+}",
    function ($id) use ($app) {
        $phql = "SELECT * FROM  API\\Achievements WHERE idAchievement = :id:";
        $achievement = $app->modelsManager->executeQuery(
            $phql,
            [
                "id" => $id,
            ]
        )->getFirst();
        // Create a response
        $response = new Response();
        if ($achievement === false) {
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
                        "id" => $achievement->idAchievement,
                        "nom" => $achievement->nom,
                        "description" => $achievement->description,
                        "multiplicateur" => $achievement->multiplicateur,
                    ]
                ]
            );
        }
        return $response;
    }
);