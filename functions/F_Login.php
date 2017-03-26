<?php
use Phalcon\Http\Response;

/*************************************************************************************/
/*								  LOGIN FUNCTIONS				      			     */
/*																					 */
/*************************************************************************************/

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