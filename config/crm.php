<?php

return [

	'api' => [
		'addContract' => [
			'url' => env('CRM_SERVER').'/api/v1.0/suite-crm/add-contact'
		],
		'updateContract' =>[
			'url' => env('CRM_SERVER').'/api/v1.0/suite-crm/update-contact'
		],

	],
	'crm_column'=>[
		'address_c','birthdate','blood_type_c','city_c','cn_c','country_c','created_by_name','education_degree_c','education_public_private_c','education_status_c','email1','gender_c','height_c','how_to_know_comment_1_c','how_to_know_comment_2_c','how_to_know_dynamic_1_c','how_to_know_dynamic_2_c','ihala_line_url_c','job_classification_c','job_comment_c','language_c','last_name','marital_relationship_c','nationality_c','nationality_comment_c','other_id_card_c','passport_c','phone_home','phone_mobile','phone_work','school_name_and_department_c','sexual_activity_c','social_media_id_1_c','social_media_id_2_c','social_media_type_1_c','social_media_type_2_c','spouse_birthday_c','spouse_blood_type_c','spouse_cn_c','spouse_email_c','spouse_foreign_id_card_c','spouse_id_card_c','spouse_job_classification_c','spouse_job_comment_c','spouse_name_c','spouse_nationality_c','spouse_nationality_comment_c','spouse_passport_c','spouse_phone_c','spouse_social_media_id_1_c','spouse_social_media_type_1_c','state_c','tw_id_card_c','weight_c','zone_c'
	]
];