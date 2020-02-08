<?php
/**
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Message;

trait MessageTrait 
{
    /**
     * Get Message by it's key
     */
    // Message::getByKey('sitename');
	public static function getByKey($module, $key) {
		$row = Message::where('message_key',$key)
			->where('module', $module)->first();

		if(isset($row->message_value)) {
			return $row->message_value;
		} else {
			return false;
		}
	}
    
    /**
     * Get all message in Array
     */
	// Message::getAll();
	public static function getAll() {
		$configs = array();
		$configs_db = Message::all();
		foreach ($configs_db as $row) {
			$configs[$row->module.$row->message_key] = $row->message_value;
		}
		return (object) $configs;
	}
}