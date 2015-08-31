<?php
array(
		'ref'=> $arrThirdparty['last_name'], // Company name or individual last name
		'individual'=> $individual, // Individual
		'firstname'=> $arrThirdparty['first_name'],
		'status   '=> '1', // Active
		'client   '=> '1', // Is a client
		'supplier '=> '0', // Is not a supplier
		'address'=> $arrThirdparty['livr_adresse'],
		'zip'=>$arrThirdparty['livr_code_postable'],
		'town'=> $arrThirdparty['livr_ville'],
		'country_code'=> $arrThirdparty['livr_pays'],
		'phone'=>$arrThirdparty['tel'],
		'email'=>$arrThirdparty['email'],
		'ref_ext'=> uniqid(),
		'fk_user_author'=> 1,
		'customer_code'=> '',
		'customer_code_accountancy'=> null,
		'supplier_code_accountancy'=> null,
		'date_creation'=> date(),
		'date_modification'=> date(),
		'note_private'=> null,
		'note_public'=> null,
		'province_id'=> null,
		'country_id'=> null,

		'country'=> null,

		'fax'=> null,

		'url'=> null,
		'profid1'=> null,
		'profid2'=> null,
		'profid3'=> null,
		'profid4'=> null,
		'profid5'=> null,
		'profid6'=> null,
		'capital'=> null,
		'vat_used'=> null,
		'vat_number'=> null
		);


