<?php

$app->get('/session', function(){
    $db = new DbHandler();
    $session = $db->getSession();
    $response['userid'] = $session['userid'];
    $response['name'] = $session['name'];
    $response['grade'] = $session['grade'];
    $response['studentid'] = $session['studentid'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app){
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('user_id, password'), $r->login_info);
    $response = array();
    $db = new DbHandler;
    $password = $r->login_info->password;
    $user_id = $r->login_info->user_id;
    $user = $db->getOneRecord("SELECT user_id, name, password, grade, studentid FROM member_list WHERE user_id = '$user_id'");
    if($user != NULL) {
        if(passwordHash::check_password($user['password'], $password)){
            $response['status'] = "success";
            $response['message'] = "로그인 성공";
            $response['name'] = $user['name'];
            $response['userid'] = $user['user_id'];
            $response['grade'] = $user['grade'];
            $response['studentid'] = $user['studentid'];
            if(!isset($_SESSION)){
                session_start();
            }
            $_SESSION['userid'] = $user['userid'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['grade'] = $user['grade'];
            $_SESSION['studentid'] = $user['studentid'];
        } else {
            $response['status'] = "error";
            $response['message'] = "로그인 실패. Incorrect credentials";
        }
    } else {
        $response['status'] = "error";
        $response['message'] = "해당 ID가 존재하지 않습니다."
    }
    echoResponse(200, $response);
})

$app->get('/logout', function(){
    $db = new DbHandler();
    $session = $db->destroySession();
    $response['status'] = "info";
    $response['message'] = '성공적으로 로그아웃되었습니다.';
    echoResponse(200, $response);
})
?>