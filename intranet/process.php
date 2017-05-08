<?php
global $student;
include "lib_real.php";
$fn="intranet/src/process/".basename($_REQUEST['actiontype'])."/".basename($_REQUEST['action']).".php"; ?>
<script type="text/javascript">alert("기소 기간이 아닙니다.");location.href="/intranet";</script>
<?php
if(file_exists($fn)) {
    include($fn);
}
?>