<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin_Notice extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['type', 'message', 'link', 'role', 'read_at', 'deleted_at', 'updated_at', 'created_at'];
    
    protected $table = "admin_notices";
    
    protected $primary = "id";
    
    protected $dates = ['deleted_at'];
}
