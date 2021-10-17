<?php 
    global $wpdb;
  $membersTable = new Members_Table($wpdb);
  $membersTable->prepare_items();
?>
<div class="wrap">
  <h1>Members</h1>
  <?php $membersTable->display(); ?>
</div>
<?php 

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Members_Table extends Nexus_Table
{

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'avatar' => 'Avatar',
            'name' 	=> 'Username',
            'userId'        => 'ID',
            'status'      => 'Status',
            //'createdTimestamp' => 'Created',
            'roles'       => 'Roles'
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
        return array('username' => array('username', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    public function table_data()
    {
        $nexusbot_url = get_option('nexusbot_url');
        $request = wp_remote_get($nexusbot_url.'/members');
  		$members = json_decode( wp_remote_retrieve_body( $request ), ARRAY_A);

        //pre_print($members);
        // NOTE: Currently not saving user avatars in the DB. Also do not have "Presense"
        // $members = $this->wpdb->get_results("SELECT * FROM na_members ORDER BY `name` ASC", ARRAY_A);
        
        // if(! empty($members)){
        //     foreach($members as $key => $member) {
        //         $members[$key]['info'] = json_decode($member['info']);
        //     }
        // }

        // pre_print($members);

        return $members;
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
            case 'avatar':
                return '<img src="'.$item['avatarURL'].'" width="128" height="128" />';
            case 'userId':
            case 'name':
                return $item[ $column_name ];
            case 'bot':
                return $item[ 'bot' ]?'Yes':'No';
            case 'createdAt':
                return date('Y-m-d', $item[ $column_name ] / 1000);
        
            case 'status':
                return $item['presence']['status'];
            case 'roles':
                $roles = [];
                foreach($item['roles'] as $role) {
                    $roles[] = $role['name'];
                }
                return implode(',', $roles);
            default:
                return print_r( $item, true ) ;
        }
    }

}