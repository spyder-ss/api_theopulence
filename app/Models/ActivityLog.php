<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'added_by',
        'client_id',
        'module',
        'action',
        'description',
        'ip',
        'table_name',
        'user_agent',
        'data_after_action'
    ];

    public static function ActivityLogCreate($array)
    {
        try {
            $data = ActivityLog::create($array);
            return ['status' => 'ok', 'data' => $data];
        } catch (\Exception $e) {
            return ['status' => 'error', 'data' => $e];
        }
    }

    public function GetAddedBy()
    {
        return $this->belongsTo('App\Models\User', 'added_by');
    }
}