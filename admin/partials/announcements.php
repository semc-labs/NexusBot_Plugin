<?php 
    global $wpdb;
  $announcementsTable = new Announcements_Table($wpdb);
  $announcementsTable->prepare_items();
?>
<div class="wrap">
  <h1>Announcements</h1>
  <?php $announcementsTable->display(); ?>
</div>
<?php 

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Announcements_Table extends Nexus_Table
{

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'name'        => 'Author',
            'content' 	    => 'Content',
            'createdAt'     => 'Created',
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
        return array('user' => array('user', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    public function table_data()
    {
        // $nexusbot_url = get_option('nexusbot_url');
        // $request = wp_remote_get($nexusbot_url.'/announcements');
  		// $announcements = json_decode( wp_remote_retrieve_body( $request ), ARRAY_A);
          
          $announcements = $this->wpdb->get_results("SELECT naa.*, nam.name FROM na_announcements AS naa
                                                    LEFT JOIN na_members AS nam ON nam.userId = naa.userId ", ARRAY_A);
        return $announcements;
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
            case 'name':
                return $item[ $column_name ];
            case 'content':
                return apply_filters('the_content',$item[ $column_name ]);
            case 'createdAt':
                return date('Y-m-d @ H:i:s', strtotime($item[ $column_name ]));
            default:
                return print_r( $item, true ) ;
        }
    }

}