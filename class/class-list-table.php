<?php 


/**
 * Create a new table class that will extend the WP_List_Table
 */
class List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
	var $data_list;
		
	
    public function prepare_items($perpage = 20 , $data_columns)
    {
        $columns = $data_columns;
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->data_list;
		
        usort( $data, array( &$this, 'sort_data' ) );
        $perPage = $perpage ;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'      => 'ID',
			'date'    => 'Date Created',
            'exp_date'=> 'Expiration Date',
			'code'    => 'Coupon Code',	
            'email'   => 'Email Address',
        );
        return $columns;
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('title' => array('title', false));
    }
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        return $data;
    }
	
	/**
     * Set the table data
     *
     * @return Array
     */
    public function set_table_data($data_list)
    {
       $this->data_list = $data_list;
    }
	
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'project':
            case 'user_id':
            case 'name':
            case 'email':
            case 'role':
            case 'location':
            case 'category':
            case 'public':
            case 'show_email':
            case 'exp_date':
            case 'date':
            case 'promo_category':
            case 'last_update':
            case 'description':
            case 'code':
                return $item[ $column_name ];
			case 'action':
				$add_meta_nonce = wp_create_nonce( 'promo_delete_form_nonce' );
				
				return "<a href='".get_home_url()."/wp-admin/admin-post.php?action=promo_delete&promo_delete_meta_form_nonce=".$add_meta_nonce."&id=".$item['id']."'>Delete</a>";
            default:
                return print_r( $item, true );	
        }
    }
	
    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }
}







?>