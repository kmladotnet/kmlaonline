<?php
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
function __autoload($className) {
      if (file_exists('src/lib/OpenWeatherMap/'.$className.'.php')) {
          require_once('src/lib/OpenWeatherMap/'.$className.'.php');
          return true;
      }
      if (file_exists('src/lib/OpenWeatherMap/Fetcher/'.$className.'.php')) {
          require_once('src/lib/OpenWeatherMap/Fetcher/'.$className.'.php');
          return true;
      }
      if (file_exists('src/lib/OpenWeatherMap/Util/'.$className.'.php')) {
          require_once('src/lib/OpenWeatherMap/Util/'.$className.'.php');
          return true;
      }
      return false;
}
require_once("src/lib/OpenWeatherMap.php");

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
