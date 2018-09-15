<?php

if( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Implements free features of YIT WooCommerce Watermark plugin
 *
 * @class   YITH_WC_Watermark
 * @package YITHEMES
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

if( !class_exists( 'YITH_WC_Watermark' ) ) {

    class YITH_WC_Watermark
    {

        /**
         * @var YITH_WC_Watermark single instance of class
         */
        protected static $_instance;
        /**
         * Panel object
         *
         * @var     /Yit_Plugin_Panel object
         * @since   1.0.0
         * @see     plugin-fw/lib/yit-plugin-panel.php
         */
        protected $_panel;

        /**
         * @var $_premium string Premium tab template file name
         */
        protected $_premium = 'premium.php';

        /**
         * @var string Premium version landing link
         */
        protected $_premium_landing_url = 'https://yithemes.com/themes/plugins/yith-woocommerce-watermark/';

        /**
         * @var string Plugin official documentation
         */
        protected $_official_documentation = 'https://yithemes.com/docs-plugins/yith-woocommerce-watermark/';

        /**
         * @var string plugin official live demo
         */
        protected $_premium_live_demo = 'http://plugins.yithemes.com/yith-woocommerce-watermark/';

        /**
         * @var string Yith WooCommerce Watermark panel page
         */
        protected $_panel_page = 'yith_ywcwat_panel';

        /**
         * @var string suffix for load minified js
         */
        protected $_suffix;

        public function __construct()
        {

            add_action( 'admin_notices', array( $this, 'show_message_to_user' ) );
            add_action( 'admin_init', array( $this, 'hide_message_for_user' ) );
            // Load Plugin Framework
            add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
            //Add action links
            add_filter( 'plugin_action_links_' . plugin_basename( YWCWAT_DIR . '/' . basename( YWCWAT_FILE ) ), array( $this, 'action_links' ) );
            //Add row meta
            add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
            //Add tab premium
            add_action( 'yith_wc_watermark_premium', array( $this, 'premium_tab' ) );
            //Add Yith Watermark menu
            add_action( 'admin_menu', array( $this, 'add_ywcwat_menu' ), 5 );

            //add ajax action for apply all watermark
            add_action( 'wp_ajax_yith_apply_all_watermark', array( $this, 'yith_apply_all_watermark' ) );
            add_action( 'wp_ajax_nopriv_yith_apply_all_watermark', array( $this, 'yith_apply_all_watermark' ) );

            //add ajax action for remove watermark
            add_action( 'wp_ajax_ywcwat_remove_watermark', array( $this, 'ywcwat_remove_watermark' ) );
            add_action( 'wp_ajax_nopriv_ywcwat_remove_watermark', array( $this, 'ywcwat_remove_watermark' ) );


            $this->_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

            if( is_admin() ) {

                $this->__includes();
                //Add custom type in plugin option
                add_action( 'woocommerce_admin_field_watermark-select', 'YITH_Watermark_Select::output' );
                add_action( 'woocommerce_admin_field_custom-button', array( $this, 'show_backup_btn' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_free_scripts' ) );
                add_action( 'add_meta_boxes', array( $this, 'add_product_meta_boxes' ), 10 );
                add_action( 'wp_ajax_save_watermark_on_single_product', array( $this, 'save_watermark_on_single_product' ) );
                add_filter( 'wp_generate_attachment_metadata', array( $this, 'save_watermark_on_attachment_image' ),10, 2 );
            }


            add_action( 'ywcwat_build_watermark_image', array( $this, 'build_watermark_image' ), 10, 2 );

        }

        /** return single instance of class
         * @author YITHEMES
         * @since 1.0.0
         * @return YITH_WC_Watermark
         */

        public static function get_instance()
        {

            if( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }


        /**include files
         * @author YITHEMES
         * @since 1.0.0
         */
        private function __includes()
        {

            include_once( YWCWAT_TEMPLATE_PATH . '/admin/watermark-select.php' );
        }

        public function plugin_fw_loader()
        {
            if( !defined( 'YIT_CORE_PLUGIN' ) ) {
                global $plugin_fw_data;
                if( !empty( $plugin_fw_data ) ) {
                    $plugin_fw_file = array_shift( $plugin_fw_data );
                    require_once( $plugin_fw_file );
                }
            }
        }

        /**
         * Action Links
         *
         * add the action links to plugin admin page
         *
         * @param $links | links plugin array
         *
         * @return   mixed Array
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         * @return mixed
         * @use plugin_action_links_{$plugin_file_name}
         */
        public function action_links( $links )
        {

            $links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'yith-woocommerce-watermark' ) . '</a>';

            $premium_live_text = defined( 'YWCWAT_FREE_INIT' ) ? __( 'Premium live demo', 'yith-woocommerce-watermark' ) : __( 'Live demo', 'yith-woocommerce-watermark' );

            $links[] = '<a href="' . $this->_premium_live_demo . '" target="_blank">' . $premium_live_text . '</a>';

            if( defined( 'YWCWAT_FREE_INIT' ) ) {
                $links[] = '<a href="' . $this->get_premium_landing_uri() . '" target="_blank">' . __( 'Premium Version', 'yith-woocommerce-watermark' ) . '</a>';
            }

            return $links;
        }

        /**
         * plugin_row_meta
         *
         * add the action links to plugin admin page
         *
         * @param $plugin_meta
         * @param $plugin_file
         * @param $plugin_data
         * @param $status
         *
         * @return   Array
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         * @use plugin_row_meta
         */
        public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status )
        {
            if( ( defined( 'YWCWAT_INIT' ) && ( YWCWAT_INIT == $plugin_file ) ) ||
                ( defined( 'YWCWAT_FREE_INIT' ) && ( YWCWAT_FREE_INIT == $plugin_file ) )
            ) {

                $plugin_meta[] = '<a href="' . $this->_official_documentation . '" target="_blank">' . __( 'Plugin Documentation', 'yith-woocommerce-watermark' ) . '</a>';
            }

            return $plugin_meta;
        }

        /**
         * Get the premium landing uri
         *
         * @since   1.0.0
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         * @return  string The premium landing link
         */
        public function get_premium_landing_uri()
        {
            return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing_url . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing_url . '?refer_id=1030585';
        }

        /**
         * Premium Tab Template
         *
         * Load the premium tab template on admin page
         *
         * @since   1.0.0
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         * @return  void
         */
        public function premium_tab()
        {
            $premium_tab_template = YWCWAT_TEMPLATE_PATH . '/admin/' . $this->_premium;
            if( file_exists( $premium_tab_template ) ) {
                include_once( $premium_tab_template );
            }
        }

        /**
         * Add a panel under YITH Plugins tab
         *
         * @return   void
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         * @use     /Yit_Plugin_Panel class
         * @see      plugin-fw/lib/yit-plugin-panel.php
         */
        public function add_ywcwat_menu()
        {
            if( !empty( $this->_panel ) ) {
                return;
            }

            $admin_tabs = apply_filters( 'ywcwat_add_premium_tab', array(
                'general-settings' => __( 'Settings', 'yith-woocommerce-watermark' ),
                'premium-landing' => __( 'Premium Version', 'yith-woocommerce-watermark' )
            ) );

            $args = array(
                'create_menu_page' => true,
                'parent_slug' => '',
                'page_title' => __( 'Watermark', 'yith-woocommerce-watermark' ),
                'menu_title' => __( 'Watermark', 'yith-woocommerce-watermark' ),
                'capability' => 'manage_options',
                'parent' => '',
                'parent_page' => 'yit_plugin_panel',
                'page' => $this->_panel_page,
                'admin-tabs' => $admin_tabs,
                'options-path' => YWCWAT_DIR . '/plugin-options'
            );

            $this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
        }

        /** Set a limit in percentage for watermark size
         * @author YITHEMES
         * @since 1.0.1
         * @return mixed|void
         */
        public function get_perc_size()
        {

            return apply_filters( 'ywcwat_perc_size', 25 );
        }

        /** apply the watermark on 15 products at a time.
         * @author YITHEMES
         * @since 1.0.0
         * @return mixed|void
         */
        public function get_max_item_task()
        {
            return apply_filters( 'ywcwat_max_item_task', 15 );
        }

        /**include free style and free script in admin
         * @author YITHEMES
         * @since 1.0.0
         */
        public function admin_enqueue_free_scripts()
        {
            if( isset( $_GET['page'] ) && 'yith_ywcwat_panel' == $_GET['page'] ) {
                wp_register_script( 'ywcwat_panel_admin_script', YWCWAT_ASSETS_URL . 'js/ywcwat_admin' . $this->_suffix . '.js', array( 'jquery' ), YWCWAT_VERSION, true );
                wp_enqueue_script( 'jquery-ui-progressbar' );


                $size = wc_get_image_size( 'shop_single' );

                $perc_size =  $this->get_perc_size();

                $max_w = round( ( $size['width'] * $perc_size ) / 100 );
                $max_h = round( ( $size['height'] * $perc_size ) / 100 );

                $ywcwat_params = apply_filters( 'ywcwat_admin_panel_script_parameters', array(
                    'ajax_url' => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
                    'attach_id' => $this->get_ids_attach(),
                    'max_item_action' => $this->get_max_item_task(),
                    'perc_w' => intval( $max_w ),
                    'perc_h' => intval( $max_h ),
                    'messages' => array(
                        'complete_single_task' => __( 'The watermark has been applied to', 'yith-woocommerce-watermark' ),
                        'single_product' => __( 'Product', 'yith-woocommerce-watermark' ),
                        'on' => __( 'on', 'yith-woocommerce-watermark' ),
                        'more_product' => __( 'Products', 'yith-woocommerce-watermark' ),
                        'complete_all_task' => __( 'Completed', 'yith-woocommerce-watermark' ),
                        'error_watermark_sizes' => sprintf( '%s %s %s %s x %s .', __( 'You can\'t use images bigger than', 'yith-woocommerce-watermark' ),

                            $perc_size . '%',
                            __( 'of the size of the "Single Product image", that is', 'yith-woocommerce-watermark' ),
                            $max_w,
                            $max_h ),
                        'log_message' => __( 'Attach id ', 'yith-woocommerce-watermark' ),
                        'reset_confirm' => __( 'Images will be restored, are you sure? ', 'yith-woocommerce-watermark' ),
                        'singular_success_image' => __( 'Image has been deleted', 'yith-woocommerce-watermark' ),
                        'plural_success_image' => __( 'Images have been deleted', 'yith-woocommerce-watermark' ),
                        'singular_error_image' => __( 'Image has not been deleted', 'yith-woocommerce-watermark' ),
                        'plural_error_image' => __( 'Images have not been deleted', 'yith-woocommerce-watermark' ),
                        'error_messages' => $this->get_messages(),
                        'shop_sizes' => array(
                            'shop_single' => __( 'Shop Single', 'yith-woocommerce-watermark' ),
                            'shop_thumbnail' => __( 'Shop Thumbnail', 'yith-woocommerce-watermark' ),
                            'shop_catalog' => __( 'Shop Catalog', 'yith-woocommerce-watermark' ),
                            'full' => __( 'Full Size', 'yith-woocommerce-watermark' )
                        ),

                    ),
                    'actions' => array(
                        'apply_all_watermark' => 'yith_apply_all_watermark',
                        'remove_watermark' => 'ywcwat_remove_watermark',
                        'change_thumbnail_image' => 'change_thumbnail_image',

                    )
                ) );

                wp_localize_script( 'ywcwat_panel_admin_script', 'ywcwat_params', $ywcwat_params );
                wp_enqueue_script( 'ywcwat_panel_admin_script' );
                wp_enqueue_style( 'ywcwat_admin_style', YWCWAT_ASSETS_URL . 'css/ywcwat_admin.css', array(), YWCWAT_VERSION );
            }

            global $post;

            if( isset( $post ) && get_post_type( $post ) == 'product' ) {


                wp_enqueue_style('wp-color-picker');
                wp_register_script( 'ywcwat_product_admin_script', YWCWAT_ASSETS_URL . 'js/ywcwat_admin_single_product' . $this->_suffix . '.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-dialog' ), YWCWAT_VERSION, true );

                $params = apply_filters( 'ywcwat_product_admin_script_parameters', array(
                    'ajax_url' => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
                    'actions' => array(
                        'save_watermark_on_single_product' => 'save_watermark_on_single_product'
                    )
                ) );

                wp_localize_script( 'ywcwat_product_admin_script', 'ywcwat_product_param', $params );
                wp_enqueue_script( 'ywcwat_product_admin_script' );
                wp_enqueue_style( 'ywcwat_admin_style', YWCWAT_ASSETS_URL . 'css/ywcwat_admin.css', array(), YWCWAT_VERSION );

            }
        }

        /** return attachment ids
         * @author YITHEMES
         * @since 1.0.0
         * @return mixed|string|void
         */
        public function get_ids_attach()
        {

            $attach_ids = ywcwat_get_all_product_attach();

            $ids = array();

            foreach ( $attach_ids as $attach_id )
                $ids[] = $attach_id->ID;

            return json_encode( $ids );

        }

        /**
         * @author Salvatore Strano
         * @since 1.1.0
         */
        public function apply_all_watermark( $attach_id, $watermarks = array() )
        {

            $fullsizepath = get_attached_file( $attach_id );
            $backupfile = ywcwat_backup_file_name( $fullsizepath );
            $size_types = $this->get_woocommerce_size();
            $all_watermarks = empty( $watermarks ) ?  get_option( 'ywcwat_watermark_select' ) : $watermarks ;


            $results = array();

            //if file exist

            if( file_exists( $fullsizepath ) ) {

                if( !file_exists( $backupfile ) ) {
                    ywcwat_backup_file( $fullsizepath );
                }
                foreach ( $size_types as $size ) {

                    $watermarks_size = array_filter( $all_watermarks, function ( $v ) USE ( $size ) {
                        return $v['ywcwat_watermark_sizes'] == $size;
                    } );


                    if( $watermarks_size ) {
                        foreach ( $watermarks_size as $watermark_size ) {

                            $watermark_created = $this->create_watermark( $backupfile, $fullsizepath, $attach_id, $watermark_size );
                            $results[] = array( $watermark_created, $size );
                        }
                    }
                }
            }

            return $results;
        }

        /** call ajax, apply watermark to single attach
         * @author YITHEMES
         * @since 1.0.0
         */
        public function yith_apply_all_watermark()
        {

            if( isset( $_REQUEST['ywcwat_attach_id'] ) ) {

                $attach_id = $_REQUEST['ywcwat_attach_id'];

                $results = $this->apply_all_watermark( $attach_id );
                wp_send_json( $results );
            }
        }

        /**
         * @author Salvatore Strano
         * @since 1.1.0
         * @param string $backup_path
         * @param string $path
         * @param int $attachment_id
         * @param array $watermark
         * @return string
         *
         */
        public function create_watermark( $backup_path, $path, $attachment_id, $watermark )
        {

            $size_name = isset( $watermark['ywcwat_watermark_sizes'] ) ? $watermark['ywcwat_watermark_sizes'] : false;
            $result = 'size_name_empty';
            if( $size_name ) {
                list( $error_code, $thumbnail_path ) = $this->create_image_resized( $backup_path, $path, $attachment_id, $size_name );

                if( $error_code === 0 ) {

                    $result = $this->save_image_with_watermark( $thumbnail_path, $attachment_id, $watermark );
                }
                else {
                    $result = $error_code;
                }
            }
            return $result;
        }

        /** resize the original image and call save image with watermark
         * @author Salvatore Strano
         * @since 1.1.0
         * @param string $path
         * @param string $size_name
         * @param int $attach_id
         * @return array
         */
        public function create_image_resized( $backup_path, $path, $attach_id, $size_name )
        {
            $new_path = '';
            $error_code = 0;
            if( !empty( $path ) ) {

                if( $size_name == 'full' ) {

                    copy( $backup_path, $path );
                    $new_path = $path;

                }
                else {

                    $img = wp_get_image_editor( $backup_path );

                    if( !is_wp_error( $img ) ) {
                        $size = wc_get_image_size( $size_name );

                        $crop = isset( $size['crop'] ) && $size['crop'] == 1;
                        $img->resize( $size['width'], $size['height'], $crop );

                        $info = pathinfo( $path );

                        $dir = $info['dirname'];
                        $ext = $info['extension'];
                        $suffix = $img->get_suffix();
                        $name = wp_basename( $path, ".$ext" );
                        $dest_file = trailingslashit( $dir ) . "{$name}-{$suffix}.{$ext}";


                        $saved = $img->save( $dest_file );

                        if( is_wp_error( $saved ) ) {
                            $error_code = 'image_resize';
                        }
                        else {
                            $new_path = $saved['path'];
                        }
                    }
                    else {
                        $error_code = 'load_editor';
                    }
                }
            }
            else {

                $error_code = 'empty_path';
            }

            return array( $error_code, $new_path );

        }

        /**
         * @author Salvatore Strano
         * @since 1.1.0
         * @param int $message_id
         * @return mixed
         */
        public function get_messages( $message_id = -1 )
        {

            /*
             * Error codes
             * 0=> ok
             */
            $messages = array(
                'watermark_created' => __( 'Watermark Created', 'yith-woocommerce-watermark' ),
                'empty_path' => __( 'Empty Path', 'yith-woocommerce-watermark' ),
                'image_resize' => __( 'Error when saving resize image', 'yith-woocommerce-watermark' ),
                'load_editor' => __( 'Can\'t load the image editor', 'yith-woocommerce-watermark' ),
                'error_on_create' => __( 'Error when creating watermark', 'yith-woocommerce-watermark' ),
                'size_name_empty' => __( 'Image size doesn\'t exist', 'yith-woocommerce-watermark' )
            );

            return ( $message_id == -1 ) ? $messages : ( isset( $messages[$message_id] ) ? $messages[$message_id] : false );
        }

        /**restore all original image
         * @author YITHEMES
         * @since 1.0.0
         *
         */
        public function ywcwat_remove_watermark()
        {

            if( isset( $_REQUEST['ywcwat_remove_watermark'] ) ) {

                $count = array( 'success' => 0, 'error' => 0 );

                $wp_upload_dir = wp_upload_dir();
                $uploads_dir = $wp_upload_dir['basedir'];
                $backup_dir = $wp_upload_dir['basedir'] . '/' . YWCWAT_PRIVATE_DIR;

                $prefix = YWCWAT_BACKUP_FILE;

                foreach ( scandir( $backup_dir ) as $yfolder ) {
                    if( !( is_dir( "$backup_dir/$yfolder" ) && !in_array( $yfolder, array( '.', '..' ) ) ) ) {
                        continue;
                    }

                    $yfolder = basename( $yfolder );
                    foreach ( scandir( "$backup_dir/$yfolder" ) as $mfolder ) {
                        if( !( is_dir( "$backup_dir/$yfolder/$mfolder" ) && !in_array( $mfolder, array( '.', '..' ) ) ) ) {
                            continue;
                        }

                        $mfolder = basename( $mfolder );
                        $images = (array)glob( "$backup_dir/$yfolder/$mfolder/*.{jpg,png,gif}", GLOB_BRACE );
                        foreach ( $images as $image ) {

                            // $filename = str_replace( $prefix, '', $image );
                            $filename = basename( $image );
                            $dest_dir = "$uploads_dir/$yfolder/$mfolder/$filename";

                            if( copy( $image, $dest_dir ) ) {
                                $count['success']++;
                            }
                            else {
                                $count['error']++;
                            }
                        }
                    }
                }

                wp_send_json( array( 'success' => $count['success'], 'error' => $count['error'] ) );

            }
        }

        /** create new image from different type (by path)
         * @author YITHEMES
         * @since 1.0.0
         * @param $path
         * @param $type
         * @return bool|resource
         */
        protected function createimagefrom( $path, $type )
        {

            $original_image = false;
            switch ( $type ) {

                case 'jpeg' :
                case 'jpg':

                    $original_image = imagecreatefromjpeg( $path );
                    break;
                case 'gif':
                    $original_image = imagecreatefromgif( $path );
                    break;
                case 'png':
                    $original_image = imagecreatefrompng( $path );
                    break;
            }


            return $original_image;
        }

        /** generate new image from different type
         * @author YITHEMES
         * @param $original_image
         * @param $path
         * @param $type
         * @param $quality
         * @return bool
         */
        protected function generateimagefrom( $original_image, $path, $type, $quality )
        {
            $result = false;
            switch ( $type ) {

                case 'jpeg':
                case 'jpg' :
                    $result = imagejpeg( $original_image, $path, $quality );
                    break;
                case 'gif':
                    $result = imagegif( $original_image, $path );
                    break;
                case 'png':
                    /* conversion quality from jpeg (0-100)  to png(0-9)
                     *
                     */
                    $new_quality = ( $quality-100 ) / 11.111111;
                    $new_quality = round( abs( $new_quality ) );
                    $result = imagepng( $original_image, $path, $new_quality );
                    break;
            }

            return $result;
        }


        /** save image+watermark
         * overridden
         * @author YITHEMES
         * @param $filepath
         * @return string
         */
        public function save_image_with_watermark( $thumbnail_path, $attachment_id, $watermark )
        {

            $mime_type = pathinfo( $thumbnail_path, PATHINFO_EXTENSION );
            $original_image = $this->createimagefrom( $thumbnail_path, $mime_type );
            $original_image = $this->get_truecolor_image( $original_image );

            $action = ( empty( $watermark['ywcwat_watermark_type'] ) || $watermark['ywcwat_watermark_type'] == 'type_img' ) ? 'image' : 'text';
            $watermark_category = isset( $watermark['ywcwat_watermark_category'] ) ? $watermark['ywcwat_watermark_category'] : array();


            if( !empty( $watermark_category ) && function_exists( 'ywcwat_get_product_id_by_attach' ) ) {

                $products = ywcwat_get_product_id_by_attach( $attachment_id );

                $watermark_category = !is_array( $watermark_category ) ?  explode( ',', $watermark_category ) : $watermark_category;
                foreach ( $products as $product ) {

                    $categories = wp_get_post_terms( $product->ID, 'product_cat', array( "fields" => "ids" ) );

                    if( count( array_intersect( $watermark_category, $categories ) )>0 ) {
                        do_action( 'ywcwat_build_watermark_' . $action, $original_image, $watermark );
                    }
                }
            }
            else {

                do_action( 'ywcwat_build_watermark_' . $action, $original_image, $watermark );

            }
           
            
            imagesavealpha( $original_image, true );

            $quality_img = get_option( 'ywcwat_quality_jpg', 100 );
            $result = $this->generateimagefrom( $original_image, $thumbnail_path, $mime_type, $quality_img );
            imagedestroy( $original_image );
            if( $result ) {

                return 'watermark_created';
            }
            else {
                return 'error_on_create';
            }

        }

        /**@author Salvatore Strano
         * @since 1.1.0
         * @param resource $image_content
         * @return resource
         */
        public function get_truecolor_image( $image_content )
        {

            imagealphablending( $image_content, true );
            imagesavealpha( $image_content, true );
            $image_width = imagesx( $image_content );
            $image_height = imagesy( $image_content );
            $truecolor = imagecreatetruecolor( $image_width, $image_height );
            $transparent = imagecolorallocatealpha( $truecolor, 0, 0, 0, 127 );
            imagefill( $truecolor, 0, 0, $transparent );
            imagecopyresampled( $truecolor, $image_content, 0, 0, 0, 0, $image_width, $image_height, $image_width, $image_height );

            return $truecolor;
        }


        /** print watermark in product image
         * @author YITHEMES
         * @since 1.0.0
         * @param $original_image
         * @param $overlay
         * @param $overlay_path
         * @param $size_name
         * @param $watermark
         */
        public function build_watermark_image( $original_image, $watermark )
        {
            $watermark_id = isset( $watermark['ywcwat_watermark_id'] ) ? $watermark['ywcwat_watermark_id'] : false;
            $watermark_path = get_attached_file( $watermark_id );
            if( $watermark_id ) {
                $watermark_type = pathinfo( $watermark_path, PATHINFO_EXTENSION );
                if( $watermark_type == 'jpg' ) {
                    $watermark_type = 'jpeg';
                }

                $image_width = imagesx( $original_image );
                $image_height = imagesy( $original_image );

                $create_function_watermark = 'imagecreatefrom' . $watermark_type;
                $watermark_content = $create_function_watermark( $watermark_path );
                $watermark_width = imagesx( $watermark_content );
                $watermark_height = imagesy( $watermark_content );


                if( $watermark_width > $image_width ){
	                $coeff_ratio = $image_width / $watermark_width;


	                $watermark_width = intval( round( $watermark_width * $coeff_ratio ) );
	                $watermark_height = intval( round( $watermark_height * $coeff_ratio ) );

	                $wat_info = array();

	                $wat_info[] = imagesx( $watermark_content );
	                $wat_info[] = imagesy( $watermark_content );

	                $watermark_content = $this->resizeImage( $watermark_content, $watermark_width, $watermark_height, $wat_info );
                }


                list( $watermark_start_x, $watermark_start_y ) = $this->compute_watermark_position( $image_width, $image_height, $watermark_width, $watermark_height, $watermark );

                imagesavealpha( $watermark_content, true );
                imagealphablending( $watermark_content, true );

                $repeat = ( isset( $watermark['ywcwat_watermark_repeat'] ) && ( $watermark['ywcwat_watermark_type'] !== 'type_text' ) );

                if( !$repeat ) {
                    imagecopyresampled( $original_image, $watermark_content, $watermark_start_x, $watermark_start_y, 0, 0, $watermark_width, $watermark_height, $watermark_width, $watermark_height );
                }else{

                    do_action( 'ywcwat_build_watermark_repeat_image', $original_image, $watermark_content );
                }
            }
        }

	    /**
	     * @author YITHEMES
	     * @since 1.0.0
	     * @param $im
	     * @param $new_width
	     * @param $new_height
	     * @param $img_info
	     * @return resource
	     */
	    private function resizeImage( $im, $new_width, $new_height, $img_info )
	    {
		    $newImg = imagecreatetruecolor( $new_width, $new_height );
		    imagealphablending( $newImg, false );
		    imagesavealpha( $newImg, true );
		    $transparent = imagecolorallocatealpha( $newImg, 255, 255, 255, 127 );
		    imagefilledrectangle( $newImg, 0, 0, $new_width, $new_height, $transparent );

		    imagecopyresampled( $newImg, $im, 0, 0, 0, 0, $new_width, $new_height, $img_info[0], $img_info[1] );

		    return $newImg;
	    }


	    /**
         * compute watermark position
         * @author Salvatore Strano
         * @since 1.1.0
         *
         * @param float $image_width
         * @param float $image_height
         * @param float $watermark_width
         * @param float $watermark_height
         * @param array $watermark
         * @return array
         */
        public function compute_watermark_position( $image_width, $image_height, $watermark_width, $watermark_height, $watermark )
        {

            /*position button right*/
            $watermark_start_x = $image_width-$watermark_width-20;
            $watermark_start_y = $image_height-$watermark_height-20;

            $watermark_position = apply_filters( 'ywcwat_watermark_position', array( $watermark_start_x, $watermark_start_y ), $image_width, $image_height, $watermark_width, $watermark_height, $watermark );

            return $watermark_position;

        }

        /**return the current enable size
         * @author YITHEMES
         * @since 1.0.0
         * @return mixed|void
         */
        public function get_woocommerce_size()
        {

            return array( 'shop_single' );

        }

        /**return all watermark id
         * @author YITHEMES
         * @since 1.0.0
         * @return array
         */
        public function get_watermark_ids()
        {

            $watermark = get_option( 'ywcwat_watermark_select' );

            $ids = array();

            if( $watermark ) {
                foreach ( $watermark as $value ) {

                    $ids [] = $value['ywcwat_watermark_id'];
                }
            }
            return $ids;
        }

        /**when change featured image in edit product, apply watermark
         * @author YITHEMES
         * @since 1.0.0
         */
        public function save_watermark_on_single_product()
        {
            $product_id = isset( $_REQUEST['product_id'] ) ? $_REQUEST['product_id'] : false;

            $product = wc_get_product( $product_id );
            $attach_ids = array();
            $is_custom_enabled = yit_get_prop( $product, '_enable_watermark' ) == 'yes';

            $custom_watermark = yit_get_prop( $product, '_ywcwat_product_watermark', true );
            $watermarks = array();

            if( $is_custom_enabled && !empty( $custom_watermark ) ){
                $watermarks = $custom_watermark;
            }


            if( $product ) {

                $attach_ids[] = get_post_thumbnail_id( $product_id );

                if( $product->is_type( 'variable' ) ) {
                    $child_ids = $product->get_children();

                    foreach ( $child_ids as $child_id ) {
                        $attach_ids[] = get_post_thumbnail_id( $child_id );
                    }
                }

                $attach_ids = apply_filters( 'ywcwat_product_attach_ids', $attach_ids, $product );
                $results = array();

                foreach ( $attach_ids as $attach_id ) {
                    $results[] = $this->apply_all_watermark( $attach_id, $watermarks );
                }


            }
        }

        public function save_watermark_on_attachment_image( $metadata, $attachment_id ){

	        if( isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] != 0 ) {

		        $post_id = $_REQUEST['post_id'];

		        if( get_post_type( $post_id ) == 'product' ){

		            $product = wc_get_product( $post_id );
			        $is_custom_enabled = yit_get_prop( $product, '_enable_watermark' ) == 'yes';

			        $custom_watermark = yit_get_prop( $product, '_ywcwat_product_watermark', true );
			        $watermarks = array();

			        if( $is_custom_enabled && !empty( $custom_watermark ) ){
				        $watermarks = $custom_watermark;
			        }

		            $this->apply_all_watermark( $attachment_id, $watermarks );
                }
	        }

            return $metadata;
        }

        /**
         * @author YITHEMES
         * @since 1.0.7
         */
        public function show_message_to_user()
        {

            global $current_user;

            if( isset( $_GET['page'] ) && 'yith_ywcwat_panel' === $_GET['page'] ) {

                $user_id = $current_user->ID;

                $show_message = get_user_meta( $user_id, '_ywcwat_showmessage', true );
                $args = array(
                    'page' => 'yith_ywcwat_panel',
                    'show_notice' => 'no'
                );

                if( isset( $_GET['tab'] ) && 'watermark-list' === $_GET['tab'] ) {
                    $args['tab'] = 'watermark-list';
                }

                $url = esc_url( add_query_arg( $args, admin_url( 'admin.php' ) ) );

                if( $show_message === '' ) {

                    $upload_dir = wp_upload_dir();
                    $upload_dir = $upload_dir['basedir'];
                    $message = sprintf( '%s <strong>%s</strong>', __( 'From version 1.0.7 all your product backed up images are available at', 'yith-woocommerce-watermark' ), $upload_dir . '/yith_watermark_backup' );
                    ?>
                    <div class="notice notice-info" style="padding-right: 38px;position: relative;">
                        <p><?php echo $message; ?></p>
                        <a class="notice-dismiss" href="<?php echo $url; ?>" style="text-decoration: none;"></a>

                    </div>
                    <?php
                }

                if( isset( $_GET['bakup_success'] ) && 'yes' == $_GET['bakup_success'] ) {
                    ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php _e( 'Backup completed!', 'yith-woocommerce-watermark' ); ?></p>
                    </div>
                    <?php
                }
            }
        }

        /**
         * @author YITHEMES
         * @since 1.0.7
         */
        public function hide_message_for_user()
        {

            global $current_user;

            $user_id = $current_user->ID;
            if( isset( $_GET['show_notice'] ) ) {

                update_user_meta( $user_id, '_ywcwat_showmessage', 'no' );
            }

        }

        /**
         * @author YITHEMES
         * @since 1.0.9
         */
        public function show_backup_btn()
        {

            wc_get_template( 'admin/custom-button.php', array(), '', YWCWAT_TEMPLATE_PATH );
        }

        /**
         * @author Salvatore Strano
         * @since 1.1.0
         *
         */
        public function add_product_meta_boxes()
        {
            add_meta_box( 'yith-ywcwat-metabox', __( 'Apply Watermark', 'yith-woocommerce-watermark' ), array( $this, 'show_watermark_meta_box' ), 'product', 'side', 'core' );

        }

        /**
         * @author Salvatore Strano
         * @since 1.1.0
         */
        public function show_watermark_meta_box()
        {
            wc_get_template( 'admin/apply-single-watermark.php', array(), YWCWAT_TEMPLATE_PATH, YWCWAT_TEMPLATE_PATH );
        }
    }
}