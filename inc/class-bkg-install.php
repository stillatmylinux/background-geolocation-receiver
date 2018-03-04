<?php

if( !defined( 'ABSPATH' ) ){
    exit;
}

if (! class_exists( 'STIL_BKGGEO_Install' ) ){

    /**Create plugin table
     * @since 1.0.0
     * Class STIL_BKGGEO_Install
     */
    class STIL_BKGGEO_Install {

        /**Single instance of class
         * @since 1.0.0
         * @var \STIL_BKGGEO_Install
         */
        private static $instance;

        /**The table name
         * @since 1.0.0
         * @var string
         */
        public $_table_name;

        /**Construct class
         * @since 1.0.0
         */
        public function __construct(){

            global $wpdb;

            $this->_table_name  =   $wpdb->prefix. 'bkg_geo';
            $wpdb->yith_wsfl_table  =   $this->_table_name;

            define( 'BKGGEO_LIST_TABLE', $this->_table_name );

        }

        public function init(){

			$this->_add_table();
            update_option( 'bkggeo_db_version', BKGL_DB_VERSION );
        }

        /** update db
         * @author STILEBKGG         * @since 1.0.1
         */
        public function update(){

            $this->_update_table();
            update_option( 'bkggeo_db_version', BKGL_DB_VERSION );
        }

        public function is_table_created(){
            global $wpdb;
            $number_of_tables = $wpdb->query("SHOW TABLES LIKE '{$this->_table_name}'" );

            return (bool) ( $number_of_tables == 1 );

        }
        private function _add_table(){
            if( !$this->is_table_created() ) {

				global $wpdb;
				
				$sql    =   "CREATE TABLE {$this->_table_name} (
                                id int( 11 ) NOT NULL AUTO_INCREMENT,
                                `latitude` varchar(64) NOT NULL,
                                `longitude` varchar(64) NOT NULL,
                                `accuracy` varchar(32) NOT NULL,
                                `speed` varchar(32) NOT NULL,
                                `altitude` varchar(32) NOT NULL,
                                `uuid` varchar(64) NOT NULL,
                                `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                  PRIMARY KEY( id )
                                )DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

                if (! function_exists( 'dbDelta' ) )
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

                dbDelta($sql);
            }
            return;
        }

        /** add a column in table
         * @author STILEBKGG         * @since 1.0.1
        */
        private function _update_table(){

            global $wpdb;

            // $sql    =   "";
            // $wpdb->query( $sql );

            return;
        }

        /**return single instance of class
         * @since 1.0.0
         * @return STIL_BKGGEO_Install
         */
        public static function  get_instance(){
            if( is_null( self::$instance ) ){
                self::$instance = new self();
            }
            return self::$instance;
        }



    }
}

function STIL_BKGGEO_Install(){
    return STIL_BKGGEO_Install::get_instance();
}