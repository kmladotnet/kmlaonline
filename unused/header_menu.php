<div class="menu1">
    <div class="widthholder">
        <div class="menu1_text">교내</div>
        <div class="menu1_sub">
            <a href="board/student_council" class="menu2">학생회</a>
            <a href="board/student_legislative" class="menu2">입법</a>
            <a href="board/student_judicial" class="menu2">사법</a>
            <a href="board/student_executive" class="menu2">행정</a>

            <?php
                if($me['n_level'] >= 19){
                    echo '<a href="board/student_discuss" class="menu2">학급회의</a>';
                    echo '<a href="board/student_clubs" class="menu2">동아리</a>';

                }
            ?>
        </div>
    </div>
</div>