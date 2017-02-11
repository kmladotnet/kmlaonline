<?php
$title = "기소";

function printContent(){ ?>

    <script type="text/javascript">
        /*$(document).ready(function(){
            $(document).on("focus", '#article_table tr:last-child td:last-child', function() {
                var table = $("#article_table");
                table.append('<tr><p>WOW</p></tr>');
            });
        });*/
        function addNewRow(){
            var table = $("#article_table_body");
            //table.append("<tr>\r\n                    <td style=\"width:190px;\">\r\n                        <input type=\"text\" style=\"text-align:center\" placeholder=\"\uC774\uB984\" id=\"name_1\" class=\"ui-autocomplete-input auto_name\" autocomplete=\"on\" \/>\r\n                        <input type=\"hidden\" id=\"n_student\" class=\"ui-input\" value=\"0\" \/>\r\n                    <\/td>\r\n                    <td style=\"width:190px;\">\r\n                        <input type=\"text\" style=\"text-align:center\" placeholder=\"\uAE30\uC18C\uC77C\" id=\"datepicker_2\" class=\"ui-autocomplete-input date_picker\" autocomplete=\"off\" \/>\r\n                    <\/td>\r\n                    <td style=\"width:190px;\">\r\n                        <input type=\"text\" style=\"text-align:center\" placeholder=\"\uAE30\uC18C\uC790\" id=\"accuser_2\" class=\"ui-autocomplete-input\" autocomplete=\"off\" \/>\r\n                    <\/td>\r\n\r\n                    <td style=\"width:400px;\">\r\n                        <input type=\"text\" style=\"text-align:center; width:380px\" placeholder=\"\uD56D\uBAA9\" id=\"article_kind_2\" class=\"ui-autocomplete-input auto_article\" autocomplete=\"on\" \/>\r\n                        <input type=\"hidden\" id=\"article_id\" value=\"0\" \/>\r\n                    <\/td>\r\n                <\/tr>");
        }
    </script>

    <script type="text/javascript">
        $(function() {
            $( ".date_picker" ).each(function(){
                $(this).datepicker({
                    dateFormat: 'mm-dd'
                });
            });
        });
    </script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>


    <form data-toggle="validator" data-delay="100" action="../ajax/user/accuse" method="post" enctype="multipart/form-data" onsubmit="window.onbeforeunload=null;">
        <input type="hidden" name="prev_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" />
        <div style="text-align:center; width:100%">
            <h1><?php echo "새로운 기소 신청"; ?></h1>
            <h2><?php echo "기소 항목" ?></h2>
            <table style="margin:50px auto; width:1000px;" id="article_table">
                <tbody id="article_table_body">
                    <tr>
                        <th style="width:200px;">
                            <p>학생 이름</p>
                        </th>
                        <th style="width:200px;">
                            <p>기소 일자</p>
                        </th>
                        <th style="width:200px;">
                            <p>기소자</p>
                        </th>
                        <th style="width:400px;">
                            <p>기소 항목</p>
                        </th>
                    </tr>
                    <tr>
                        <td style="width:190px;">
                            <input type="text" style="text-align:center" placeholder="이름" id="name_1" class="ui-autocomplete-input auto_name" autocomplete="on" />
                            <input type="hidden" id="n_student" class="ui-input" value="0" />
                        </td>
                        <td style="width:190px;">
                            <input type="text" style="text-align:center" placeholder="기소일" id="datepicker_1" class="ui-autocomplete-input date_picker" autocomplete="off" />
                        <td style="width:190px;">
                            <input type="text" style="text-align:center" placeholder="기소자" id="accuser_1" class="ui-autocomplete-input" autocomplete="off" />
                        </td>
                        </td>
                        <td style="width:400px;">
                            <input type="text" style="text-align:center; width:380px" placeholder="항목" id="article_kind_1" class="ui-autocomplete-input auto_article" autocomplete="on" />
                            <input type="hidden" id="article_id" value="0" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width:190px;">
                            <input type="text" style="text-align:center" placeholder="이름" id="name_1" class="ui-autocomplete-input auto_name" autocomplete="on" />
                            <input type="hidden" id="n_student" class="ui-input" value="0" />
                        </td>
                        <td style="width:190px;">
                            <input type="text" style="text-align:center" placeholder="기소일" id="datepicker_2" class="ui-autocomplete-input date_picker" autocomplete="off" />
                        </td>
                        <td style="width:190px;">
                            <input type="text" style="text-align:center" placeholder="기소자" id="accuser_2" class="ui-autocomplete-input" autocomplete="off" />
                        </td>

                        <td style="width:400px;">
                            <input type="text" style="text-align:center; width:380px" placeholder="항목" id="article_kind_2" class="ui-autocomplete-input auto_article" autocomplete="on" />
                            <input type="hidden" id="article_id" value="0" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" class="btn btn-primary" value="확인" style="margin:10px" />
        </div>
    </form>

    <!--script type="text/javascript" src="/"></script-->

    <script>
    $(".auto_article").keydown(function(event){
        //if (event.which == 9 && $(this).closest("tr").is(":last-child")) {
            //addNewRow();
        //}
        console.log(event.which);
        console.log($(this).closest("tr").is(":last-child"));
        console.log($("#article_table_body").children().last());
    });
    </script>
<?php
}