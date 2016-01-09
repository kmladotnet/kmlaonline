<?php
require_once("src/content/modules/module.php");
$post_data = json_decode($_POST['json'], true);
moduleContents($post_data['name'], $post_data['options']['options']);
?>
