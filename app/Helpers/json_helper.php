<?php

function json_response($status = 'success', $message = '', $data = [])
{
    return [
        'status'     => $status,
        'message'    => $message,
        'data'       => $data,
        'csrf_token' => csrf_hash(),
        'csrf_name'  => csrf_token(),
    ];
}