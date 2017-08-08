<?php

require_once 'dbHandler.php';
require_once 'passwordHash.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$user_id = NULL;

require_once 'authentication.php';

/**
*
*/
function verifyRequiredParams($required_fields, $request_params) {
    $error = false;
    $error_fields = "";
    foreach($required_fields as $field){
        if(!isset($request_params->$field) || strlen(trim($request_params->$field)) <=0) {
            $error = true;
            $error_field .= $field . ', ';
        }
    }

    if($error) {
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["status"] = "error";
        $response["message"] = 'Required fields' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(200, $response);
        $app->stop();
    }
}

function echoResponse($status_code, $response){
    $app = \Slim\Slim::getInstance();
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response);
}

$app->run();
?>