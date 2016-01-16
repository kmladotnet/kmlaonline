<?php
$weather = getWeather();
?>
<div class="weather">
    <link href="css/owfont-regular.css" rel="stylesheet" type="text/css">
    <i class="owf owf-<?php echo $weather->weather->id;?> owf-5x"></i>
    <br>
    <?php
        echo '기온: ', $weather->temperature;
    ?>
</div>
