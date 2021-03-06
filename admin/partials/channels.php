<?php 
    global $wpdb;
  $channelTable = new Channel_Table($wpdb);
  $channelTable->prepare_items();
?>
<div class="wrap">
  <h1>Channels</h1>
  <?php $channelTable->display(); ?>
</div>
<?php 

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Channel_Table extends Nexus_Table
{

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'channelId'       => 'ID',
            'name' 	    => 'Name',
            'type'      => 'Type',
            'createdAt'   => 'Created',
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
        return array('name' => array('name', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    public function table_data()
    {
        // $nexusbot_url = get_option('nexusbot_url');
        // $request = wp_remote_get($nexusbot_url.'/channels');
        // $channels = json_decode( wp_remote_retrieve_body( $request ), ARRAY_A);

        $channels = $this->wpdb->get_results("SELECT * FROM na_channels ORDER BY `name` ASC", ARRAY_A);
        return $channels;
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
            case 'channelId':
            case 'name':
            case 'type':
                return $item[ $column_name ];
            case 'createdAt':
                return date('Y-m-d', $item[ $column_name ] ); // / 1000

            default:
                return print_r( $item, true ) ;
        }
    }

}