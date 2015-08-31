<?php

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

/**
 * SOAP third parties representation
 */
class Dolibarr_Thirdparty {
	public $ref;
	public $name;
	public $ref_ext;
	public $status;
	public $client;
	public $fournisseur;
	public $code_client;
	public $code_fournisseur;
	public $code_compta;
	public $code_compta_fournisseur;
	public $note_private;
	public $note_public;
	public $address;
	public $zip;
	public $town;
	public $country_code;
	public $province_id;
	public $phone;
	public $fax;
	public $email;
	public $url;
	public $idprof1;
	public $idprof2;
	public $idprof3;
	public $idprof4;
	public $idprof5;
	public $idprof6;

	public $capital;

	public $barcode;
	public $tva_assuj;
	public $tva_intra;

	public $canvas;
	public $particulier;
	 
	public $firstname ;
	public $name_bis ;
	public $customer_code_accountancy;
	public $supplier_code_accountancy;
	public $date_modification;

}

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
