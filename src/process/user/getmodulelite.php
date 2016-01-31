<?php
require_once("src/content/modules/module.php");
$post_data = json_decode($_REQUEST['json'], true);
moduleContentsLite($post_data['name'],  array_merge(defaultOptions($post_data['name']), $post_data['options']['options']));
?>
