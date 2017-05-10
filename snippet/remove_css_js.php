<?php

//To remove all the hidden text not displayed on a webpage
function strip_html_tags($str) {
    $str = preg_replace('/(<|>)\1{2}/is', '', $str);
    $str = preg_replace(
            array(// Remove invisible content
        '@<head[^>]*?>.*?</head>@siu',
        '@<style[^>]*?>.*?</style>@siu',
        '@<script[^>]*?.*?</script>@siu',
        '@<noscript[^>]*?.*?</noscript>@siu',
            ), "", //replace above with nothing
            $str);
    $str = replaceWhitespace($str);
    // $str = strip_tags($str);
    return $str;
}

//function strip_html_tags ENDS
//To replace all types of whitespace with a single space
function replaceWhitespace($str) {
    $result = $str;
    foreach (array(
"  ", " \t", " \r", " \n",
 "\t\t", "\t ", "\t\r", "\t\n",
 "\r\r", "\r ", "\r\t", "\r\n",
 "\n\n", "\n ", "\n\t", "\n\r",
    ) as $replacement) {
        $result = str_replace($replacement, $replacement[0], $result);
    }
    return $str !== $result ? replaceWhitespace($result) : $result;
}

############################
