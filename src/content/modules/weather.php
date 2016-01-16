<?php
$weather = getWeather();
?>
<div class="weather">
    <div class="weather-last-update">
        <?php
        echo $weather->lastUpdate->format('m월 d일 H시 i분');
        ?>
    </div>
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
    <div class="weather-copyright">
        정보제공: <a href="http://openweathermap.org/">openweathermap.org</a>
    </div>
</div>
