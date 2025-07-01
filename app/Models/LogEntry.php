<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_at',
        'type',
        'message',
    ];

    public $timestamps = false; // Disable automatic timestamps if you are manually setting created_at
}