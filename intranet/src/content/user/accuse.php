<?php
$title = "기소";

function printContent(){ ?>


    <p>It works!!</p>
    <form action="ajax/user/accuse">
        <input type="text" placeholder="이름" id="name" class="ui-autocomplete-input" autocomplete="on" />
        <input type="text" placeholder="기소자" id="accuser" class="ui-autocomplete-input" autocomplete="off" />
        <input type="text" placeholder="항목" id="article_kind" class="ui-autocomplete-input" autocomplete="on" />
    </form>

<?php
}