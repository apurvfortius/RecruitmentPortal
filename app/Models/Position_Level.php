<?php
/**
 * Model genrated using LaraAdmin
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position_Level extends Model
{
    use SoftDeletes;
	
	protected $table = 'position_levels';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];
}
