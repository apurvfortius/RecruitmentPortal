<?php
/**
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Employee;

trait EmployeeTrait 
{
    public function user()
    {
        return $this->belongsTo('App\User', 'context_id');
    }
}