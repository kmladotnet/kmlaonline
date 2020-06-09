<?php
function streamPart($fname, $start, $bytes_left_to_read)
{
    $bufferSize = 128 * 1024; // 128KB buffer
    $fstream = fopen($fname, "r");
    fseek($fstream, $start);
    while (!feof($fstream) && $bytes_left_to_read > 0 && !connection_aborted()) {
        if ($bytes_left_to_read > $bufferSize) {
            echo fread($fstream, $bufferSize);
            $bytes_left_to_read -= $bufferSize;
        } else {
            echo fread($fstream, $bytes_left_to_read);
            fclose($fstream);
            return;
        }
    }
    fclose($fstream);
}
function outRange($filename)
{
    header("Accept-Ranges: bytes");
    if (isset($_SERVER['HTTP_RANGE'])) {
        $range = str_replace(' ', '', substr(strstr($_SERVER['HTTP_RANGE'], "="), 1));
        $range = str_replace(',', ';', $range);
        $ranges = explode(";", $range);
        $rangeArray = array();
        $rangeCount = 0;
        $rangeTotal = 0;
        $rangePrintString = "";
        foreach ($ranges as $curr) {
            $pre = substr($curr, 0, strpos($curr, "-"));
            $post = substr($curr, strpos($curr, "-") + 1);
            if (substr($curr, 0, 1) == "-") {
                //[-x]: Last x bytes
                $sbyte = filesize($filename) - 1 - $post;
                $ebyte = filesize($filename) - 1;
            } else if (substr($curr, -1, 1) == "-") {
                //[x-]: From x byte (start from 0)
                $sbyte = $pre;
                $ebyte = filesize($filename) - 1;
            } else {
                $sbyte = $pre;
                $ebyte = $post;
                //[x-y]: Range (Includes x, y)
            }
            $sbyte = intval($sbyte);
            $ebyte = intval($ebyte);
            if ($ebyte >= filesize($filename)) {
                $ebyte = filesize($filename);
            }

            if ($sbyte > $ebyte) {
                $sbyte = $ebyte;
            }

            $rangeTotal += $ebyte - $sbyte + 1;
            $rangeArray[$rangeCount++] = array($sbyte, $ebyte);
            $rangePrintString .= $sbyte . "-" . $ebyte . "/" . filesize($filename) . "; ";
        }
        header("HTTP/1.1 206 Partial Content");
        header("Status: 206 Partial Content");
        header("Content-Length: " . $rangeTotal);
        header("Content-Range: " . $rangePrintString);
        for ($i = 0; $i < $rangeCount && !connection_aborted(); $i++) {
            streamPart($filename, $rangeArray[$i][0], $rangeArray[$i][1] - $rangeArray[$i][0] + 1);
        }
    } else {
        header("Content-Length: " . filesize($filename));
        header("HTTP/1.1 200 OK");
        header("Status: 200 OK");
        streamPart($filename, 0, filesize($filename));
    }
}
