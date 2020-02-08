<?php
/**
 * Config genrated using LaraAdmin
 * Help: `http://laraadmin.com`
 */

return [
    
	/*
    |--------------------------------------------------------------------------
    | General Configuration
    |--------------------------------------------------------------------------
    */
    
	'adminRoute' => 'admin',
    
    /*
    |--------------------------------------------------------------------------
    | Uploads Configuration
    |--------------------------------------------------------------------------
    |
    | private_uploads: Show that uploaded file remains private and can be seen by respective owners only
    | default_uploads_security: public / private
    | 
    */
    'uploads' => [
        /* By setting this parameter true we can make all uploaded files 
            as private which are accessible with login only. 
            Setting false will make file public.
        */
        'private_uploads' => true,
        'default_public' => false,
        'allow_filename_change' => true
    ],
];