<?php
$layout = '';
switch($_POST['name']) {
    case 'colorful':
        $layout = '[{"name":"minjok-news","options":{"x":0,"y":0,"w":5,"h":4,"options":{"color":"info"}}},{"name":"menu","options":{"x":5,"y":0,"w":5,"h":5,"options":{"color":"success","all-day":true}}},{"name":"birthday","options":{"x":10,"y":0,"w":2,"h":2,"options":{"color":"warning"}}},{"name":"weather","options":{"x":10,"y":2,"w":2,"h":3,"options":{"color":"info"}}},{"name":"important","options":{"x":0,"y":4,"w":5,"h":6,"options":{"color":"success","show-cat":false,"show-title":true,"show-name":false,"show-date":true}}},{"name":"kmlaboard","options":{"x":5,"y":5,"w":7,"h":5,"options":{"color":"warning"}}},{"name":"article-list","options":{"x":0,"y":10,"w":5,"h":6,"options":{"color":"info","cat":["77"],"show-cat":false,"show-title":true,"show-name":true,"show-date":true,"title":"큼라 카페"}}},{"name":"article-list","options":{"x":5,"y":10,"w":7,"h":6,"options":{"color":"success","cat":["63","64","65","78","2","3","4","6","203"],"show-cat":true,"show-title":true,"show-name":true,"show-date":true,"title":"내 게시판"}}}]';
        break;
    case 'warrior':
        $layout = '[{"name":"article-list","options":{"x":0,"y":0,"w":12,"h":6,"options":{"color":"danger","cat":["6"],"show-cat":false,"show-title":true,"show-name":true,"show-date":true,"title":"전쟁터"}}}]"';
        break;
}
if($layout !== '') {
    file_put_contents("data/user/main_layout/{$me['n_id']}.txt", $layout);
}
?>
