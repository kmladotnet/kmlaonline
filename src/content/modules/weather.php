<?php
$weather = getWeather();
?>
<div class="weather">
    <link href="css/owfont-regular.css" rel="stylesheet" type="text/css">
    <i class="owf owf-<?php echo $weather->weather->id;?> owf-5x"></i>
    <div class="weather-temp">
        <?php
        echo $weather->temperature;
        ?>
    </div>
    <div class="weather-name">
        <?php
        echo lang('weather', $weather->weather->id);
        ?>
    </div>
    ?>
</div>
