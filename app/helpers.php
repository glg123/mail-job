<?php
function replace_newlines_with_space($str) {
    $replaceArray = [
        "\n" => " ",
        "\r\n" => " ",
        "\r" => " ",
    ];
    $str = strReplaceAssoc($replaceArray, $str);

    $replaceArray = [
        "          " => " ",
        "         " => " ",
        "        " => " ",
        "       " => " ",
        "      " => " ",
        "     " => " ",
        "    " => " ",
        "   " => " ",
        "  " => " ",
        " " => " ",
    ];
    return strReplaceAssoc($replaceArray, $str);
}
function strReplaceAssoc(array $replace, $subject)
{
    return str_replace(array_keys($replace), array_values($replace), $subject);
}
