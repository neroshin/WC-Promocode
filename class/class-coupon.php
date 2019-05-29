<?php
/**
 * Class to handle coupon operations
 * Changes by Alex Rabinovich (@putchi)
 * 
 * @author Joash Pereira
 * @date  2015-06-05
 */
class coupon {
    CONST MIN_LENGTH = 8;
    
	function __construct( ) {
		// Display Fields
		add_action('woocommerce_product_options_general_product_data',  array($this ,'woocommerce_product_custom_fields'));
		// Save Fields
		add_action('woocommerce_process_product_meta',  array($this ,'woocommerce_product_custom_fields_save'));
		add_action( 'wp_ajax_nopriv_action_apply_coupon', array($this ,'action_apply_coupon') );
		add_action( 'wp_ajax_action_apply_coupon', array($this ,'action_apply_coupon') );
		
		///
		
		
		add_action('init', array($this ,'register_session_code'));
		
		// add the filter 
		add_filter( 'woocommerce_shortcode_products_query',  array($this ,'filter_woocommerce_shortcode_products_query'), 10, 2 ); 
		
		
		
		// add the filter 
		add_filter( 'woocommerce_product_query_meta_query', array($this,'filter_woocommerce_product_query_meta_query'), 10, 2 );
		
		add_action( 'manage_product_posts_custom_column',  array($this ,'wpcoupon_product_column_offercode'), 10, 2 );
		 
		add_filter( 'manage_edit-product_columns', array($this ,'show_wpcoupon_product'),15 );
		
		add_action('wp_logout',  array($this ,'clear_session_on_logout'));
	
		 //add_filter( 'woocommerce_product_related_posts_query',  array($this ,'custom_product_related_posts_query') , 10  );
		
		
	}
	function filter_woocommerce_product_query_meta_query( $meta_query, $instance ) { 
 
	 $count_row = apply_filters("db_fetch_coupon" ,$_SESSION['promocode']);
		$meta_query[] = array(
		 'relation' => 'OR',
		 
			array(
				'key'     => '_select',
				'value'   => $count_row[0]->promo_category,
				'compare' => '==',
			),
			array(
				'key'     => '_select',
				'value'   => "none",
				'compare' => '==',
			),
		);
		return $meta_query; 
	}
	function custom_product_related_posts_query( $query ){
		global $wpdb;
		$count_row = apply_filters("db_fetch_coupon" ,$_SESSION['promocode']);
		$query['join']  .= " INNER JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id ";
		$query['where'] .= " AND pm.meta_key = '_select' AND meta_value LIKE '$count_row' ";

		return $query;
	}
	
	
	function clear_session_on_logout() {
	   session_destroy();
	}
	
	function show_wpcoupon_product($columns){

	   //add column
	   $columns['coupon_code_product'] = __( 'Promocode'); 

	   return $columns;
	}
	
	function wpcoupon_product_column_offercode( $column, $postid ) {
		if ( $column == 'coupon_code_product' ) {
			echo get_post_meta( $postid, '_select', true );
		}
	} 
	
	
	
	
	
	// define the woocommerce_shortcode_products_query callback 
	function filter_woocommerce_shortcode_products_query( $args, $atts ) { 
	 
	 $count_row = apply_filters("db_fetch_coupon" ,$_SESSION['promocode']);
		//echo $count_row[0]->count_row;
			
		 $args['meta_query'] = array(
			
			'relation'  => 'OR',
			array(
				'key'     => '_select',
				'value'   => $count_row[0]->promo_category,
				'compare' => '==',
			),
			array(
				'key'     => '_select',
				'value'   => "none",
				'compare' => '==',
			),
		);

			
		return $args; 
	}
		
	
	
	function register_session_code()
	{
		
		

		session_start();
		if(isset($_POST['promocode_apply'])){
			 $check_coupon = apply_filters("check_coupon" ,$_POST['promocode_apply']);
		}
		else{
			$check_coupon = apply_filters("check_coupon" ,$_SESSION['promocode']);
		}
	  
		
	  if( isset($_POST['promocode_apply']) && $check_coupon[0]->count_row != 0 )
	  {
		
		$_SESSION['promocode'] = $_POST["promocode_apply"];
	  }
	  if($check_coupon[0]->count_row == 0 && isset($_POST['promocode_apply'])){
		  
	  }
	}	

	function action_apply_coupon() {
		session_start();
		$_SESSION['promocode'] = $_POST["code"];
		
		echo $_SESSION['promocode'];
		die();
	}
	function woocommerce_product_custom_fields()
	{
		global $woocommerce, $post;
		
		//print_r($post);
		$defualt_value = get_post_meta( $post->ID, '_select', true );
		$promo_categories = get_option( 'coupon-category', $coupon_category );
		
		$option = array();
		$option["none"] = __( "none" , 'woocommerce' );
		foreach($promo_categories as $key => $value){
			$option[$value["id"]] = __( $value["name"], 'woocommerce' );
		}
		//print_r($option);
		echo '<div class="product_custom_field options_group show_if_promocode" >';
		// Custom Product Text Field
		 woocommerce_wp_select( array(
			'id'      => '_select',
			'label'   => __( 'Promocode', 'woocommerce' ),
			'options' =>  $option, //this is where I am having trouble
			'value'   => $defualt_value ,
			
		) );
		echo '</div>';
		

	   /*  $retrive_data = WC()->session->get( 'name_for_your_data' );
		
		echo  $retrive_data; */
	} 

	function woocommerce_product_custom_fields_save($post_id)
	{
		// WooCommerce custom dropdown Select
		   $woocommerce_custom_product_select = $_POST['_select'];
	
			update_post_meta($post_id, '_select', esc_attr($woocommerce_custom_product_select));
	}
	
	
	
	/* Utility object to html format option */
	static public function array_to_optionitem($item){
		$html = "";
		foreach($item as $key => $value){
			$html .=  "<option value='".$value["id"]."' selected='selected'>".$value["name"]."</option>";
		}
		return  $html;
	}
	
	
	/* Utility object to array convertion */
	static public function objToArray($obj, &$arr = null){

		if(!is_object($obj) && !is_array($obj)){
			$arr = $obj;
			return $arr;
		}

		foreach ($obj as $key => $value)
		{
			if (!empty($value))
			{
				$arr[$key] = array();
				self::objToArray($value, $arr[$key]);
			}
			else
			{
				$arr[$key] = $value;
			}
		}
		return $arr;
	}
    /**
     * MASK FORMAT [XXX-XXX]
     * 'X' this is random symbols
     * '-' this is separator
     *
     * @param array $options
     * @return string
     * @throws Exception
     */
    static public function generate($options = []) {

        $length         = (isset($options['length']) ? filter_var($options['length'], FILTER_VALIDATE_INT, ['options' => ['default' => self::MIN_LENGTH, 'min_range' => 1]]) : self::MIN_LENGTH );
        $prefix         = (isset($options['prefix']) ? self::cleanString(filter_var($options['prefix'], FILTER_SANITIZE_STRING)) : '' );
        $suffix         = (isset($options['suffix']) ? self::cleanString(filter_var($options['suffix'], FILTER_SANITIZE_STRING)) : '' );
        $useLetters     = (isset($options['letters']) ? filter_var($options['letters'], FILTER_VALIDATE_BOOLEAN) : true );
        $useNumbers     = (isset($options['numbers']) ? filter_var($options['numbers'], FILTER_VALIDATE_BOOLEAN) : false );
        $useSymbols     = (isset($options['symbols']) ? filter_var($options['symbols'], FILTER_VALIDATE_BOOLEAN) : false );
        $useMixedCase   = (isset($options['mixed_case']) ? filter_var($options['mixed_case'], FILTER_VALIDATE_BOOLEAN) : false );
        $mask           = (isset($options['mask']) ? filter_var($options['mask'], FILTER_SANITIZE_STRING) : false );

        $uppercase    = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];
        $lowercase    = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm'];
        $numbers      = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $symbols      = ['`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '\\', '|', '/', '[', ']', '{', '}', '"', "'", ';', ':', '<', '>', ',', '.', '?'];

        $characters   = [];
        $coupon = '';

        if ($useLetters) {
            if ($useMixedCase) {
                $characters = array_merge($characters, $lowercase, $uppercase);
            } else {
                $characters = array_merge($characters, $uppercase);
            }
        }

        if ($useNumbers) {
            $characters = array_merge($characters, $numbers);
        }

        if ($useSymbols) {
            $characters = array_merge($characters, $symbols);
        }

        if ($mask) {
            for ($i = 0; $i < strlen($mask); $i++) {
                if ($mask[$i] === 'X') {
                    $coupon .= $characters[mt_rand(0, count($characters) - 1)];
                } else {
                    $coupon .= $mask[$i];
                }
            }
        } else {
            for ($i = 0; $i < $length; $i++) {
                $coupon .= $characters[mt_rand(0, count($characters) - 1)];
            }
        }

        return $prefix . $coupon . $suffix;
    }

    /**
     * @param int $maxNumberOfCoupons
     * @param array $options
     * @return array
     */
    static public function generate_coupons($maxNumberOfCoupons = 1, $options = []) {
        $coupons = [];
        for ($i = 0; $i < $maxNumberOfCoupons; $i++) {
            $temp = self::generate($options);
            $coupons[] = $temp;
        }
        return $coupons;
    }

    /**
     * @param int $maxNumberOfCoupons
     * @param $filename
     * @param array $options
     */
    static public function generate_coupons_to_xls($maxNumberOfCoupons = 1, $filename, $options = []) {
        $filename = (empty(trim($filename)) ? 'coupons' : trim($filename));

        header('Content-Type: application/vnd.ms-excel');

        echo 'Coupon Codes' . "\t\n";
        for ($i = 0; $i < $maxNumberOfCoupons; $i++) {
            $temp = self::generate($options);
            echo $temp . "\t\n";
        }

        header('Content-disposition: attachment; filename=' . $filename . '.xls');
    }

    /**
     * Strip all characters but letters and numbers
     * @param $string
     * @param array $options
     * @return string
     * @throws Exception
     */
    static private function cleanString($string, $options = []) {
        $toUpper = (isset($options['uppercase']) ? filter_var($options['uppercase'], FILTER_VALIDATE_BOOLEAN) : false);
        $toLower = (isset($options['lowercase']) ? filter_var($options['lowercase'], FILTER_VALIDATE_BOOLEAN) : false);

        $striped = preg_replace('/[^a-zA-Z0-9]/', '', trim($string));

        // make uppercase
        if ($toLower && $toUpper) {
            throw new Exception('You cannot set both options (uppercase|lowercase) to "true"!');
        } else if ($toLower) {
            return strtolower($striped);
        } else if ($toUpper) {
            return strtoupper($striped);
        } else {
            return $striped;
        }
    }
}
new coupon();