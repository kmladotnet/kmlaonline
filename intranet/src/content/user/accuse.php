<?php
$title = "기소";

function printContent(){ ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $( ".date_picker" ).each(function(){
                $(this).datepicker({
                    dateFormat: 'mm-dd'
                });
            });
        });
    </script>



    <form data-toggle="validator" data-delay="100" action="ajax/user/accuse" method="post" enctype="multipart/form-data" onsubmit="window.onbeforeunload=null;">
        <input type="hidden" name="prev_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" />
        <div style="text-align:center; width:100%">
            <h1><?php echo "새로운 기소 신청"; ?></h1>
            <h2><?php echo "기소 항목" ?></h2>
            <table style="margin:20px auto; width:800px;" id="article_table">
                <tr>
                    <th style="width:200px;">
                        <h3>학생 이름</h3>
                    </th>
                    <th style="width:200px;">
                        <h3>기소 일자</h3>
                    </th>
                    <th style="width:200px;">
                        <h3>기소자</h3>
                    </th>
                    <th style="width:600px;">
                        <h3>기소 항목</h3>
                    </th>
                </tr>
                <tr>
                    <td style="width:190px;">
                        <input type="text" placeholder="이름" id="name_1" class="ui-autocomplete-input auto_name" autocomplete="on" />
                        <input type="hidden" id="n_student" class="ui-input" value="0" />
                    </td>
                    <td style="width:190px;">
                        <input type="text" placeholder="기소자" id="accuser_1" class="ui-autocomplete-input" autocomplete="off" />
                    </td>
                    <td style="width:190px;">
                        <input type="text" placeholder="기소일" id="datepicker_1" class="ui-autocomplete-input date_picker" autocomplete="off" />
                    </td>
                    <td style="width:580px;">
                        <input type="text" placeholder="항목" id="article_kind_1" class="ui-autocomplete-input auto_article" autocomplete="on" />
                        <input type="hidden" id="article_id" value="0" />
                    </td>
                </tr>
                <tr>
                    <td style="width:190px;">
                        <input type="text" placeholder="이름" id="name_1" class="ui-autocomplete-input auto_name" autocomplete="on" />
                        <input type="hidden" id="n_student" class="ui-input" value="0" />
                    </td>
                    <td style="width:190px;">
                        <input type="text" placeholder="기소자" id="accuser_2" class="ui-autocomplete-input" autocomplete="off" />
                    </td>
                    <td style="width:190px;">
                        <input type="text" placeholder="기소일" id="datepicker_2" class="ui-autocomplete-input date_picker" autocomplete="off" />
                    </td>
                    <td style="width:580px;">
                        <input type="text" placeholder="항목" id="article_kind_2" class="ui-autocomplete-input auto_article" autocomplete="on" />
                        <input type="hidden" id="article_id" value="0" />
                    </td>
                </tr>
            </table>
        </div>
    </form>
<?php
}