<?php

class Logs_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();

        $this->load->model('items_model');
    }

    public function get_logs($start, $length)
    {
        $logs_result = [];

        $query = $this->db->select('logs.*, fields.title AS field_title, items.title AS item_title')
            ->from('logs')
            ->join('fields', 'fields.id = logs.table_id AND logs.table = "fields"', 'left')
            ->join('items', 'items.id = logs.table_id AND logs.table = "items"', 'left')
            ->order_by('created_at', 'desc')
            ->limit($length, $start)
            ->get();

        $logs = $query->result_object();


        foreach ($logs as $key => $log) {
            $log->created_at = $this->formatTime($log->created_at);

            $log->user = $this->User_model->get_user_by_id($log->user_id);

            switch ($log->table) {
                case 'fields':
                    $log->title = $log->field_title;
                    break;
                case 'items':
                    $log->title = $log->item_title;
                    break;
                case 'items_meta':

                    $meta = $this->Items_model->get_meta_by_id($log->table_id);

                    if (!empty($meta)) {

                        $item = $this->Items_model->find_by_id($meta->items_id);

                        $log->title = $item->title;
                    } else {

                        $log->title = 'N/A';
                    }
                    break;
                default:
                    $log->title = 'HỆ THỐNG';
                    break;
            }

            $logs_result[] = $log;
        }

        return $logs_result;
    }

    public function get_log_by_id($id)
    {
        return $this->db->get_where('logs', ['id' => $id])->row_object();
    }

    public function get_logs_by_item($item_id, $page = null, $limit = null, $start_date = null, $end_date = null)
    {
        // Lấy logs của items
        $this->db->select('logs.*, CONCAT(UCASE(LEFT(RecursiveItems.title, 1)), SUBSTRING(RecursiveItems.title, 2)) AS item_title');

        $this->db->from('(SELECT * FROM items WHERE id = ?
                 UNION ALL
                 SELECT items.* FROM items 
                 JOIN (SELECT id FROM items WHERE parent_id = ?) AS ItemHierarchy ON items.parent_id = ItemHierarchy.id) AS RecursiveItems', null, false);

        $this->db->join('logs', 'logs.table_id = RecursiveItems.id AND logs.table = "items"');

        $this->db->join('users', 'logs.user_id = users.id');

        if (!empty($start_date)) {
            $this->db->where('logs.created_at >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('logs.created_at <=', $end_date);
        }

        $sub_query_items = $this->db->get_compiled_select();

        $this->db->reset_query();

        // Lấy logs của fields
        $this->db->select('logs.*, CONCAT(UCASE(LEFT(RecursiveItems.title, 1)), SUBSTRING(RecursiveItems.title, 2)) AS item_title');
        $this->db->from('(SELECT * FROM items WHERE id = ?
                 UNION ALL 
                 SELECT items.* FROM items 
                 JOIN (SELECT id FROM items WHERE parent_id = ?) AS ItemHierarchy ON items.parent_id = ItemHierarchy.id) AS RecursiveItems', null, false);
        $this->db->join('fields', 'fields.items_id = RecursiveItems.id');
        $this->db->join('logs', 'logs.table_id = fields.id AND logs.table = "fields"');
        $this->db->join('users', 'logs.user_id = users.id');

        if (!empty($start_date)) {
            $this->db->where('logs.created_at >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('logs.created_at <=', $end_date);
        }

        $sub_query_fields = $this->db->get_compiled_select();

        $this->db->reset_query();

        // Lấy logs của items_meta
        $this->db->select('logs.*, CONCAT(UCASE(LEFT(RecursiveItems.title, 1)), SUBSTRING(RecursiveItems.title, 2)) AS item_title');
        $this->db->from('(SELECT * FROM items WHERE id = ?
                 UNION ALL 
                 SELECT items.* FROM items 
                 JOIN (SELECT id FROM items WHERE parent_id = ?) AS ItemHierarchy ON items.parent_id = ItemHierarchy.id) AS RecursiveItems', null, false);
        $this->db->join('items_meta', 'items_meta.items_id = RecursiveItems.id');
        $this->db->join('logs', 'logs.table_id = items_meta.id AND logs.table = "items_meta"');

        if (!empty($start_date)) {
            $this->db->where('logs.created_at >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('logs.created_at <=', $end_date);
        }

        $sub_query_items_meta = $this->db->get_compiled_select();

        $final_query = "(SELECT * FROM ($sub_query_items) AS items_union 
                 UNION ALL 
                 SELECT * FROM ($sub_query_fields) AS fields_union 
                 UNION ALL 
                 SELECT * FROM ($sub_query_items_meta) AS items_meta_union)";

        $final_query .= " ORDER BY created_at DESC";

        if (!empty($limit) && !empty($page)) {
            $offset = ($page - 1) * $limit;
            $final_query .= " LIMIT $offset, $limit";
        }

        $query = $this->db->query($final_query, array($item_id, $item_id, $item_id, $item_id, $item_id, $item_id));

        $result = $query->result_object();

        foreach ($result as $key => $log) {

            // // Lọc các logs thuộc group được truy cập
            // switch ($log->table) {
            //     case 'items':
            //         $item = $this->Items_model->find_by_id($log->table_id);

            //         if($item->type !== 6)

            //         break;

            //     case 'fields':
            //         $fields = $this->Fields_model->get_by_id($log->table_id);


            //         break;
            //     default:
            //         # code...
            //         break;
            // }

            $log->created = $this->formatTime($log->created_at);
            $log->user = $this->User_model->get_user_by_id($log->user_id);
        }

        return $result;
    }

    // public function get_all_logs_by_item($item_id)
    // {
    //     $logs_result = [];
    //     $temp_ids = [];

    //     $childs_of_item = $this->items_model->get_all_childs_item($item_id);

    //     foreach ($childs_of_item as $item) {

    //         $query = $this->db
    //             ->select('logs.*, CONCAT(UCASE(LEFT(items.title, 1)), SUBSTRING(items.title, 2)) AS item_title, CONCAT(UCASE(LEFT(items.title, 1)), SUBSTRING(items.title, 2)) AS title, users.firstname, users.lastname')
    //             ->from('logs')
    //             ->join('items', 'logs.table_id = items.id')
    //             ->join('users', 'users.id = logs.user_id')
    //             ->where(['table' => 'items', 'table_id' => $item->id])
    //             ->get();
    //         $logs = $query->result_object();

    //         $logs_result = array_merge($logs_result, $logs);

    //         $query = $this->db->get_where('fields', ['items_id' => $item->parent_id]);

    //         if ($query->num_rows() > 0) {

    //             $fields = $query->result_object();
    //             foreach ($fields as $field) {

    //                 $query = $this->db
    //                     ->select('logs.*, CONCAT(UCASE(LEFT(items.title, 1)), SUBSTRING(items.title, 2)) AS item_title, CONCAT(UCASE(LEFT(fields.title, 1)), SUBSTRING(fields.title, 2)) AS title, users.firstname, users.lastname')
    //                     ->from('logs')
    //                     ->join('fields', 'logs.table_id = fields.id')
    //                     ->join('items', 'items.id = fields.items_id')
    //                     ->join('users', 'users.id = logs.user_id')
    //                     ->where(['table' => 'fields', 'table_id' => $field->id])
    //                     ->get();
    //                 $logs = $query->result_object();

    //                 foreach ($logs as $log) {

    //                     if (!in_array($log->id, $temp_ids)) {
    //                         $temp_ids[] = $log->id; // Thêm ID vào mảng tạm thời
    //                         $logs_result[] = $log; // Thêm bản ghi vào mảng kết quả
    //                     }
    //                 }
    //             }
    //         }

    //         $query = $this->db->get_where('items_meta', ['items_id' => $item->id]);
    //         if ($query->num_rows() > 0) {

    //             $items_meta = $query->result_object();

    //             foreach ($items_meta as $item_meta) {

    //                 $query = $this->db
    //                     ->select('logs.*, CONCAT(UCASE(LEFT(items.title, 1)), SUBSTRING(items.title, 2)) AS item_title, CONCAT(UCASE(LEFT(fields.title, 1)), SUBSTRING(fields.title, 2)) AS title, users.firstname, users.lastname')
    //                     ->from('logs')
    //                     ->join('items_meta', 'logs.table_id = items_meta.id')
    //                     ->join('fields', 'fields.key = items_meta.key')
    //                     ->join('items', 'items.id = items_meta.items_id')
    //                     ->join('users', 'users.id = logs.user_id')
    //                     ->where(['table' => 'items_meta', 'table_id' => $item_meta->id])
    //                     ->get();
    //                 $logs = $query->result_object();

    //                 foreach ($logs as $log) {
    //                     if (!in_array($log->id, $temp_ids)) {
    //                         $temp_ids[] = $log->id; // Thêm ID vào mảng tạm thời
    //                         $logs_result[] = $log; // Thêm bản ghi vào mảng kết quả
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     usort($logs_result, function ($a, $b) {
    //         $timeA = strtotime($a->created_at);
    //         $timeB = strtotime($b->created_at);
    //         return $timeB - $timeA;
    //     });

    //     return $logs_result;
    // }


    public function create($data)
    {
        return $this->db->insert('logs', $data);
    }

    public function update($id, $data)
    {
        return $this->db->update('logs', $data, ['id' => $id]);
    }

    function formatTime($time)
    {
        $current_time = time();
        $created_at_time = strtotime($time);
        $interval = abs($current_time - $created_at_time);

        $days = floor($interval / (24 * 3600));
        if ($days > 0) {
            return $days . ' ngày';
        }

        $hours = floor($interval / 3600);
        if ($hours > 0) {
            return $hours . ' giờ';
        }

        $minutes = floor($interval / 60);
        if ($minutes > 0) {
            return $minutes . ' phút';
        }

        $seconds = $interval;
        if ($seconds > 0) {
            return $seconds . ' giây';
        }
    }

    public function restore_data($log)
    {
        $restore_data = $this->db->get_where($log->table, ['id' => $log->table_id])->row_object();

        $result = false;

        switch ($log->table) {
                // Log của bảng items
            case 'items':
                $result = $this->items_model->restore($log->table_id);

                $log = [
                    'type' => 'RESTORE',
                    'table' => 'items',
                    'table_id' => $log->id,
                    'message' => 'success',
                    'value_old' => null,
                    'value_new' => $restore_data->title,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'ip' => $this->session->userdata('ip'),
                ];

                break;

                //Log của item_meta
            case 'items_meta':
                $result = $this->items_model->restore($log->table_id);
                break;

                //Logs của bảng fields
            case 'fields':

                $result = $this->Fields_model->restore($log->table_id);
                
                $log = [
                    'type' => 'RESTORE',
                    'table' => 'items',
                    'table_id' => $log->id,
                    'message' => 'success',
                    'value_old' => null,
                    'value_new' => $restore_data->value,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'ip' => $this->session->userdata('ip'),
                ];

                break;

            default:
                return false;
        }

        if ($result) {
            $this->db->delete('logs', ['id' => $log->id]); 

            $this->logs_model->create($log);
        }
        
        return $result;
    }
}
