<?php

function base1_export_users() {
    
    $pt = get_post_type();
    if($pt != 'app_user') return;

    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($)
        {
            $('.wp-list-table').before('<form action="#" method="POST" id="export-csv" style="margin-top:20px;"><input type="hidden" id="base1_export_csv" name="base1_export_csv" value="1" /><input class="button button-primary user_export_button" style="margin-top:3px;" type="submit" value="<?php esc_attr_e('Export CSV', 'base1');?>" /><input type="hidden" id="base1-export-from" name="base1_export_from" value="" /><input type="hidden" id="base1-export-to" name="base1_export_to" value="" /></form>');

            $('form#export-csv').on('submit', function(e) {
                $date_from = $('input[name="mishaDateFrom"]').val();
                $date_to = $('input[name="mishaDateTo"]').val();
                $(this).find('#base1-export-from').val($date_from)
                $(this).find('#base1-export-to').val($date_to)
            })
        });
    </script>
    <?php
}
add_action('admin_footer', 'base1_export_users');

function export_csv() {
    if (!empty($_POST['base1_export_csv'])) {

        if (current_user_can('edit_posts')) {
            header("Content-type: application/force-download");
            header('Content-Disposition: inline; filename="app-users-'.date('YmdHis').'.csv"');

            $date_from = isset($_POST['base1_export_from']) ? $_POST['base1_export_from'] : false;
            $date_to = isset($_POST['base1_export_to']) ? $_POST['base1_export_to'] : false;
            $date_query = array(
                'inclusive' => true,
            );
            $tz = 'America/Chicago';
            if($date_from) {
                $date_query['after'] = $date_from . ' 00:00:00';
            }
            if($date_to) {
                $date_query['before'] = $date_to . ' 23:59:59';
            }

            $args = array (
                'post_type' => 'app_user',
                'posts_per_page' => -1,
                'date_query' => array(
                    $date_query
                ),
            );
            $posts = get_posts( $args );
            foreach ( $posts as $item ) {
                $id = $item->ID;
                $first_name = get_field('first_name', $id);
                $last_name  = get_field('last_name', $id);
                $email = get_field('email', $id);
                $country = get_field('country', $id);
                $pledge_id = get_field('pledge', $id) ? get_field('pledge', $id) : false;
                if($pledge_id) {
                    $pledge = get_post($pledge_id);                    
                    $pledge_text = $pledge->post_title;
                } else {
                    $pledge_text = 'empty';
                }

                $post_date = date('Y-m-d h:i:s', strtotime($item->post_date));

                echo '"' . $first_name . '","' . $last_name . '","' . $email . '","' . $country . '","' . $pledge_text . '","' . $post_date . '"' . "\r\n";
            }

            exit();
        }
    }
}
add_action('admin_init', 'export_csv');