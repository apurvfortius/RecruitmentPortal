<?php
/**
 * Model genrated using LaraAdmin
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
	
	protected $table = 'countries';
	
	protected $hidden = [
        
    ];
	protected $primaryKey = 'countryID';

	protected $guarded = [];

	protected $dates = ['deleted_at'];
}
