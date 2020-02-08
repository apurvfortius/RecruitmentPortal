<?php
/**
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Setting;

trait SettingTrait 
{

    /**
     * ##############################################
     * #####   Model Columns Getter and Setters #####
     * ##############################################
     */

    /**
     * Setting Up Setting Value Attribute if it's configure to Encrypt
     *
     * @param  string  $value
     * @return string
     */
    public function setSettingValueAttribute($value)
    {
        $enc = false;
        if(isset(request()->is_encrypted) && !empty(request()->is_encrypted))
            $enc = request()->is_encrypted == 'true';
        else
            $enc = $this->is_encrypted == 'true';
            
        $this->attributes['setting_value'] = $enc ? encrypt($value) : $value;
    }

    /**
     * Decrypt the Setting Value Attribute if it's configure to Encrypt
     *
     * @param  string  $value
     * @return string
     */
    public function getSettingValueAttribute($value)
    {
        if(isset($value) && !empty($value) && $this->is_encrypted == 'true')
            return decrypt($value);
        return $value;
    }

    /**
     * ##############################################
     * #####   Extending Functionality Methods ######
     * ##############################################
     */

    /**
     * Get Setting by Setting Key as the Find function won't help as we want
     */
    // Setting::getByKey('sitename');
	public static function getByKey($key) {
		$row = Setting::where('setting_key',$key)->first();

		if(isset($row->setting_value)) {
			return $row->setting_value;
		} else {
			return false;
		}
	}
    
    /**
     * Get all the settings in Array where key is setting_key and value
     */
	// Setting::getAll();
	public static function getAll() {
		$configs = array();
		$configs_db = Setting::all();
		foreach ($configs_db as $row) {
			$configs[$row->module.$row->setting_key] = $row->setting_value;
		}
		return (object) $configs;
	}
}