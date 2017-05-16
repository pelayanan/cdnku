<?php

function paginate($page, $limit, $total_pages, $adjacents, $targetpage) {
    // $adjacents = 3;
    /*
      First get total number of rows in data table.
      If you have a WHERE clause in your query, make sure you mirror it here.
     */
    // $total_pages = 100;

    /* Setup page vars for display. */
    if ($page == 0)
        $page = 1;     //if no page var is given, default to 1.
    $prev = $page - 1;       //previous page is page - 1
    $next = $page + 1;       //next page is page + 1
    $lastpage = ceil($total_pages / $limit);  //lastpage is = total pages / items per page, rounded up.
    $lpm1 = $lastpage - 1;      //last page minus 1

    /*
      Now we apply our rules and draw the pagination object.
      We're actually saving the code to a variable in case we want to draw it more than once.
     */
    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<ul class=\"pagination\">";
        //previous button
        if ($page > 1)
            $pagination.= "<li><a href=\"$targetpage/$prev\"> previous</a></li>";
        else
            $pagination.= "<li><span aria-hidden=\"true\">&laquo; previous</span></li>";

        //pages	
        if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= "<li><span class=\"current\">$counter</span></li>";
                else
                    $pagination.= "<li><a href=\"$targetpage/$counter\">$counter</a></li>";
            }
        }
        elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><span class=\"current\">$counter</span></li>";
                    else
                        $pagination.= "<li><a href=\"$targetpage/$counter\">$counter</a></li>";
                }
                $pagination.= "<li><span aria-hidden=\"true\">...</span></li>";
                $pagination.= "<li><a href=\"$targetpage/$lpm1\">$lpm1</a></li>";
                $pagination.= "<li><a href=\"$targetpage/$lastpage\">$lastpage</a></li>";
            }
            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination.= "<li><a href=\"$targetpage/1\">1</a></li>";
                $pagination.= "<li><a href=\"$targetpage/2\">2</a></li>";
                $pagination.= "<li><span aria-hidden=\"true\">...</span></li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><span class=\"current\">$counter</span></li>";
                    else
                        $pagination.= "<li><a href=\"$targetpage/$counter\">$counter</a></li>";
                }
                $pagination.= "<li><span aria-hidden=\"true\">...</span></li>";
                $pagination.= "<li><a href=\"$targetpage/$lpm1\">$lpm1</a></li>";
                $pagination.= "<li><a href=\"$targetpage/$lastpage\">$lastpage</a></li>";
            }
            //close to end; only hide early pages
            else {
                $pagination.= "<li><a href=\"$targetpage/1\">1</a></li>";
                $pagination.= "<li><a href=\"$targetpage/2\">2</a></li>";
                $pagination.= "<li><span aria-hidden=\"true\">...</span></li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><span aria-hidden=\"true\">$counter</span></li>";
                    else
                        $pagination.= "<li><a href=\"$targetpage/$counter\">$counter</a></li>";
                }
            }
        }

        //next button
        if ($page < $counter - 1)
            $pagination.= "<li><a href=\"$targetpage/$next\">next </a></li>";
        else
            $pagination.= "<li><span aria-hidden=\"true\">&raquo; Next</span></li>";
        $pagination.= "</ul>\n";
    }

    return $pagination;
}

function bikin_paginate($query = false, $kolom = false, $count_all = false) {
    if (!$query) {
        $pg = array();
        $pg["total"] = $count_all;
    } else {
        $pg = query_fetch_assoc(query_paginate($query));
    }
    if ($pg["total"] > 10 AND ! isset($_GET[$kolom])) {
        $total_page = floor($pg["total"] / 10);
        if (isset($_GET["start"])) {
            $this_page = $_GET["start"];
        } else {
            $this_page = 0;
        }
        ?>
        <div style="clear: both; padding-top: 20px;"></div>
        <div style="margin: 0 auto; width: 400px;">
            Halaman 
            <?php
            if ($total_page < 9) {
                for ($x = 0; $x <= floor($pg["total"] / 10); $x++) {
                    ?>
                    <a style="padding: 5px; background: #DDD; <?php
                    if ($_GET["start"] == ($x * 10)) {
                        print "background: #FFF; font-weight: bold; padding: 5px 10px;";
                    }
                    ?>text-decoration: none;" href="?start=<?= ($x * 10) ?>"><?= $x + 1 ?></a> | 
                       <?php
                   }
               } else {
                   if (!$this_page) {
                       print '<a href="?start=10' . (isset($_GET["qq"]) ? $_GET["qq"] : "") . '">Next</a>';
                   } else if ($total_page <= ($this_page / 10)) {
                       print '<a href="?start=' . ($this_page - 10) . (isset($_GET["qq"]) ? $_GET["qq"] : "") . '">Previous</a>';
                   } else {
                       print '<a href="?start=' . ($this_page - 10) . (isset($_GET["qq"]) ? $_GET["qq"] : "") . '">Previous</a> | ';
                       print '<a href="?start=' . ($this_page + 10) . (isset($_GET["qq"]) ? $_GET["qq"] : "") . '">Next</a>';
                   }
               }
               ?>
        </div>
        <?php // var_dump($pg)            ?>
        <div style="clear: both; padding: 20px;"></div>
        <?php
    }
}

