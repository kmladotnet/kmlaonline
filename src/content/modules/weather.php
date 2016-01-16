<?php
$weather = getWeather();
$ko_time = new DateTimeZone('Asia/Seoul');
?>
<div class="weather">
    <div class="weather-last-update">
        <?php
        echo $weather->lastUpdate->setTimeZone($ko_time)->format('n월 j일 H시 i분 기준');
        ?>
    </div>
    <link href="css/owfont-regular.css" rel="stylesheet" type="text/css">
    <div class="weather-temp">
        <i class="owf owf-<?php echo $weather->weather->id;?> owf-2x" style="vertical-align:middle"></i>
        <?php
        echo $weather->temperature;
        ?>
    </div>
    <div class="weather-name">
        <?php
        echo '(',lang('weather', $weather->weather->id),')';
        ?>
    </div>
    <div class="weather-copyright">
        정보제공: <a href="http://openweathermap.org/">openweathermap.org</a>
    </div>
</div>
