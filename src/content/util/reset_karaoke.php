<?php
function resetKaraokeTable() {
    global $mysqli;

    $query="TRUNCATE TABLE kmlaonline_karaoke_table";
    if ($mysqli->query($query)) {
        return true; 
    } else {
        return false; 
    }
}

if (resetKaraokeTable()) {
    echo "Karaoke table reset successfully.";
} else {
    echo "Error occurred while resetting karaoke table.";
}
?>
