<?php
/**
 * Model genrated using LaraAdmin
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
	
	protected $table = 'cities';
	
	protected $hidden = [
        
	];
	
	protected $primaryKey = 'cityID';


	protected $guarded = [];

	protected $dates = ['deleted_at'];
}
