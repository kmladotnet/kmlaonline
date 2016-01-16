<?php
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\AbstractCache;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
function __autoload($className) {
    $className = substr($className, strrpos($className, '\\') + 1);
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

class ExampleCache extends AbstractCache {
    private function urlToPath($url) {
        $tmp = sys_get_temp_dir();
        $dir = $tmp . DIRECTORY_SEPARATOR . "OpenWeatherMapPHPAPI";
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $path = $dir . DIRECTORY_SEPARATOR . md5($url);
        return $path;
    }
    public function isCached($url) {
        $path = $this->urlToPath($url);
        if (!file_exists($path) || filectime($path) + $this->seconds < time()) {
            echo "Weather data is NOT cached!\n";
            return false;
        }
        echo "Weather data is cached!\n";
        return true;
    }
    public function getCached($url) {
        return file_get_contents($this->urlToPath($url));
    }
    public function setCached($url, $content) {
        file_put_contents($this->urlToPath($url), $content);
    }
}
// Language of data (try your own language here!):
$lang = 'ko';
// Units (can be 'metric' or 'imperial' [default]):
$units = 'metric';
$owm = new OpenWeatherMap(null, new ExampleCache(), 60);
$weather = $owm->getWeather('Hoengsong', $units, $lang, '713e90471b96dbd9c11826031ee66031');
echo "EXAMPLE 1<hr />\n\n\n";
echo $weather->temperature;
?>
