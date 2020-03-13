<?php
/**
 * Model genrated using LaraAdmin
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assign_Position extends Model
{
    use SoftDeletes;
	
	protected $table = 'assign_positions';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];
}
