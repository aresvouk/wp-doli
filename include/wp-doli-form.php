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
	public $code_coupon;
	public $isnewletter;
	public $attributs=array();
	public $reg_errors;
	public $produit ;
	public $dolibarr ;
	function __construct($dolibarr) {
		
		$this->dolibarr = $dolibarr;
		$prod = array();
		(is_object($this->dolibarr)) ?$this->produit  = $this->dolibarr->dolibarr_getProduit():array();
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
				'code_coupon',
				'isnewletter');
	}
	
	function  getAttributsLable() {
		return   array(
				'produit_id'=>'Produit',
				'username'=>'Longin',
				'password'=>'Password',
				'first_name'=>'Fisrt name',
				'last_name'=>'Lastname',
				'email'=>'Email',
				'website'=>'Web site',
				'tel'=>'TÉL/GSM',
				'employeur'=>'EMPLOYEUR',
				'livr_adresse'=>'aDRESSE DE LIVRAISON',
				'livr_code_postable'=>'CODE POSTAL (1)',
		
				'livr_ville'=>'VILLE(1)',
				'livr_pays'=>'PAYS (1)',
				'fac_adresse'=>'ADRESSE DE FACTURATION (2)',
				'fac_code_postable'=>'CODE POSTAL (2)',
		
				'fac_ville'=>'VILLE (2)',
				'fac_pays'=>'PAYS (2)',
				'password_confirme'=>'',
				'code_coupon'=>'COUPON CODE ',
				'isnewletter'=>"S'abonner à la
					newsletter d'Alter Echos");
		
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
		 foreach($this->produit as $id=>$value)
		 	$option .="<option value='$id'>$value</option>";
		}
		return $option;
	}
	function registration_form(  ) {
		$siteKey = '6Le1EgoTAAAAAHuvF_74Q1T7P30kKHLKDn5Ep9xq'; // votre clé publique

		echo  $this->getStyle();
		?>
	<script src="https://www.google.com/recaptcha/api.js"></script>
		
<form action="<?php  $_SERVER['REQUEST_URI'] ?>" method="post">
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
			<td><label for="firstname">First Name<strong>*</strong>
			</label>
			</td>
			<td><input type="text" name="first_name"
				value="<?=$this->first_name  ?>">
			</td>
		</tr>

		<tr>
			<td><label for="last_name">Last Name <strong>*</strong>
			</label>
			</td>
			<td><input type="text" name="last_name"
				value="<?=$this->last_name  ?>">
			</td>
		</tr>

		<tr>
			<td><label for="tel">TÉL/GSM</label> <strong>*</strong>
			</td>
			<td><input type="text" name="tel" value="<?=$this->tel  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="employeur">EMPLOYEUR</label> <strong>*</strong>
			</td>
			<td><input type="text" name="employeur"
				value="<?=$this->employeur  ?>">
			</td>
		</tr>


		<tr>
			<td><label for="livr_adresse">ADRESSE DE LIVRAISON</label> <strong>*</strong>
			</td>
			<td><input type="text" name="livr_adresse"
				value="<?=$this->livr_adresse  ?>">
			</td>
		</tr>



		<tr>
			<td><label for="livr_code_postable">CODE POSTAL (1)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="livr_code_postable"
				value="<?=$this->livr_code_postable  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="livr_ville">VILLE (1)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="livr_ville"
				value="<?=$this->livr_ville  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="livr_pays">PAYS (1)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="livr_pays"
				value="<?=$this->livr_pays  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="fac_adresse">ADRESSE DE FACTURATION</label> <strong>*</strong>
			</td>
			<td><input type="text" name="fac_adresse"
				value="<?=$this->fac_adresse  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="fac_code_postable">CODE POSTAL (2)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="fac_code_postable"
				value="<?=$this->fac_code_postable  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="fac_ville">VILLE (2)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="fac_ville"
				value="<?=$this->fac_ville  ?>">
			</td>
		</tr>
		<tr>
			<td><label for="fac_pays">PAYS (2)</label> <strong>*</strong>
			</td>
			<td><input type="text" name="fac_pays" value="<?=$this->fac_pays  ?>">
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
		<tr>
			<td><label for="email">Email <strong>*</strong>
			</label>
			</td>
			<td><input type="text" name="email" value="<?=$this->email ?>">
			</td>
		</tr>
		<!--  
		<tr>
			<td><label for="password">Password <strong>*</strong>
			</label>
			</td>
			<td><input type="password" name="password"
				value="<?=$this->password ?>" />
			</td>
		</tr>-->
        <!--  
		<tr>
			<td><label for="password_confirme">PASSWORD CONFIRMATION <strong>*</strong>
			</label>
			</td>
			<td><input type="password" name="password_confirme"
				value="<?=$this->password_confirme ?>" />
			</td>
		</tr>-->
		<tr>
			<td><label for="code_coupon">COUPON CODE</label>
			</td>
			<td><input type="text" name="code_coupon"
				value="<?=$this->code_coupon ?>">
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
	<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
		<div class='submit'><input type="submit" name="submit" value="Register" /></div>
	
</form>
<?php 
	}
	function registration_validation(  )  {
        $arrRequired = array('email','first_name','last_name','tel','livr_adresse','fac_adresse','livr_code_postable','livr_pays','fac_pays','fac_code_postable','fac_ville');
       $labels = $this->getAttributsLable();
       foreach ($arrRequired as $attrName) {
       	if(empty( $this->$attrName )) $this->reg_errors->add($attrName, $labels[$attrName].'  Required');
       }
// 		if (   empty( $this->email ) || empty( $this->first_name )
// 				|| empty( $this->last_name ) || empty( $this->tel ) || empty( $this->livr_adresse )
// 				|| empty( $this->fac_adresse ) || empty( $this->livr_code_postable )|| empty( $this->livr_pays )
// 				|| empty( $this->fac_pays ) || empty( $this->fac_code_postable )|| empty( $this->fac_ville )
// 		) {
// 			$this->reg_errors->add('field', 'Required form field is missing');
// 		}
		// 		if ( 4 > strlen( $this->username ) ) {
		// 			$this->reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
		// 		}
		// 		if ( username_exists( $this->username ) )
		// 			$this->reg_errors->add('user_name', 'Sorry, that username already exists!');
		// 		if ( ! validate_username( $this->username ) ) {
		// 			$this->reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
		// 		}
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
		// 		if ( ! empty( $this->website ) ) {
		// 			if ( ! filter_var( $this->website, FILTER_VALIDATE_URL ) ) {
		// 				$this->reg_errors->add( 'website', 'Website is not a valid URL' );
		// 			}
		// 		}
		$secret = '6Le1EgoTAAAAAKIK8bsHkrGeWoC0c62gtc32MPnq';
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
			if (!$resp->success || is_null($resp)) {
			
			    $this->reg_errors->add( 'invalid_captcha', 'CAPTCHA incorrect' );
			}
			//if($_POST["g-recaptcha-response"]=='')$this->reg_errors->add( 'invalid_captcha', 'CAPTCHA incorrect' );
			//var_dump($_SERVER["REMOTE_ADDR"], $REMOTE_ADDR, $_POST["g-recaptcha-response"], $resp);
		}

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


}
