<?php

class WPDoliFormAbonnement {
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $nickname;
	public $email;
	public $website;
	public $tel;
	public $employeur;
	public $livr_adresse;
	public $livr_code_postable;
	public $produit_id;
	public $livr_ville;
	public $livr_pays;
	public $fac_adresse;
	public $fac_code_postable;

	public $fac_ville;
	public $fac_pays;
	public $password_confirme;
	public $communication;
	public $isnewletter;
	public $siteweb;
	public $num_tva;

	public $attributs=array();
	public $reg_errors;
	public $produit ;
	public $country;
	public $dolibarr ;
	public $issameadress;
	public $contactname;
	public $contactfirstname;
	function __construct($dolibarr) {

		$this->dolibarr = $dolibarr;
		$prod = array();
		(is_object($this->dolibarr)) ?$this->produit  = $this->dolibarr->dolibarr_getProduit():array();
		(is_object($this->dolibarr)) ?$this->country  = $this->dolibarr->dolibarr_getCountry():array();
		$this->private = 0;
		
		$this->reg_errors  = new WP_Error;
		$this->attributs = array(
				'produit_id',
				'username',
				'password',
				'first_name',
				'last_name',
				'email',
				'website',
				'tel',
				'employeur',
				'livr_adresse',
				'livr_code_postable',

				'livr_ville',
				'livr_pays',
				'fac_adresse',
				'fac_code_postable',

				'fac_ville',
				'fac_pays',
				'password_confirme',
				'communication',
				'website',
				'num_tva',
				'isnewletter',
				'issameadress',
				'private',
				'contactname',
				'contactfirstname',
				'docpapier');
	}

	public static function  getAttributsLable() {
		return   array(
				'produit_id'=>'Produit',
				'username'=>'Login',
				'password'=>'Password',
				'first_name'=>'Prénom',
				'last_name'=>'Nom',
				'email'=>'Email',
				'website'=>'Web site',
				'tel'=>'TÉL/GSM',
				'employeur'=>'EMPLOYEUR',
				'livr_adresse'=>'ADRESSE DE LIVRAISON',
				'livr_code_postable'=>'CODE POSTAL (1)',

				'livr_ville'=>'VILLE(1)',
				'livr_pays'=>'PAYS (1)',
				'fac_adresse'=>'ADRESSE DE FACTURATION (2)',
				'fac_code_postable'=>'CODE POSTAL (2)',

				'fac_ville'=>'VILLE (2)',
				'fac_pays'=>'PAYS (2)',
				'password_confirme'=>'',
				'communication'=>'Communication',
				'num_tva'=>'Numéro TVA',
				'isnewletter'=>"S'abonner à la
				newsletter d'Alter Echos",
				'issameadress'=>"Adresse de Facturation identique à l'adresse de livraison",
				'private'=>'private',
				'contactname'=>'Contact Nom',
				'contactfirstname'=>'Contact Prénom',
				'docpapier'=>'Je désire recevoir les factures sous format papier');
	}
	function getStyle () {
		return 	$style= '
		<style>
		div {
		margin-bottom:2px;
	}
	label {
	width:100px;
	}

	input{
	margin-bottom:4px;
	}
	</style>
	';
	}
	function select_produit() {
		$option = '';
		if(is_array($this->produit)) {
			$option .="<option ></option>";
		 foreach($this->produit as $id=>$value)
		 	$option .="<option value='$id'>$value</option>";
		}
		return $option;
	}
	function select_country($idselect=null) {
		$option = '';
		if(is_array($this->country)) {
			foreach($this->country as $id=>$value) {
				$selected ='';
				if(!is_null($idselect) && $idselect ==$id)
				$selected = 'selected="selected"';
				$option .="<option value='$id' $selected >$value</option>";
			}
		}
		return $option;
	}
	function registration_form(  ) {
		$siteKey = '6Le1EgoTAAAAAHuvF_74Q1T7P30kKHLKDn5Ep9xq';

		echo  $this->getStyle();
		?>
<script type="text/javascript">
 jQuery(document).ready(function(){
	 is_private=<?=is_null($this->private)?0:$this->private?>;
		if (is_private) {
			jQuery(".individualline").show();
			jQuery(".compagnyline").hide();
		} else {
			jQuery(".individualline").hide();
			
		}
		jQuery("#radiocompany").click(function() {
			jQuery(".individualline").hide();
			jQuery(".compagnyline").show();
         	//$("#typent_id").val(0);
         	//$("#effectif_id").val(0);
         	//$("#TypeName").html(document.formsoc.ThirdPartyName.value);
         	document.formsoc.private.value=0;
         });
		jQuery("#radioprivate").click(function() {
         	jQuery(".individualline").show();
         	jQuery(".compagnyline").hide();
         	//jQuery("#typent_id").val(id_te_private);
         	//jQuery("#effectif_id").val(id_ef15);
         	//jQuery("#TypeName").html(document.formsoc.LastName.value);
         	document.formsoc.private.value=1;
         });
		jQuery("#issameadress").click(function() {
			if( jQuery('input[name=issameadress]').is(':checked') ){
				jQuery("#livr_code_postable").val(jQuery("#fac_code_postable").val());
	         	jQuery("#livr_adresse").val(jQuery("#fac_adresse").val());
	         	jQuery("#livr_ville").val(jQuery("#fac_ville").val());
	         	jQuery("#livr_pays").val(jQuery("#fac_pays").val());

	         	jQuery("#contactfirstname").val(jQuery("#first_name").val());
	         	jQuery("#contactname").val(jQuery("#last_name").val());
	         	
	         	
			} else {

				jQuery("#livr_code_postable").val('');
	         	jQuery("#livr_adresse").val('');
	         	jQuery("#livr_ville").val('');
	         	jQuery("#livr_pays").val('');

	         	jQuery("#contactfirstname").val('');
	         	jQuery("#contactname").val('');
	         	
	         	
			    
			}
         	//jQuery("#typent_id").val(id_te_private);
         	//jQuery("#effectif_id").val(id_ef15);
         	//jQuery("#TypeName").html(document.formsoc.LastName.value);
         	
         });
		
});
 </script>
 <?php $tabLabel=self::getAttributsLable()?>
<form action="<?php  $_SERVER['REQUEST_URI'] ?>" method="post"
	name='formsoc'>
		<div id="selectthirdpartytype">
		<div class="hideonsmartphone float">Vous êtes: &nbsp; &nbsp;</div>
		<label for="radiocompany"><input type="radio" id="radiocompany"
			class="flat" name="private" value="0" <?php echo $this->private==0? 'checked="checked"':''?>>&nbsp;ORGANISATION</label>
		&nbsp; &nbsp; <label for="radioprivate"><span
			style="padding-right: 3px !important;"><input type="radio"
				id="radioprivate" class="flat" name="private" value="1" <?php echo $this->private==1? 'checked="checked"':''?>>&nbsp;PARTICULIER</span> </label>
	</div>
	<table>
		<tr>
			<td><label for="produit_id">Produit<strong>*</strong>
			</label>
			</td>
			<td><select name="produit_id" id='produit_id'>
					<?php echo $this->select_produit();?>
			</select>
			</td>
		</tr>
        <tr>
			<td><label for="last_name">Nom <strong>*</strong>
			</label>
			</td>
			<td><input type="text" name="last_name" id="last_name"
				value="<?=$this->last_name  ?>">
			</td>
		</tr>
		<tr class='individualline'>
			<td><label for="firstname">Prénom<strong>*</strong>
			</label>
			</td>
			<td><input type="text" name="first_name" id="first_name"
				value="<?=$this->first_name  ?>">
			</td>
		</tr>
         <tr class='individualline'>
			<td><label for="employeur">EMPLOYEUR</label> 
			</td>
			<td><input type="text" name="employeur"
				value="<?=$this->employeur  ?>">
			</td>
		</tr>
		

		<tr>
			<td><label for="tel">TÉL/GSM</label> <strong>*</strong>
			</td>
			<td><input type="text" name="tel" value="<?=$this->tel  ?>">
			</td>
		</tr>
		
       <tr>
			<td><label for="email">Email <strong>*</strong>
			</label>
			</td>
			<td><input type="text" name="email" value="<?=$this->email ?>">
			</td>
		</tr>
      
		<tr >
			<td><label for="fac_adresse">ADRESSE DE FACTURATION</label> <strong>*</strong>
			</td>
			<td><input type="text" name="fac_adresse" id="fac_adresse"
				value="<?=$this->fac_adresse  ?>">
			</td>
		</tr>
		<tr >
			<td><label for="fac_code_postable">CODE POSTAL (1)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="fac_code_postable" id="fac_code_postable"
				value="<?=$this->fac_code_postable  ?>">
			</td>
		</tr>
		<tr >
			<td><label for="fac_ville">VILLE (1)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="fac_ville" id="fac_ville"
				value="<?=$this->fac_ville  ?>">
			</td>
		</tr>
		<tr >
			<td><label for="fac_pays">PAYS (1)</label> <strong>*</strong>
			</td>
			<td><select name="fac_pays" id='fac_pays'>
					<?php echo $this->select_country($this->fac_pays);?>
			</select>
			
			</td>
		</tr>
		
		<tr  >

			<td colspan='2'><input type="checkbox" name="issameadress"
				id="issameadress" value="1"> <label for="issameadress">Adresse de Facturation identique à l'adresse de livraison</label>
				 <!-- <textarea name="isnewletter">
				 Vous souhaitez être livré à une autre adresse
			<?=$this->issameadress ?>
			
		</textarea> -->
			</td>
		</tr>
		
		<tr>
			<td><label for="contactname">Contact Nom</label> 
			</td>
			<td><input type="text" name="contactname" id="contactname"
				value="<?=$this->contactname  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="contactfirstname">Contact prénom</label> 
			</td>
			<td><input type="text" name="contactfirstname" id="contactfirstname"
				value="<?=$this->contactfirstname  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="livr_adresse">ADRESSE DE LIVRAISON</label> <strong>*</strong>
			</td>
			<td><input type="text" name="livr_adresse" id="livr_adresse"
				value="<?=$this->livr_adresse  ?>">
			</td>
		</tr>
        


		<tr>
			<td><label for="livr_code_postable">CODE POSTAL (2)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="livr_code_postable" id="livr_code_postable"
				value="<?=$this->livr_code_postable  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="livr_ville">VILLE (2)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="livr_ville" id="livr_ville"
				value="<?=$this->livr_ville  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="livr_pays">PAYS (2)</label> <strong>*</strong>
			</td>
			<td><select name="livr_pays" id='livr_pays'>
					<?php echo $this->select_country($this->livr_pays);?>
			</select>
				
			</td>
		</tr>
		
		
		<!--  
		<tr>
			<td><label for="username">Username <strong>*</strong>
			</label>
			</td>
			<td><input type="text" name="username" value="<?=$this->username  ?>">
			</td>
		</tr>
		-->
		
		<!--  
		-->

		<tr>
			<td><label for="website">Site web </label>
			</td>
			<td><input type="text" name="website" value="<?=$this->website ?>" />
			</td>
		</tr>
		<tr class='compagnyline'>
			<td><label for="num_tva">Numéro TVA </label>
			</td>
			<td><input type="num_tva" name="num_tva" value="<?=$this->num_tva ?>" />
			</td>
		</tr>
		<tr>
			<td><label for="communication"><?=$tabLabel['communication']?>  </label>
			</td>
			<td> <textarea  type="text" name="communication" maxlength="500"
				> <?=$this->communication ?> </textarea>
			</td>
		</tr>
		
		<tr>

			<td colspan='2'><input type="checkbox" name="docpapier"
				id="docpapier" value="1"> <label for="docpapier"><?=$tabLabel['docpapier']?></label> <!-- <textarea name="isnewletter">
			<?=$this->docpapier ?>
		</textarea> -->
			</td>
		</tr>
         <tr>

			<td colspan='2'><input type="checkbox" name="isnewletter"
				id="isnewletter" value="1"> <label for="isnewletter">S'abonner à la
					newsletter d'Alter Echos</label> <!-- <textarea name="isnewletter">
			<?=$this->isnewletter ?>
		</textarea> -->
			</td>
		</tr>
		
	</table>
	<div class="g-recaptcha"
		data-sitekey="<?=htmlentities(trim($siteKey)) ?>"></div>
	<div class='submit'>
		<input type="submit" name="submit" value="Register" />
	</div>

</form>
<?php 
	}
	function registration_validation(  )  {
		$arrRequiredParticulier = array('produit_id','email','first_name','last_name','tel','livr_adresse','livr_code_postable','livr_pays');
		$arrRequiredCompagny = array('produit_id','email','last_name','tel','livr_adresse','fac_adresse','livr_code_postable','livr_pays','fac_pays','fac_code_postable','fac_ville');
		
		$labels = self::getAttributsLable();
		$arrRequired = ($this->private)?$arrRequiredParticulier:$arrRequiredCompagny;
		foreach ($arrRequired as $attrName) {
			if(empty( $this->$attrName )) $this->reg_errors->add($attrName, $labels[$attrName].'  Required');
		}
		
		if ( username_exists( $this->email ) )
			$this->reg_errors->add('user_name', 'Sorry, that Email already exists!');
		if ( ! validate_username( $this->email ) ) {
			$this->reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
		}
// 		if ( 5 > strlen( $this->password ) ) {
// 			$this->reg_errors->add( 'password', 'Password length must be greater than 5' );
// 		}
// 		if ( $this->password !=$this->password_confirme ) {
// 			$this->reg_errors->add( 'password', 'Password Confirmation Doesn\'t Match' );
// 		}
		if ( !is_email( $this->email ) ) {
			$this->reg_errors->add( 'email_invalid', 'Email is not valid' );
		}
		if ( email_exists( $this->email ) ) {
			$this->reg_errors->add( 'email', 'Email Already in use' );
		}
		if ( ! empty( $this->website ) ) {
			if ( ! filter_var( $this->website, FILTER_VALIDATE_URL ) ) {
				$this->reg_errors->add( 'website', 'Website is not a valid URL' );
			}
		}
		$error = $resp = null;
		$secret = '6Le1EgoTAAAAAKIK8bsHkrGeWoC0c62gtc32MPnq';
		if(isset($_POST['g-recaptcha-response'])) {
			//$this->xrvel_login_recaptcha_process();
			if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
				$remoteIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$remoteIp = $_SERVER['REMOTE_ADDR'];
			}
			$remoteIp = $_SERVER['REMOTE_ADDR'];
			$recaptcha = new \ReCaptcha1\ReCaptcha1($secret);
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $remoteIp);
			//var_dump($resp,'2emereponse');
			if (is_object($resp) && $resp->isSuccess()) {
				// verified!
			} else {
				$error = 'CAPTCHA incorrect: ';
				foreach ($resp->getErrorCodes() as $code) {
					$error .= $code;
				}
				//var_dump($_SERVER["REMOTE_ADDR"], $remoteIp, $_POST["g-recaptcha-response"], $resp);
			//	$this->reg_errors->add('invalid_captcha', $error);
			}
		}
		/*
		 require_once GOOGLE_PATH.'recaptchalib.php';

		$reCaptcha = new ReCaptcha($secret);
		if(isset($_POST["g-recaptcha-response"])) {
		if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
		$REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		$REMOTE_ADDR = $_SERVER["REMOTE_ADDR"];
		}
		$resp = $reCaptcha->verifyResponse(
				$REMOTE_ADDR,
				$_POST["g-recaptcha-response"]
		);
		var_dump($_SERVER["REMOTE_ADDR"], $REMOTE_ADDR, $_POST["g-recaptcha-response"], $resp);
		if (!$resp->success || is_null($resp)) {
			
		$this->reg_errors->add( 'invalid_captcha', 'CAPTCHA incorrect' );
		}
		//if($_POST["g-recaptcha-response"]=='')$this->reg_errors->add( 'invalid_captcha', 'CAPTCHA incorrect' );
		}
		*/

		if ( is_wp_error( $this->reg_errors ) ) {

			foreach ( $this->reg_errors->get_error_messages() as $error ) {
					
				echo '<div>';
				echo '<strong>ERROR</strong>:  ';
				echo $error . '<br/>';
				echo '</div>';
					
			}

		}
	}

	function complete_registration() {
		$ok = -1;

		if ( 1 > count( $this->reg_errors->get_error_messages() ) ) {
			$userdata = array(
					'user_login'    =>   $this->username,
					'user_email'    =>   $this->email,
					'user_pass'     =>   $this->password,
					//'user_url'      =>   $this->website,
					'first_name'    =>   $this->first_name,
					'last_name'     =>   $this->last_name,
					//'nickname'      =>   $this->nickname,
					//'description'   =>   $this->bio,
			);
			//$user = wp_insert_user( $userdata );
			$re = $this->dolibarr->dolibarr_create_thirdparty($this->getAttributs());
			//echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';
			if(intval($re)>0)
				echo '<div> Les informations ont été enregistrées avec succès <a href="'.$_SERVER['REQUEST_URI'].'"> Nouvel abonnement</a></div>';
			else echo '<div> votre abonnement n\' pas été pris en compte<a href="'.$_SERVER['REQUEST_URI'].'"> Réessayez </a></div>';
			$ok=1;
		}
		return $ok;
	}
	public function setAttributs($arrAttributs) {
		if(!is_array($arrAttributs))
			return;
		//var_dump($this->attributs);
		foreach($this->attributs as $attrName)
		{ //var_dump(isset($this->attributs[$name]),$name);
			if(isset($arrAttributs[$attrName])){
				$this->$attrName=$arrAttributs[$attrName];
					
			}
		}
	}
	public function getAttributs() {
		$atts = array();
		foreach ($this->attributs as $attr) {
			$atts[$attr] = $this->$attr;
		}
		return $atts;
	}
	function sanitize () {
		// sanitize user form input
		$this->username   =   sanitize_user($this->username );
		$this->password   =   esc_attr($this->password);
		$this->email      =   sanitize_email($this->email );
		//$this->website    =   esc_url( $this->website );
		$this->first_name =   sanitize_text_field($this->first_name );
		$this->last_name  =   sanitize_text_field($this->last_name  );
		//$this->nickname   =   sanitize_text_field( $this->nickname );
		//$this->bio        =   esc_textarea( $this->bio  );

	}
	function custom_registration_function() {
		$ok = -1;
		if ( isset($_POST['submit'] ) ) {
			$this->setAttributs($_POST);
			//var_dump($this->getAttributs(),'alll');
			$this->registration_validation();
			$this->sanitize();
			// call @function complete_registration to create the user
			// only when no WP_error is found
			$ok = $this->complete_registration();
			//var_dump($ok);
				
		}

		if(intval($ok) < 0)$this->registration_form();
	}


	function xrvel_login_recaptcha_process() {
		if (array() == $_POST) {
			return true;
		}

		//$opt = get_option('xrvel_login_recaptcha_options');
		$secret = '6Le1EgoTAAAAAKIK8bsHkrGeWoC0c62gtc32MPnq';
		if ('' != trim($secret) && '' != trim($secret)) {
			$parameters = array(
					'secret' => trim($secret),
					'response' => $this->xrvel_login_recaptcha_get_post('g-recaptcha-response'),
					'remoteip' => $this->xrvel_login_recaptcha_get_ip()
			);
			$url = 'https://www.google.com/recaptcha/api/siteverify?' . http_build_query($parameters);

			$response = $this->xrvel_login_recaptcha_open_url($url);
			$json_response = json_decode($response, true);
			//var_dump($json_response,'1ere reponse');

			// 			if (isset($json_response['success']) && true !== $json_response['success']) {
			// 				header('Location: wp-login.php?login_recaptcha_err=1');
			// 				exit();
			// 			}
		}
	}
	function xrvel_login_recaptcha_get_post($var_name) {
		if (isset($_POST[$var_name])) {
			return $_POST[$var_name];
		} else {
			return '';
		}
	}
	function xrvel_login_recaptcha_get_ip() {
		return $_SERVER['REMOTE_ADDR'];
	}
	function xrvel_login_recaptcha_open_url($url) {
		if (function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($ch);
			curl_close($ch); //var_dump($url,'ooo');
		} else {
			$response = file_get_contents($url);
		}
		return trim($response);
	}

}
