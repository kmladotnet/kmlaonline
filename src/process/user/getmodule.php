<?php
require_once("src/content/modules/module.php");
$post_data = json_decode($_REQUEST['json'], true);
print_r($post_data['options']['options']);
moduleContents($post_data['name'], $post_data['options']['options']);
?>
