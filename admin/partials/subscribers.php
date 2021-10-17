<?php 
    global $wpdb;
    $subscribersTable = new Subscribers_Table($wpdb);
    $subscribersTable->prepare_items();

?>
<div class="wrap">
  <h1>Subscribers</h1>
  <?php $subscribersTable->display(); ?>
</div>
<?php 

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Subscribers_Table extends Nexus_Table
{

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'email'			=> 'Email',
            'active' 		=> 'Active',
            'from_discord' 	=> 'Discord',
            'createdAt'      => 'Subscribed'
        );

        return $columns;
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('email' => array('email', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    public function table_data()
    {
  		$subscribers = $this->wpdb->get_results("SELECT * FROM na_subscribers ORDER BY email ASC", ARRAY_A);

        return $subscribers;
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
            case 'email':
                return $item[ $column_name ];
            case 'active':
            case 'from_discord':
                return $item[ $column_name ]?'Yes':'No';
            case 'createdAt':
                return date('F j, Y', strtotime($item[ $column_name ]));
            default:
                return print_r( $item, true ) ;
        }
    }

}