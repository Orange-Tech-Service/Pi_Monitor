<?php
return [
    /**
     * Apps timezone. List of supported timezones: http://php.net/manual/en/timezones.php
     */
    'timezone'=>'Europe/Vilnius',
	
    /**
     * Number of predictions
     */
    'schedulePrediction'=>200,
	
    /**
     * Ajax preload (create/edit)
     */
    'ajaxPrediction'=>20,
	
    /**
     * Cron Job response length in email
     */
    'kbEmailOutput'=>5,

    /**
     * Default value is 16.384. Number of UTF-8 characters to log.
     *
     * If you want to increase this number then you need to run following query in mysql
     * ALTER TABLE `webcron_log` CHANGE `response` `response` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
     */
    'dbLogResponse'=>'16384',
	
    /**
     * Long name of App
     */
    'longAppName'=>'',
    
	/**
     *  Short name of App
     */
    'shortAppName'=>'<i class="fa fa-clock-o"></i>',
	
    /**
     * Cookie path
     * If you installed script in subdir. for example: http://my-domain.com/webcron, then the cookiePath must be: /webcron
     * If you installed script in web root. for example: http://my-domain.com, then the cookiePath must be: /
     */
    'cookiePath'=>'/',

    /**
     * The "from" email which is used in email notification
     */
    "notificationFrom"=>"no-reply@example.com",

    /**
     * Base URL of the App. Used for console program
     * If you installed script in subdirectory, then the URL must be in a following format: http://my-domain.com/sub-dir
     * If you installed script in web root, then the URL must be in a following format: http://my-domain.com
     */
    "baseUrl"=>'http://example.com',

    /**
     * List of supported languages
     */
    'languages'=>[
        'en-US'=>'English',
        'ru-RU'=>'Русский',
    ],

    /**
     * Secret key used for cookies encoding
     */
	'cookieValidationKey'=>'TXntdwyZRXYBNV05PZK6cfw1f8cp1vpv',

    /**
     * The key to run cron jobs handler via web (http(s))
     */
    'webHandlerKey'=>'',

    /**
     * Can Setup SSH Process to execute
     */
    'canSetupProcess'=>function_exists('proc_open'),

    /**
     * Number of cURL commands per batch request
     */
    'batchUrl'=>10,

    /**
     * Number of processes per batch execution
     */
    'batchCommand'=>10,

    /**
     * cURL default options
     */
    'curl'=>require 'curl.php',
];
