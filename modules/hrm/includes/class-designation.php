<?php
namespace WeDevs\ERP\HRM;

/**
 * The designation class
 */
class Designation {

    /**
     * Initialize a designation
     *
     * @param int|object  the designation numeric id or a wpdb row
     */
    public function __construct( $designation = '' ) {

        if ( is_object( $designation ) ) {

            $this->populate( $designation );

        } elseif ( is_int( $designation ) ) {

            $fetched = $this->get_by_id( $designation );
            $this->populate( $fetched );

        }
    }

    /**
     * [populate description]
     *
     * @param  object  the company wpdb object
     *
     * @return void
     */
    private function populate( $designation ) {
        $this->id   = (int) $designation->id;
        $this->name = stripslashes( $designation->title );
        $this->data = $designation;
    }

    /**
     * Magic method to get designation data values
     *
     * @param  string
     *
     * @return string
     */
    public function __get( $key ) {
        if ( isset( $this->data->$key ) ) {
            return stripslashes( $this->data->$key );
        }
    }

    /**
     * Get a company by ID
     *
     * @param  int  company id
     *
     * @return object  wpdb object
     */
    private function get_by_id( $designation_id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}erp_hr_designations WHERE id = %d", $designation_id ) );
    }

    /**
     * Get number of employee belongs to this department
     *
     * @return int
     */
    public function num_of_employees() {
        global $wpdb;

        $sql = "SELECT SUM(id) FROM {$wpdb->prefix}erp_hr_employees WHERE status = 1 AND designation = %d";
        $number = (int) $wpdb->get_var( $wpdb->prepare( $sql, $this->id ) );

        return $number;
    }
}