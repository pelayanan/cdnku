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
