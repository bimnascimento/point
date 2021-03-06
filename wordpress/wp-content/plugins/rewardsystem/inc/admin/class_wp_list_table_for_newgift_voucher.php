<?php

// Integrate WP List Table for Master Log

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WP_List_Table_for_NewGiftVoucher extends WP_List_Table {

    // Prepare Items
    public function prepare_items() {
        global $wpdb;

        $this->process_bulk_action();
        $columns = $this->get_columns();

        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $newdata = get_option('rs_list_of_gift_vouchers_created') != false ? get_option('rs_list_of_gift_vouchers_created') : array();
        $num_rows = count($newdata);
        $data = $this->table_data();

        if (isset($_REQUEST['s']) && $_REQUEST['s'] != '') {
            $searchvalue = $_REQUEST['s'];
            $keyword = "$searchvalue";

            $data = $this->get_data_of_searched_code($keyword, $newdata);

            usort($data, array(&$this, 'sort_data'));

            $perPage = 10;
            $currentPage = $this->get_pagenum();
            $totalItems = count($data);

            $newdata = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

            $this->_column_headers = array($columns, $hidden, $sortable);

            $this->items = $newdata;
        } else {
            usort($data, array(&$this, 'sort_data'));

            $perPage = 10;
            $currentPage = $this->get_pagenum();
            $totalItems = count($data);

            $this->set_pagination_args(array(
                'total_items' => $num_rows,
                'per_page' => $perPage
            ));

            $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

            $this->_column_headers = array($columns, $hidden, $sortable);

            $this->items = $data;
        }
    }

    public function get_data_of_searched_code($keyword, $subdatas) {
        $i = 1;
        $data = array();
        if (is_array($subdatas) && !empty($subdatas)) {
            foreach ($subdatas as $eachvalues) {
                if (is_array($eachvalues) && !empty($eachvalues)) {
                    foreach ($eachvalues as $value) {                        
                        if (array_key_exists($keyword,$eachvalues)) {
                            if ($value['vouchercode'] != '') {
                                if ($i % 2 != 0) {
                                    $name = 'alternate';
                                } else {
                                    $name = '';
                                }
                                if ($value['memberused'] != '') {
                                    $userinfo = get_userdata($value['memberused']);
                                    $username = $userinfo->user_login;
                                } else {
                                    $username = "Not Yet";
                                }
                                $data[] = array(
                                    'cb' => $value['vouchercode'],
                                    'sno' => $i,
                                    'reward_code' => $value['vouchercode'],
                                    'points_assigned' => $value['points'],
                                    'rewardcode_created_date' => $value['vouchercreated'],
                                    'rewardcode_expired_date' => $value['voucherexpiry'] != '' ? $value['voucherexpiry'] : "Never",
                                    'rewardcode_used_by' => $username,
                                );
                                $i++;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'sno' => __('S.No', 'rewardsystem'),
            'reward_code' => __('Reward Code', 'rewardsystem'),
            'points_assigned' => __('Points Assigned', 'rewardsystem'),
            'rewardcode_created_date' => __('Reward Code Created Date', 'rewardsystem'),
            'rewardcode_expired_date' => __('Reward Code Expired Date', 'rewardsystem'),
            'rewardcode_used_by' => __('Reward Code used by', 'rewardsystem'),
        );

        return $columns;
    }

    public function get_hidden_columns() {
        return array();
    }

    public function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="id[]" value="%s" />', $item['cb']
        );
    }

    public function column_reward_code($item) {


        //Build row actions
        $actions = array(
            'delete' => sprintf('<a href="?page=%s&tab=%s&action=%s&id=%s">Delete</a>', $_REQUEST['page'], $_REQUEST['tab'], 'delete_row', $item['cb']),
        );

        //Return the title contents
        return sprintf('%1$s %3$s',
                /* $1%s */ $item['reward_code'],
                /* $2%s */ $item['cb'],
                /* $3%s */ $this->row_actions($actions)
        );
    }

    public function get_bulk_actions() {
        $columns = array(
            'delete' => __('Delete', 'rewardsystem'),
            'delete_all' => __('Delete All', 'rewardsystem'),
        );

        return $columns;
    }

    public function get_sortable_columns() {
        return array(
            'points_assigned' => array('points_assigned', false),
            'sno' => array('sno', false),
            'rewardcode_created_date' => array('rewardcode_created_date', false),
            'rewardcode_expired_date' => array('rewardcode_expired_date', false),
        );
    }

    public function process_bulk_action() {
        if ('delete_all' === $this->current_action()) {
            delete_option('rs_list_of_gift_vouchers_created');
        } elseif ('delete' === $this->current_action()) {
            $newupdates = array();
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            $checkifexists = get_option('rs_list_of_gift_vouchers_created');
            if (is_array($ids) && !empty($ids)) {
                foreach ($ids as $each_code) {
                    if (is_array($checkifexists) && !empty($checkifexists)) {
                        foreach ($checkifexists as $key => $each_data) {
                            if (isset($each_data[$each_code])) {
                                $newupdates[] = $key;
                            }
                        }
                    }
                }


                if (is_array($newupdates) && !empty($newupdates)) {
                    foreach ($newupdates as $each_datas) {
                        if (is_array($checkifexists) && !empty($checkifexists)) {
                            unset($checkifexists[$each_datas]);
                        }
                    }
                }

                update_option('rs_list_of_gift_vouchers_created', $checkifexists);
            }
        } elseif ('delete_row' === $this->current_action()) {
            $newupdates = array();
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
            $checkifexists = get_option('rs_list_of_gift_vouchers_created');
            if (is_array($checkifexists) && !empty($checkifexists)) {
                foreach ($checkifexists as $key => $each_data) {
                    if (isset($each_data[$ids])) {
                        unset($each_data[$ids]);
                    }
                    $newupdates[] = $each_data;
                }
            }
            $new_values = array_filter($newupdates);

            update_option('rs_list_of_gift_vouchers_created', $new_values);
        }
    }

    private function table_data() {
        global $wpdb;
        $data = array();
        $i = 1;
        $subdatas = get_option('rs_list_of_gift_vouchers_created') != false ? get_option('rs_list_of_gift_vouchers_created') : array();
        if (is_array($subdatas) && !empty($subdatas)) {
            foreach ($subdatas as $eachvalues) {
                if (is_array($eachvalues) && !empty($eachvalues)) {
                    foreach ($eachvalues as $value) {
                        if(isset($value['vouchercode'])){
                        if ($value['vouchercode'] != '') {
                            if ($i % 2 != 0) {
                                $name = 'alternate';
                            } else {
                                $name = '';
                            }
                            if ($value['memberused'] != '') {
                                $userinfo = get_userdata($value['memberused']);
                                $username = $userinfo->user_login;
                            } else {
                                $username = "Not Yet";
                            }
                            $data[] = array(
                                'cb' => $value['vouchercode'],
                                'sno' => $i,
                                'reward_code' => $value['vouchercode'],
                                'points_assigned' => $value['points'],
                                'rewardcode_created_date' => $value['vouchercreated'],
                                'rewardcode_expired_date' => $value['voucherexpiry'] != '' ? $value['voucherexpiry'] : "Never",
                                'rewardcode_used_by' => $username,
                            );
                            $i++;
                        }
                    }
                    }
                }
            }
        }
        return $data;
    }

    public function column_id($item) {
        return $item['sno'];
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'sno':
            case 'reward_code':
            case 'points_assigned':
            case 'rewardcode_created_date':
            case 'rewardcode_expired_date':
            case 'rewardcode_used_by':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    private function sort_data($a, $b) {

        $orderby = 'sno';
        $order = 'asc';

        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }
        $result = strnatcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }

}
