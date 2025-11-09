<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailErrorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'msg_body',
        'msg_subject',
        'attachments',
        'msg_to',
        'msg_from',
        'module_name',
        'added_by',
        'ip',
        'status',
        'error_message',
        'type',
        'data'
    ];
}