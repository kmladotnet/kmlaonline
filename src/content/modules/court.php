<?php
$courtPost = getLatestCourtPost();
if($courtPost !== null) {
    $guilty = goesToCourt($me['s_name'], $courtPost);
    $isDasan = isCourtDasan($courtPost);
    ?>
    <div class="court-face <?php echo $guilty ? "guilty" : "innocent"; ?>">
        <i class="fa fa-<?php echo $guilty ? "frown" : "smile"; ?>-o" aria-hidden="true"></i>
    </div>
    <div style="text-align: center; font-size: 13pt;">
        <?php if($guilty) { ?>
            이번 법정은
            <span class="court-location <?php echo $isDasan ? "dasan" : "gym"; ?>">
                <?php echo $isDasan ? "소강당" : "체육관"; ?>
            </span>
        <?php } ?>
        <a href="/board/student_judicial/view/<?php echo $courtPost['n_id']; ?>">법정리스트</a>
    </div>
<?php } else { ?>
    <div class="court-face">
        <br>
        ?
    </div>
<?php }
