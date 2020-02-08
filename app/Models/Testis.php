<?php
/**
 * Model genrated using LaraAdmin
 * Help: Contact Sagar Upadhyay
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testis extends Model
{
    use SoftDeletes;
	
	protected $table = 'testes';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];
}
