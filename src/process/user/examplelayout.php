<?php
$layout = '';
switch($_POST['name']) {
    case 'colorful':
        $layout = '[{"name":"minjok-news","options":{"x":0,"y":0,"w":6,"h":4,"options":{"color":"warning"}}},{"name":"menu","options":{"x":8,"y":0,"w":2,"h":5,"options":{"color":"success","all-day":false}}},{"name":"birthday","options":{"x":10,"y":0,"w":2,"h":2,"options":{"color":"danger"}}},{"name":"weather","options":{"x":6,"y":0,"w":2,"h":5,"options":{"color":"info"}}},{"name":"important","options":{"x":6,"y":5,"w":6,"h":4,"options":{"color":"danger","num":"10","show-cat":false,"show-title":true,"show-name":false,"show-date":true}}},{"name":"kmlaboard","options":{"x":0,"y":4,"w":6,"h":5,"options":{"color":"success"}}},{"name":"article-list","options":{"x":0,"y":9,"w":5,"h":5,"options":{"color":"info","cat":["77"],"num":"8","show-cat":false,"show-title":true,"show-name":true,"show-date":true,"title":"큼라 카페"}}},{"name":"court","options":{"x":10,"y":2,"w":2,"h":3,"options":[]}}]';
        $modules = json_decode($layout, true);
        $my_articles = json_decode( <<<JSON
        {
          "name":"article-list",
          "options":{
             "x":5,
             "y":9,
             "w":7,
             "h":5,
             "options":{
                "color" : "warning",
                "num": 8,
                "title":"내 게시판"
             }
          }
        }
JSON
                                   , true);
        $my_articles['options']['options']['cat'] = array_values(getUserMainBoards($me));
        $modules[] = $my_articles;
        $layout = json_encode($modules);
        break;
    case 'warrior':
        $layout = '[{"name":"article-list","options":{"x":0,"y":0,"w":12,"h":6,"options":{"color":"danger","cat":["6"],"show-cat":false,"show-title":true,"show-name":true,"show-date":true,"title":"전쟁터"}}}]';
        break;
    case 'default':
        $layout = '[{"name":"weather","options":{"x":8,"y":2,"w":2,"h":4,"options":{"color":"default"}}},{"name":"birthday","options":{"x":8,"y":0,"w":2,"h":2,"options":{"color":"default"}}},{"name":"menu","options":{"x":10,"y":0,"w":2,"h":6,"options":{"color":"default","all-day":false}}},{"name":"important","options":{"x":0,"y":0,"w":8,"h":6,"options":{"color":"default","show-cat":true,"show-title":true,"show-name":true,"show-date":true}}},{"name":"kmlaboard","options":{"x":0,"y":6,"w":12,"h":6,"options":{"color":"default"}}},{"name":"minjok-news","options":{"x":0,"y":12,"w":5,"h":4,"options":{"color":"default"}}},{"name":"article-list","options":{"x":0,"y":16,"w":5,"h":4,"options":{"color":"yellow","cat":["77"],"num":"6","show-cat":false,"show-title":true,"show-name":true,"show-date":true,"title":"큼라 카페"}}}]';

        $modules = json_decode($layout, true);
        $my_articles = json_decode( <<<JSON
        {
          "name":"article-list",
          "options":{
             "x":5,
             "y":12,
             "w":7,
             "h":8,
             "options":{
                "num": "15",
                "title":"내 게시판"
             }
          }
        }
JSON
                                   , true);
        $my_articles['options']['options']['cat'] = array_values(getUserMainBoards($me));
        $modules[] = $my_articles;
        $layout = json_encode($modules);
}
if($layout !== '') {
    file_put_contents("data/user/main_layout/{$me['n_id']}.txt", $layout);
}
?>
