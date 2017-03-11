<?php

    $row = array();
    $return_arr = array();
    $row_array = array();

    if((isset($_GET['term']) && strlen($_GET['term']) > 0) || (isset($_GET['id']) && is_numeric($_GET['id'])))
    {

        if(isset($_GET['term']))
        {
            $getVar = $db->real_escape_string($_GET['term']);
            $whereClause =  " label LIKE '%" . $getVar ."%' ";
        }
        elseif(isset($_GET['id']))
        {
            $whereClause =  " categoryId = $getVar ";
        }
        /* limit with page_limit get */

        $limit = intval($_GET['page_limit']);

        $sql = "SELECT id, text FROM mytable WHERE $whereClause ORDER BY text LIMIT $limit";

        /** @var $result MySQLi_result */
        $result = $db->query($sql);

            if($result->num_rows > 0)
            {

                while($row = $result->fetch_array())
                {
                    $row_array['id'] = $row['id'];
                    $row_array['text'] = utf8_encode($row['text']);
                    array_push($return_arr,$row_array);
                }

            }
    }
    else
    {
        $row_array['id'] = 0;
        $row_array['text'] = utf8_encode('Start Typing....');
        array_push($return_arr,$row_array);

    }

    $ret = array();
    /* this is the return for a single result needed by select2 for initSelection */
    if(isset($_GET['id']))
    {
        $ret = $row_array;
    }
    /* this is the return for a multiple results needed by select2
    * Your results in select2 options needs to be data.result
    */
    else
    {
        $ret['results'] = $return_arr;
    }
    echo json_encode($ret);

    $db->close();