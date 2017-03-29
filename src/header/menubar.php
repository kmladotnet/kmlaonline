<?php if(!isset($_SESSION['user'])) return; ?>
<div id="total-header-menu-menus" style="position: absolute; left: -40px">

    <div class="menu1 menu1-logo" style="width:40px;padding-left:10px">
    </div>
    <div class="menu1">
        <div class="widthholder">
            <div class="menu1_text">전체</div>
            <div class="menu1_sub">
                <a href="board/forum" class="menu2">포럼</a>
                <a href="board/all_announce" class="menu2">공지</a>
                <a href="board/all_pds" class="menu2">자료실</a>
                <a href="board/all_gallery" class="menu2">갤러리</a>
                <a href="https://docs.google.com/spreadsheets/d/1VZ5F17bSimEeEAHaZV6WJYI7kbp5mlx9mcJKk_2eAOo/edit#gid=0" class="menu2">ARCHIVE</a>
            </div>
        </div>
    </div>
    <?php
        if($me['n_level'] <= 19){
            echo '<div class="menu1">';
            echo '<div class="widthholder">';
            echo '<div class="menu1_text">졸업생</div>';
            echo '<div class="menu1_sub">';
            echo '<a href="board/noticeallS" class="menu2">공지</a>';
            echo '<a href="board/boardallS" class="menu2">게시판</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    ?>
    <?php
        if($me['n_level'] >= 19){
            echo '<div class="menu1">';
            echo '<div class="widthholder">';
            echo '<div class="menu1_text">교내</div>';
            echo '<div class="menu1_sub">';
            echo '<a href="board/student_council" class="menu2">학생회</a>';
            echo '<a href="board/student_legislative" class="menu2">입법</a>';
            echo '<a href="board/student_judicial" class="menu2">사법</a>';
            echo '<a href="board/student_executive" class="menu2">행정</a>';
            echo '<a href="board/student_discuss" class="menu2">학급회의</a>';
            echo '<a href="board/student_clubs" class="menu2">동아리</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    ?>
    <div class="menu1">
        <div class="widthholder">
            <div class="menu1_text">
                <?php echo $me['n_level']?>기</div>
            <div class="menu1_sub">
                <a href="board/wave<?php echo $me['n_level']?>_announce" class="menu2">공지</a>
                <a href="board/wave<?php echo $me['n_level']?>_free" class="menu2">자유</a>
                <a href="board/wave<?php echo $me['n_level']?>_pds" class="menu2">자료실</a>
            </div>
        </div>
    </div>
    <?php
if($me['n_level']!=0){
    $menu_list=getUserMenuBar($me);
    foreach($menu_list as $key=>$key2){
        ?>
        <div class="menu1">
            <div class="widthholder">
                <div class="menu1_text">
                    <?php echo $key?>
                </div>
                <div class="menu1_sub">
                    <?php foreach($key2 as $value){
                        switch($value[0]){
                            case "url": ?>
                        <a class="menu2" href="<?php echo htmlspecialchars($value[1]); ?>">
                            <?php echo htmlspecialchars($value[2]); ?>
                        </a>
                        <?php break;
                            case "action":
                                ?>
                            <a class="menu2" href="<?php echo htmlspecialchars($value[1]); ?>">
                                <?php echo htmlspecialchars($value[2]); ?>
                            </a>
                            <?php
                                break;
                        }
                    } ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
            <div class="menu1" id="upper-header-menu-kept-visible">
                <div class="widthholder">
                    <div class="menu1_text" id="upper-header-holder">둘러보기</div>
                    <div class="menu1_sub">
                        <a class="menu2" id="upper-header-menu-show-sitemap" href="/sitemap">사이트맵</a>
                        <a class="menu2" id="upper-header-menu-show-contacts" href="/contacts">연락망</a>
                        <a class="menu2" id="upper-header-menu-close" style="width:0;opacity:0.7;cursor:pointer">닫기</a>
                    </div>
                </div>
            </div>
            <div class="menu1">
                <div class="menu1_text"><a href="board/student_suggestions" class="menu2">건의사항</a></div>
            </div>
</div>
<div id="slidedown2" class="slidedown_holder">
    <div class="button slidedown_button" id="slidedown2_button" style="z-index:1;">
        <i class="fa fa-globe"></i>
    </div>
    <div class="slidedown" id="slidedown2_sub" style="right:0px;">
        <ul style="text-align: center; padding: 0; margin: 0;" id="top_notification_list">
            <li id="notification_item_loading">불러오는 중....</li>
        </ul>
        <a href="/user/notification"><div class="notification_more"> 모두 보기</div></a>
    </div>
</div>
<div id="slidedown1" class="slidedown_holder">
    <div class="button slidedown_button" id="slidedown1_button" style="z-index:1; display: none; opacity: 0; width: 0; padding: 0;">
        <i class="fa fa-user"></i>
    </div>
    <div class="menu-userarea" id="slidedown1_sub" style="right: 0px; top: 40px;">
        <?php include "src/header/userarea.php"; ?>
    </div>
</div>
<div id="slidedown3" class="slidedown_holder">
    <div class="button slidedown_button" id="slidedown3_button" style="z-index:1">
        <i class="fa fa-search"></i>
    </div>
    <div class="slidedown" id="slidedown3_sub" tabindex="0">
        <form method="get" action="/searchall">
            <div style="margin: 5px;overflow:hidden;">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="검색" name="search">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
