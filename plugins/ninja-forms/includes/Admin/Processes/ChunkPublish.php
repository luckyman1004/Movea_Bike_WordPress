<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_Batch_Process
 */
class NF_Admin_Processes_ChunkPublish extends NF_Abstracts_BatchProcess
{
//	header( 'Content-Type: application/json' );
    private $data;
    private $form_id;
    private $response = array(
    	'last_request' => 'failure',
        'batch_complete' => false,
    );
    protected $_data = array();
    protected $_errors = array();
    protected $_debug = array();



    /**
     * Constructor
     */
    public function __construct( $data = array() )
    {
        //Bail if we aren't in the admin.
        if ( ! is_admin() )
            return false;
        // Record our data if we have any.
        $this->data = $data[ 'data' ];
        $this->form_id = $this->data[ 'form_id' ];
        // Run process.
        $this->process();
    }


    /**
     * Function to loop over the batch.
     * 
     * @return JSON
     *  Str last_response = success/failure
     *  Bool batch_complete = true/false
     *  Int requesting = x
     */
    public function process()
    {
        // If we were told this is a new publish...
        if ( $this->data[ 'new_publish' ] === 'true' ) {
            // Delete our option to reset the process.
            $this->remove_option();
        }
        // Fetch our option to see what step we're on.
        $batch = get_option( 'nf_chunk_publish_' . $this->form_id );
        // If we don't have an option to see what step we're on...
        if ( ! $batch ) {
            // Run startup.
            $this->startup();
            // Fetch our option now that it's created.
            $batch = get_option( 'nf_chunk_publish_' . $this->form_id );
        }
        $batch = explode( ',', $batch );
        // If we already have a chunk for this step...
        if ( get_option( 'nf_form_' . $this->form_id . '_publishing_' . $batch[ 0 ] ) ) {
            // Update it.
            update_option( 'nf_form_' . $this->form_id . '_publishing_' . $batch[ 0 ], stripslashes( $this->data[ 'chunk' ] ) );
        } // Otherwise... (No chunk was found.)
        else {
            // Add it.
            add_option( 'nf_form_' . $this->form_id . '_publishing_' . $batch[ 0 ], stripslashes( $this->data[ 'chunk' ] ), '', 'no' );
        }
        // Increment our step.
        $batch[ 0 ]++;
        // If this was our last step...
        if ( $batch[ 0 ] == $batch[ 1 ] ) {
            // Run cleanup.
            $this->cleanup();
        } // Otherwise... (We have more steps.)
        else {
            // Update our step option.
            update_option( 'nf_chunk_publish_' . $this->form_id, implode( ',', $batch ) );
            // Request our next chunk.
            $this->response[ 'requesting' ] = $batch[ 0 ];
        }
        $this->response[ 'last_request' ] = 'success';
        echo wp_json_encode( $this->response );
        wp_die();
    }


    /**
     * Function to run any setup steps necessary to begin processing.
     */
    public function startup()
    {
        $value = '0,' . $this->data[ 'chunk_total' ];
        // Write our option to manage the process.
        add_option( 'nf_chunk_publish_' . $this->form_id, $value, '', 'no' );
        // Process the first item.
        $this->process();
    }


    /**
     * Function to cleanup any lingering temporary elements of a batch process after completion.
     */
    public function cleanup()
    {
        
        // Get all of the chunks.
        
        
        $build = '';
        $batch = get_option( 'nf_chunk_publish_' . $this->form_id );
        $batch = explode( ',', $batch );
        // Add all of our chunks into a string.
        for ( $i = 0; $i < $batch[ 1 ]; $i++ ) {
            $build .= get_option( 'nf_form_' . $this->form_id . '_publishing_' . $i );
        }

        $form_data = json_decode( $build, ARRAY_A );
        // Start copied code.
        if( is_string( $form_data[ 'id' ] ) ) {
            $tmp_id = $form_data[ 'id' ];
            $form = Ninja_Forms()->form()->get();
            $form->save();
            $form_data[ 'id' ] = $form->get_id();
            $this->_data[ 'new_ids' ][ 'forms' ][ $tmp_id ] = $form_data[ 'id' ];
        } else {
            $form = Ninja_Forms()->form($form_data['id'])->get();
        }

        unset( $form_data[ 'settings' ][ '_seq_num' ] );

        $form->update_settings( $form_data[ 'settings' ] )->save();

        if( isset( $form_data[ 'fields' ] ) ) {
            $db_fields_controller = new NF_Database_FieldsController( $form_data[ 'id' ], $form_data[ 'fields' ] );
            $db_fields_controller->run();
            $form_data[ 'fields' ] = $db_fields_controller->get_updated_fields_data();
            $this->_data['new_ids']['fields'] = $db_fields_controller->get_new_field_ids();
        }

        if( isset( $form_data[ 'deleted_fields' ] ) ){

            foreach( $form_data[ 'deleted_fields' ] as  $deleted_field_id ){

                $field = Ninja_Forms()->form( $form_data[ 'id' ])->get_field( $deleted_field_id );
                $field->delete();
            }
        }

        if( isset( $form_data[ 'actions' ] ) ) {

            /*
             * Loop Actions and fire Save() hooks.
             */
            foreach ($form_data['actions'] as &$action_data) {

                $id = $action_data['id'];

                $action = Ninja_Forms()->form( $form_data[ 'id' ] )->get_action( $id );

                $action->update_settings($action_data['settings'])->save();

                $action_type = $action->get_setting( 'type' );

                if( isset( Ninja_Forms()->actions[ $action_type ] ) ) {
                    $action_class = Ninja_Forms()->actions[ $action_type ];

                    $action_settings = $action_class->save( $action_data['settings'] );
                    if( $action_settings ){
                        $action_data['settings'] = $action_settings;
                        $action->update_settings( $action_settings )->save();
                    }
                }

                if ($action->get_tmp_id()) {

                    $tmp_id = $action->get_tmp_id();
                    $this->_data['new_ids']['actions'][$tmp_id] = $action->get_id();
                    $action_data[ 'id' ] = $action->get_id();
                }

                $this->_data[ 'actions' ][ $action->get_id() ] = $action->get_settings();
            }
        }

        /*
         * Loop Actions and fire Publish() hooks.
         */
        foreach ($form_data['actions'] as &$action_data) {

            $action = Ninja_Forms()->form( $form_data[ 'id' ] )->get_action( $action_data['id'] );

            $action_type = $action->get_setting( 'type' );

            if( isset( Ninja_Forms()->actions[ $action_type ] ) ) {
                $action_class = Ninja_Forms()->actions[ $action_type ];

                if( $action->get_setting( 'active' ) && method_exists( $action_class, 'publish' ) ) {
                    $data = $action_class->publish( $this->_data );
                    if ($data) {
                        $this->_data = $data;
                    }
                }
            }
        }

        if( isset( $form_data[ 'deleted_actions' ] ) ){

            foreach( $form_data[ 'deleted_actions' ] as  $deleted_action_id ){

                $action = Ninja_Forms()->form()->get_action( $deleted_action_id );
                $action->delete();
            }
        }

        delete_user_option( get_current_user_id(), 'nf_form_preview_' . $form_data['id'] );
        update_option( 'nf_form_' . $form_data[ 'id' ], $form_data );

        do_action( 'ninja_forms_save_form', $form->get_id() );

        if( isset( $this->_data['debug'] ) ) {
            $this->_debug = array_merge( $this->_debug, $this->_data[ 'debug' ] );
        }

        if( isset( $this->_data['errors'] ) && $this->_data[ 'errors' ] ) {
            $this->_errors = array_merge( $this->_errors, $this->_data[ 'errors' ] );
        }
        
        // Remove our option.
        $this->remove_option();

        $response = array( 'data' => $this->_data, 'errors' => $this->_errors, 'debug' => $this->_debug, 'batch_complete' => true );

        echo wp_json_encode( $response );

        wp_die(); // this is required to terminate immediately and return a proper response
        
        /**************************************************************
         * Start old data.
         **************************************************************
         */
//        // Update the chunked cache option.
//        $build = array();
//        $batch = get_option( 'nf_chunk_publish_' . $this->form_id );
//        $batch = explode( ',', $batch );
//        // Add all of our chunks onto an array.
//        for ( $i = 0; $i < $batch[ 1 ]; $i++ ) {
//            $build[] = 'nf_form_' . $this->form_id . '_' . $i;
//        }
//        // Convert them to a string.
//        $build = implode( ',', $build );
//        // If we already have a chunked option...
//        if ( get_option( 'nf_form_' . $this->form_id . '_chunks' ) ) {
//            // Update it.
//            update_option( 'nf_form_' . $this->form_id . '_chunks', $build );
//        } // Otherwise... (If we don't already have one.)
//        else {
//            // Create it.
//            add_option( 'nf_form_' . $this->form_id . '_chunks', $build, '', 'no' );
//        }
//        // Remove our option to manage the process.
//        delete_option( 'nf_chunk_publish_' . $this->form_id );
//        $this->response[ 'batch_complete' ] = true;
        /**************************************************************
         * End old data.
         **************************************************************
         */
    }
    
    /*
     * Function to remove our management option and remove any temporary chunk data.
     */
    public function remove_option() {
        // Remove our option to manage the process.
        delete_option( 'nf_chunk_publish_' . $this->form_id );
        // If our form_id was a temp id...
        if ( ! is_numeric( $this->form_id ) ) {
            // Remove all of our chunk options.
            global $wpdb;
            $sql = "DELETE FROM `" . $wpdb->prefix . "options` WHERE option_name LIKE 'nf_form_" . $this->form_id . "_publishing_%'";
            $wpdb->query( $sql );
        }
        $this->data[ 'new_publish' ] = 'false';
    }

}