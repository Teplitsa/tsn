<?php

function issued_dates()
{
    $result = config('project.issued_year', 2016);

    if ((int)date('Y') !== $result) {
        $result .= ' &mdash; ' . date('Y');
    }

    return $result;
}

function toastr($type, $msg, $title = null, $onclick = null)
{
    if ($title === null) {
        $title = ucfirst($type);
    }

    return compact('type', 'msg', 'title', 'onclick');
}