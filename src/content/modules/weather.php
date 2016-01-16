<?php
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
require_once("src/lib/OpenWeatherMap.php");
foreach (glob("src/lib/OpenWeatherMap/*.php") as $filename){
    require_once($filename);
}
foreach (glob("src/lib/OpenWeatherMap/Fetcher/*.php") as $filename){
    require_once($filename);
}
foreach (glob("src/lib/OpenWeatherMap/Util/*.php") as $filename){
    require_once($filename);
}

$lang = 'ko';

$units = 'metric';

$owm = new OpenWeatherMap();

try {
    $weather = $owm->getWeather('Berlin', $units, $lang);
} catch(OWMException $e) {
    echo 'OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
    echo "<br />\n";
} catch(\Exception $e) {
    echo 'General exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
    echo "<br />\n";
}

echo $weather->temperature;
?>
