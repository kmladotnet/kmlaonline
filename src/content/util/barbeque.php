<?php
redirectLoginIfRequired();
$title = "바베큐 신청 - " . $title;

function printContent(){
    ?>
    <h1>바베큐 신청</h1>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bbq-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bbq-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a class="navbar-brand" href="">HOME</a></li>
                    <li><a href="">신청하기</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="">내 바베큐</a></li>
                </ul>
            </div>
        </div>
    </nav>
<?php }
?>