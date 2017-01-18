<?php

function exportxls($file_name)
{
    $CI =& get_instance();
    $CI->output->set_header('Content-type: application/octet-stream');
    $CI->output->set_header("Content-Disposition: attachment; filename=$file_name.xls");
    $CI->output->set_header("Pragma: no-cache");
    $CI->output->set_header("Expires: 0");
}