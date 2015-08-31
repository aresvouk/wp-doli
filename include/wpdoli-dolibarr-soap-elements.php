<?php
if ( ! class_exists( 'Dolibarr_Order' ) ) :
class Dolibarr_Order {
	/** @var string */
	public $id;

	/** @var int */
	public $thirdparty_id;

	/** @var string ISO 8601 */
	public $date;

	/** @var int */
	public $status;

	/** @var Dolibarr_Order_Line[] */
	public $lines;
}
endif;
if ( ! class_exists( 'Dolibarr_Order_Line' ) ) :
/**
 * SOAP order lines representation
 */
class Dolibarr_Order_Line {
	/** @var int */
	public $type;

	/** @var boolean */
	public $desc;

	/** @var int */
	public $product_id;

	/** @var float|int */
	public $vat_rate;

	/** @var int */
	public $qty;

	/** @var float|int */
	public $price;

	/** @var float|int */
	public $unitprice;

	/** @var float|int */
	public $total_net;

	/** @var float|int */
	public $total;

	/** @var float|int */
	public $total_vat;
}
endif;
if ( ! class_exists( 'Dolibarr_Thirdparty1' ) ) :

/**
 * SOAP third parties representation
 */
class Dolibarr_Thirdparty1 {
	public  $ref       ;
	public  $individual;
	public  $firstname;
	public  $status   ;
	public  $client   ;
	public  $supplier ;
	public  $address;
	public  $zip ;
	public  $town;
	public  $country_code;
	public  $phone ;
	public  $email ;
	public  $ref_ext;
	public  $fk_user_author;
	public  $customer_code;
	public  $customer_code_accountancy;
	public  $supplier_code_accountancy;
	public  $date_creation;
	public  $date_modification ;
	public  $note_private;
	public  $note_public;
	public  $province_id;
	public  $country_id;
	public  $country;
	public  $fax;
	public  $url;
	public  $profid1;
	public  $profid2;
	public  $profid3;
	public  $profid4;
	public  $profid5;
	public  $profid6;
	public  $capital;
	public  $vat_used;
	public  $vat_number;
    
	public function getAttributs() {
		array(
		'ref',
		'individual',
		'firstname',
		'status',
		'client',
		'supplier',
		'address',
		'zip',
		'town',
		'country_code',
		'phone',
		'email',
		'ref_ext',
		'fk_user_author',
		'customer_code',
		'customer_code_accountancy',
		'supplier_code_accountancy',
		'date_creation',
		'date_modification ',
		'note_private',
		'note_public',
		'province_id',
		'country_id',
		'country',
		'fax',
		'url',
		'profid1',
		'profid2',
		'profid3',
		'profid4',
		'profid5',
		'profid6',
		'capital',
		'vat_used',
		'vat_number'
				);
		
	}
	public function setAttributsValues($arrData) {
		foreach ($this->getAttributs() as $attr) {
			if(isset($arrData[$arrData])) {
				$this->$attr = $arrData[$arrData];
				
			}
		}
	}
	public function getAttributsValues() {
		$data =array();var_dump($this->getAttributs());exit;
		foreach ($this->getAttributs() as $attr) {
			$data[$this->$attr] = $this->$attr;
		}
	}


}
endif;
if ( ! class_exists( 'Dolibarr_Product' ) ) :
/**
 * SOAP products representation
 */
class Dolibarr_Product {
	/** @var string */
	public $id;

	/** @var string */
	public $ref;

	/** @var string */
	public $type;

	/** @var string */
	public $label;

	/** @var string */
	public $description;

	/** @var string */
	public $date_creation;

	/** @var string */
	public $date_modification;

	/** @var string */
	public $note;

	/** @var string */
	public $status_tobuy;

	/** @var string */
	public $status_tosell;

	/** @var string */
	public $barcode_type;

	/** @var string */
	public $country_id;

	/** @var string */
	public $country_code;

	/** @var string */
	public $price_net;

	/** @var string */
	public $price;

	/** @var string */
	public $price_min_net;

	/** @var string */
	public $price_min;

	/** @var string */
	public $price_base_type;

	/** @var string */
	public $vat_rate;

	/** @var string */
	public $vat_npr;

	/** @var string */
	public $localtax1_tx;

	/** @var string */
	public $localtax2_tx;

	/** @var string */
	public $stock_real;

	/** @var string */
	public $dir;

	/** @var array */
	public $images;
}
endif;