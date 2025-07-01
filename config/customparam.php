<?php

return [
	'APP_URL' => 'http://localhost:8000',
    'HASH_SALT'=>'s60577415116ff3c389bcf117a8e9d9210d59637a01154023c147c11a49b6e57',
    'ENC_IV'=>'5637875CFSA54877',
    'APP_NAME'  =>  'CERTIFICATES OF SPECIAL TASK FORCE',
    'APP_SHORT_NAME'    =>  'COE ON SPECIAL TASK FORCE',

    'stf_notice35_temp_id'    =>   '1732693629620',	
    'stf_notice94_temp_id'    =>   '1733995477541',
    'stf_notice179_temp_id'   =>   '1733996029964',
    'stf_notice67_temp_id'    =>   '1733996172211',		                       
    'REDIS_HOST' => 'redis',
    'REDIS_PORT' => 6379,
    'QUEUE_CONNECTION'=>'redis',
	//first db Connection
	'DB_CONNECTION'=>'mysql',
    'DB_HOST'=>'db',
    'DB_PORT'=>'3306',
    'DB_DATABASE'=>'stf_udin_certificates',
    'DB_USERNAME'=>'stf_user',
    'DB_PASSWORD'=>'stf_pass',


    'udin_base_url' =>  	'https://stagingudinapi.wb.gov.in',  
    'udin_username' =>  	'stage_stf_notice',					           
    'udin_password' =>   	'YTHYK2S',					          
    'udin_enckey' =>     	'PPB7Q4'					              
    
];
