<?php
class Items_model extends CI_Model
{

    const GROUP_ID = 5;
    const PROJECT_ID = 6;
    const TASK_ID = 8;
    const SUB_TASK = 28;
    const FOLDER_ID = 7;
    const BOARD_ID = 27;
    const DEPARTMENT_ID = 30;
    const TABLE_ID = 31;
    const TIMETALBE_ID = 32;
    const TABLEITEM_ID = 35;

    public function __construct()
    {
        $this->load->database();
        $this->load->model('Notifications_model');
    }

    public function get_all()
    {
        $this->db->from("items");
        $this->db->where("deleted_at", null);
        $this->db->order_by("id", "asc");
        $query = $this->db->get();
        $result = $query->result_object();
        $result = [];
        return $result;
    }


    public function get_items_by_user($user_id)
    {
        $result = [];
    }

    public function get_departments()
    {
        $query = $this->db->from('items')
            ->where('items.parent_id', $this->config->item('id_bang_phong_ban'))
            ->where('items.type_id', self::TABLEITEM_ID)
            ->where('deleted_at', null)
            ->get();

        return $query->result_object();
    }

    public function get_parent_by_type($item_id, $type_id)
    {
        $item = $this->db->get_where('items', ['id' => $item_id])->row_object();

        if ($item == null) {
            return null;
        }

        $parent_item = $this->db->get_where('items', ['id' => $item->parent_id])->row_object();

        if ($parent_item->type_id != $type_id) {

            $result = $this->get_parent_by_type($parent_item->id, $type_id);

            if ($result != null) {
                return $result;
            } else {
                return null;
            }
        }

        return $parent_item;
    }

    public function get_by_owner($user_id, $type_id = null)
    {
        $this->db->from("items");
        $this->db->where(["deleted_at" => null]);
        if ($type_id) {
            $this->db->where(["type_id" => $type_id]);
        }
        $this->db->order_by("position", "asc");
        $query = $this->db->get();
        $items = $query->result_object();

        $result  = [];

        foreach ($items as $item) {
            $owners_of_item = explode(',', $item->owners);

            if (in_array($user_id, $owners_of_item)) {
                $result[] = $item;
            } else {
                $groups = $this->get_groups($item->id);

                foreach ($groups as $group) {
                    $owners_of_group = explode(',', $group->owners);

                    if (in_array($user_id, $owners_of_group)) {
                        $result[] = $item;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    public function get_folders($user_id)
    {
        $this->db->distinct();

        $this->db->select('folders.*');

        $this->db->from('items as folders');

        $this->db->join('items as child_folders', 'folders.id = child_folders.parent_id AND child_folders.is_archived = 0 AND child_folders.deleted_at is NULL', 'left');

        $this->db->join('items as projects_of_child_folders', 'child_folders.id = projects_of_child_folders.parent_id AND projects_of_child_folders.is_archived = 0 AND projects_of_child_folders.deleted_at is NULL', 'left');

        $this->db->join('items as groups_of_project', 'projects_of_child_folders.id = groups_of_project.parent_id AND groups_of_project.is_archived = 0 AND groups_of_project.deleted_at is NULL', 'left');

        // $this->db->join('item_configs','items.id = item_configs.items_id', 'left');

        $this->db->where('folders.type_id', self::FOLDER_ID);
        $this->db->where('folders.parent_id', 0);

        $this->db->where('folders.deleted_at', null);
        $this->db->where('folders.is_archived', 0);

        $this->db->group_start();
        $this->db->where('folders.user_id', $user_id);
        $this->db->or_where('child_folders.user_id', $user_id);
        $this->db->or_where("FIND_IN_SET('$user_id', projects_of_child_folders.owners) != 0");
        $this->db->or_where("FIND_IN_SET('$user_id', groups_of_project.owners) != 0");
        $this->db->group_end();
        $this->db->order_by('folders.position', 'asc');

        $query = $this->db->get();

        return $query->result_object();
    }

    public function get_child_folder_by_user($parent_id, $user_id)
    {
        $this->db->distinct();
        $this->db->select('b1.*');
        $this->db->from('items as b1');

        $this->db->join('items as b2', 'b1.id = b2.parent_id AND b2.is_archived = 0 AND b2.deleted_at is NULL', 'left');
        $this->db->join('items as b3', 'b2.id = b3.parent_id AND b3.is_archived = 0 AND b3.deleted_at is NULL', 'left');

        $this->db->where('b1.parent_id', $parent_id);
        $this->db->where('b1.deleted_at', null);

        $this->db->where('b1.is_archived', 0);

        $this->db->where('b1.type_id', self::FOLDER_ID);
        $this->db->where('b1.parent_id !=', 0);

        $this->db->group_start();
        $this->db->where('b1.user_id', $user_id);
        $this->db->or_where("FIND_IN_SET('$user_id', b2.owners) != 0");
        $this->db->or_where("FIND_IN_SET('$user_id', b3.owners) != 0");
        $this->db->group_end();

        $query = $this->db->get();

        return $query->result_object();
    }

    public function get_project_by_user($parent_id, $user_id)
    {
        $this->db->distinct();
        $this->db->select('b1.*');

        $this->db->from('items as b1');
        $this->db->join('items as b2', 'b1.id = b2.parent_id AND b2.is_archived = 0 AND b2.deleted_at is NULL');
        $this->db->where('b1.parent_id', $parent_id);
        $this->db->where('b1.deleted_at', null);
        $this->db->where('b1.is_archived', false);
        $this->db->where('b1.display', true);

        $this->db->group_start();
        $this->db->where("FIND_IN_SET('$user_id', b1.owners) != 0");
        $this->db->or_where("FIND_IN_SET('$user_id', b2.owners) != 0");
        $this->db->group_end();

        $this->db->order_by("b1.position", "ASC");

        $query = $this->db->get();

        return $query->result_object();
    }

    public function get_all_include_deleted()
    {
        $this->db->from("items");
        $this->db->order_by("position", "asc");
        $query = $this->db->get();
        $result = $query->result_object();

        foreach ($result as $key => $item) {
            $metas = $this->get_all_meta($item->id);
            if ($metas) {
                foreach ($metas as $meta) {
                    $result[$key]->new_item = $meta->value;
                }
            }
        }

        return $result;
    }
    public function get_first_group_id($id)
    {
        $result = $this->db->get_where('items', ['parent_id' => $id, 'deleted_at' => null]);

        if ($result->num_rows() > 0) {
            return $result->result()[0];
        }

        return false;
    }

    public function get_meta_by_field($item_id, $field_key)
    {
        $query = $this->db->get_where('items_meta', ['items_id' => $item_id, 'key' => $field_key, 'deleted_at' => null]);
        return $query->row_object();
    }

    public function find_by_id($id)
    {
        $query = $this->db->get_where("items", ["id" => $id, 'deleted_at' => null]);
        $result = $query->row_object();
        return $result;
    }

    public function find_by_type($id)
    {
        $query = $this->db->get_where("items", ["type_id" => $id, 'deleted_at' => null]);
        $result = $query->result_object();
        return $result;
    }
    public function find_by_id_and_type($id, $type)
    {
        $query = $this->db->get_where("items", ["id" => $id, "type_id" => $type, 'deleted_at' => null]);
        return $query->row_object();
    }
    public function find_in_set($id_string)
    {
        $this->db->select("*");
        $this->db->from("items");
        $this->db->where("FIND_IN_SET(id, '$id_string')");
        $this->db->order_by("position", "asc");
        $result = $this->db->get()->result_object();
        return $result;
    }
    public function add($data)
    {
        $data_items = [];
        if (array_key_exists('title', $data)) {
            $data_items['title'] = strip_tags($data['title']);
        }

        if (array_key_exists('type_id', $data)) {
            $data_items['type_id'] = $data['type_id'];
        }

        if (array_key_exists('user_id', $data)) {
            $data_items['user_id'] = $data['user_id'];
        }

        if (array_key_exists('parent_id', $data)) {
            $data_items['parent_id'] = $data['parent_id'];
        }

        if (array_key_exists('thumbnail', $data)) {
            $data_items['thumbnail'] = $data['thumbnail'];
        }

        if ($data_items['type_id']) {

            $type_arr = [self::TASK_ID, self::TABLEITEM_ID, self::TIMETALBE_ID];

            if (in_array($data_items['type_id'], $type_arr)) {
                $this->db->select_max('position');
                $this->db->where('parent_id', $data['parent_id']);
                $this->db->from('items');
                $query = $this->db->get();
                if ($query->num_rows() > 0) {
                    $data_items['position'] = $query->row('position') + 1;
                }
            }
        }

        $data_items['owners'] = $this->session->userdata('user_id');

        // create item
        $items = $this->db->insert("items", $data_items);

        // get id item
        $item_id  = $this->db->insert_id();

        if ($items) {

            $item = $this->db->get_where("items", ['id' => $item_id, 'deleted_at' => null])->row_object();

            $log = [
                'type' => 'POST',
                'table' => 'items',
                'table_id' => $item_id,
                'message' => 'success',
                'value_old' => null,
                'value_new' => $item->title,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
            ];

            $this->logs_model->create($log);

            // Get fields of item
            $fields_of_type = $this->db->get_where("fields_of_type", ['type_id' => $item->type_id])->result_object();

            switch ($item->type_id) {
                case self::PROJECT_ID:
                case self::BOARD_ID;
                case self::DEPARTMENT_ID:
                case self::GROUP_ID:
                    //  Create init fields for items
                    foreach ($fields_of_type as $key => $field_of_type) {
                        $data_field['key'] = time() + $key;
                        $data_field['title'] = $field_of_type->title;
                        $data_field['type_html'] = $field_of_type->type_html;
                        $data_field['items_id'] = $item_id;
                        $this->add_field($data_field);
                    }

                    // Create init group
                    $this->db->insert("items", ['title' => 'New Group', 'user_id' => $this->session->userdata('user_id'), 'owners' => $this->session->userdata('user_id'), 'type_id' => 5, 'parent_id' => $item_id]);
                    $group_id =  $this->db->insert_id();
                    $group =  $item = $this->db->get_where("items", ['id' => $group_id, 'deleted_at' => null])->row_object();

                    $fields_of_item = $this->db->get_where("fields", ['items_id' => $item_id, 'deleted_at' => null])->result_object();
                    $this->db->update('items', ['key_code' => time() . $group->id], ['id' => $group->id]);

                    for ($i = 1; $i <= 2; $i++) {
                        // Create init task
                        $this->db->insert("items", ['title' => 'Item ' . $i, 'user_id' => $this->session->userdata('user_id'), 'type_id' => 8, 'parent_id' => $group->id]);
                        $task_id = $this->db->insert_id();
                        $task =  $this->db->get_where("items", ['id' => $task_id, 'deleted_at' => null])->row_object();

                        // Create init meta for task
                        foreach ($fields_of_item as $key => $field) {
                            $value = "";
                            switch ($field->type_html) {
                                case 'status':
                                    $value = "chuabatdau|secondary";
                                    break;
                                default:
                                    break;
                            }
                            $this->add_meta($task->id, $field->key, $value);
                        }
                    }


                    return $item_id;
                case self::TABLE_ID:
                    // Create init fields for items
                    $fields_of_type = $this->db->get_where("fields_of_type", ['type_id' => $item->type_id])->result_object();

                    foreach ($fields_of_type as $key => $field_of_type) {
                        $data_field['key'] = time() + $key;
                        $data_field['title'] = $field_of_type->title;
                        $data_field['type_html'] = $field_of_type->type_html;
                        $data_field['items_id'] = $item_id;
                        $this->add_field($data_field);
                    }

                    $this->db->update('items', ['key_code' => time() .  $item_id], ['id' =>  $item_id]);

                    $fields_of_item = $this->db->get_where("fields", ['items_id' => $item_id, 'deleted_at' => null])->result_object();

                    for ($i = 1; $i <= 2; $i++) {
                        // Create init task
                        $this->db->insert("items", ['title' => 'Item ' . $i, 'user_id' => $this->session->userdata('user_id'), 'owners' => $this->session->userdata('user_id'), 'type_id' => self::TABLEITEM_ID, 'parent_id' => $item_id]);
                        $task_id = $this->db->insert_id();
                        $task =  $this->db->get_where("items", ['id' => $task_id, 'deleted_at' => null])->row_object();

                        // Create init meta for task
                        foreach ($fields_of_item as $key => $field) {
                            $value = "";
                            switch ($field->type_html) {
                                case 'status':
                                    $value = "chuabatdau|secondary";
                                    break;
                                default:
                                    break;
                            }
                            $this->add_meta($task->id, $field->key, $value);
                        }
                    }
                    return $item_id;
                case self::TIMETALBE_ID:
                    //  Create init fields for items
                    foreach ($fields_of_type as $key => $field_of_type) {
                        $data_field['key'] = $field_of_type->key;
                        $data_field['title'] = $field_of_type->title;
                        $data_field['type_html'] = $field_of_type->type_html;
                        $data_field['items_id'] = $item_id;
                        $this->add_field($data_field);
                    }

                    $this->db->insert("items", ['title' => 'Nhóm mới', 'user_id' => $this->session->userdata('user_id'), 'owners' => $this->session->userdata('user_id'), 'type_id' => 5, 'parent_id' => $item_id]);

                    return $item_id;

                case self::TASK_ID:
                case self::SUB_TASK:
                case self::TABLEITEM_ID:

                    // Handle create items meta for task from submit form
                    foreach ($data as $key => $value) {
                        if (!in_array($key, ['title', 'type_id', 'user_id', 'parent_id', 'project_id', 'position'])) {
                            $this->add_meta($item_id, $key, $value);
                        }
                    }

                    if (isset($data['project_id'])) {
                        $fields = $this->db->get_where("fields", ['items_id' => $data['project_id'], 'deleted_at' => null])->result_object();
                        // Create init meta for task
                        foreach ($fields as $key => $field) {

                            $value = "";
                            switch ($field->type_html) {
                                case 'status':
                                    $value = "chuabatdau|secondary";
                                    break;
                                default:
                                    break;
                            }

                            $this->add_meta($item->id, $field->key, $value);
                        }
                    }
                    // Notification
                    if ($item->type_id == self::TASK_ID) {
                        $group = $this->find_by_id($item->parent_id);

                        $receivers = explode(',', $group->owners);

                        $title = $group->title;

                        if (($key = array_search($this->session->userdata('user_id'), $receivers)) !== false) {
                            unset($receivers[$key]);
                        }

                        $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));

                        if ($receivers) {

                            foreach ($receivers as $receiver) {

                                $receiver = trim($receiver);

                                $notification = [
                                    'title' => $title,
                                    'message' => 'Một công việc vừa được tạo bởi ' . $user->firstname . ' ' . $user->lastname,
                                    'user_id' => $receiver,
                                ];


                                $result = $this->Notifications_model->create($notification);

                                if (!$result) {
                                    log_message('error', 'Failed to create notification for user ID: ' . $receiver);
                                }
                            }
                        }
                    }

                    return $item_id;
                case self::FOLDER_ID:
                    $this->db->insert("items", ['title' => 'Dự án chung', 'user_id' => $this->session->userdata('user_id'), 'owners' => $this->session->userdata('user_id'), 'type_id' => self::FOLDER_ID, 'parent_id' => $item_id]);

                    $this->db->insert("items", ['title' => 'Dự án cá nhân', 'user_id' => $this->session->userdata('user_id'), 'owners' => $this->session->userdata('user_id'), 'type_id' => self::FOLDER_ID, 'parent_id' => $item_id]);

                    return $item_id;

                default:
                    return $item_id;
            }
        }
        return $item_id;
    }
    public function update($data, $id)
    {
        $data_items = [];

        if (array_key_exists('title', $data)) {
            $data_items['title'] = strip_tags($data['title']);
        }
        if (array_key_exists('owners', $data)) {
            $data_items['owners'] = $data['owners'];
        }
        if (array_key_exists('deleted_at', $data)) {
            $data_items['deleted_at'] = $data['deleted_at'];
        }
        if (array_key_exists('deleted_by', $data)) {
            $data_items['deleted_by'] = $data['deleted_by'];
        }
        if (array_key_exists('parent_id', $data)) {
            $data_items['parent_id'] = $data['parent_id'];
        }
        if (array_key_exists('is_private', $data)) {
            $data_items['is_private'] = $data['is_private'];
        }

        if (array_key_exists('is_done', $data)) {
            $data_items['is_done'] = $data['is_done'];
        }

        if (array_key_exists('is_archived', $data)) {
            $data_items['is_archived'] = $data['is_archived'];
        }

        if (array_key_exists('filtered', $data)) {
            $data_items['filtered'] = $data['filtered'];
        }

        if (array_key_exists('rating', $data)) {
            $data_items['rating'] = $data['rating'];
        }

        if (array_key_exists('display', $data)) {
            $data_items['display'] = $data['display'];
        }

        if (array_key_exists('key_code', $data)) {
            $data_items['key_code'] = $data['key_code'];
        }

        if (array_key_exists('thumbnail', $data)) {
            $data_items['thumbnail'] = $data['thumbnail'];
        }

        $items_old = $this->find_by_id($id);

        $items_update = $this->db->update("items", $data_items, ["id" => $id]);

        if ($items_update) {

            $log = [
                'type' => 'POST',
                'table' => 'items',
                'table_id' => $id,
                'message' => 'success',
                'value_old' => isset($items_old->title) ? $items_old->title : "",
                'value_new' => isset($data['title']) ? $data['title'] : "",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
                'ip' => $this->session->userdata('ip'),
            ];

            $this->logs_model->create($log);

            foreach ($data as $key => $value) {
                if (!in_array($key, ['title', 'type_id', 'user_id', 'id', 'owners', 'deleted_at', 'deleted_by', 'parent_id', 'is_private', 'is_done', 'is_archived', 'rating']) && !empty($value)) {

                    if ($this->check_meta($id, $key)) {
                        $meta = $this->db->get_where('items_meta', ['items_id' => $id, 'key' => $key, 'deleted_at' => null])->row_object();

                        $meta_update = [
                            'meta_id' => $meta->id,
                            'key' => $key,
                            'value' => strip_tags($value),
                        ];

                        $this->update_meta($meta_update);
                    } else {
                        $this->add_meta($id, $key, strip_tags($value));
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function update_all($data, $items_id)
    {
        $result = $this->db->update('items', $data, ['parent_id' => $items_id]);
        return $result;
    }
    public function delete($id)
    {
        $item = $this->find_by_id($id);

        $result = $this->db->update('items', ['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->session->userdata('user_id')], ['id' => $id]);

        foreach ($this->get_all_childs_item($id) as $item_child) {
            $result = $this->db->update('items', ['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->session->userdata('user_id')], ['id' => $item_child->id]);
        }

        if ($result) {
            $log = [
                'type' => 'DELETE',
                'table' => 'items',
                'table_id' => $id,
                'message' => 'success',
                'value_old' => $item->title,
                'value_new' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
                'ip' => $this->session->userdata('ip'),
            ];
            $this->logs_model->create($log);
        }

        return $result;
    }
    public function delete_multiple($array_id)
    {

        if ($array_id) {
            foreach ($array_id as $i) {
                $this->db->where('id', $i);
                $this->db->where('deleted_at', null);
                $this->db->update('items', ['deleted_at' => date('Y-m-d H:i:s')]);
            }
            return true;
        }

        return false;
    }

    // Metas
    public function add_meta($id, $key, $value)
    {
        $data = [
            "items_id" => $id,
            "key"   => $key,
            "value" => strip_tags($value)
        ];

        $this->db->insert("items_meta", $data);
        $item_id = $this->db->insert_id();
        return $item_id;
    }
    public function update_meta($data)
    {
        $meta_id = $data["meta_id"];
        $value = strip_tags($data["value"]);
        $type = isset($data["type"]) ? $data["type"] : "";

        $meta_old = $this->db->get_where("items_meta", ['id' => $meta_id, 'deleted_at' => null])->row_object();

        $users_id = array();

        $log = [
            'type' => 'PUT',
            'table' => 'items_meta',
            'table_id' => $meta_old->id,
            'message' => 'success',
            'value_old' => $meta_old->value,
            'value_new' => $value,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'user_id' => $this->session->userdata('user_id'),
            'ip' => $this->session->userdata('ip'),
        ];

        $this->logs_model->create($log);

        if (isset($type)) {
            switch ($type) {
                case "people_add":
                    if ($meta_old->value) {
                        $users_id = explode(",", $meta_old->value);
                    }

                    $users_id[] = $value;
                    $users_id = implode(',', $users_id);

                    $value = $users_id;

                    break;
                case "people_remove":
                    if ($meta_old->value) {
                        $users_id = explode(",", $meta_old->value);
                    }

                    $key_user = array_search($value, $users_id);

                    unset($users_id[$key_user]);

                    $users_id = implode(',', $users_id);

                    $value = $users_id;

                    break;
                case "add_dependent_task":
                    if ($meta_old->value) {
                        $items_id = explode(",", $meta_old->value);
                    }

                    $items_id[] = $value;
                    $items_id = implode(',', $items_id);

                    $value = $items_id;

                    break;
                case "remove_dependent_task":
                    if ($meta_old->value) {
                        $items_id = explode(",", $meta_old->value);
                        $items_id = array_filter($items_id, function ($item) use ($value) {
                            return $item != $value;
                        });
                        $value = implode(',', $items_id);
                    }
                    break;
                case "status":
                    if ($meta_old->value) {
                        $status_item = explode("|", $value);
                        $is_success = 0;

                        if ($status_item[0] == "hoanthanh") {
                            $is_success = 1;
                        }

                        $meta = $this->db->get_where('items_meta', ['id' => $meta_id])->row_object();
                        $this->db->update('items', ['is_done' => $is_success], ['id' => $meta->items_id]);
                    }
                    break;
                default:
                    break;
            }
        }

        $result = $this->db->update("items_meta", array('value' => $value), ["id" => $meta_id]);

        if ($result) {
            return $this->db->get_where('items_meta', ['id' => $meta_id])->row_object();
        }

        return $result;
    }
    public function remove_meta($id, $key)
    {
        $meta_old = $this->get_meta($id, $key);
        $log = [
            'type' => 'DELETE',
            'table' => 'items_meta',
            'table_id' => $meta_old[0]->id,
            'message' => 'success',
            'value_old' => $meta_old[0]->value,
            'value_new' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'user_id' => $this->session->userdata('user_id'),
            'ip' => $this->session->userdata('ip'),
        ];
        $this->logs_model->create($log);
        return $this->db->update("items_meta", ["deleted_at" => date('Y-m-d H:i:s')], ["items_id" => $id, "key" => $key]);
    }
    public function remove_all_meta($id)
    {
        return $this->db->update("items_meta", ["deleted_at" => date('Y-m-d H:i:s')], ["items_id" => $id]);
    }
    public function get_meta($id, $key)
    {
        $query = $this->db->get_where("items_meta", ["items_id" => $id, "key" => $key, 'deleted_at' => null]);
        return $query->row_object();
    }
    public function get_meta_by_value($id, $key, $value)
    {
        $query = $this->db->get_where("items_meta", ["items_id" => $id, "key" => $key, "value" => $value,  'deleted_at' => null]);
        return $query->row_object();
    }
    public function get_meta_by_key($key)
    {
        $query = $this->db->get_where("items_meta", ["key" => $key, 'deleted_at' => null]);
        return $query->row_object();
    }
    public function get_items_meta_by_key($key)
    {
        $query = $this->db->get_where("items_meta", ["key" => $key, 'deleted_at' => null]);
        return $query->result_object();
    }
    public function search_meta($data)
    {
        $sql = "SELECT * FROM items_meta WHERE 1 ";

        $condition_arr = [
            "",
            "=",
            ">=",
            "<=",
            "!=",
            ">",
            "<",
        ];

        foreach ($data as $key => $value) {
            $sql_val = '';

            if ($value['type'] != null && $value['type'] == 'date') {

                $value_date = str_replace('-', '', $value['value']);

                $sql_val = " AND REPLACE(value, '-', '') " . $condition_arr[$value['condition']] . "'" . $value_date . "' ";
            } else {
                $sql_val = " AND `value` " . $condition_arr[$value['condition']] . "'" . $value['value'] . "' ";
            }

            if ($value['logic'] == 'AND') {
                $sql .= "AND `key` = '" . strip_tags($value['key']) . "'" . $sql_val;
            } else {
                $sql .= "OR `key` = '" . strip_tags($value['key']) . "'" . $sql_val;
            }
        }

        $query = $this->db->query($sql);

        return $query->result_object();
    }

    public function get_meta_by_key_include_deleted($key)
    {
        $query = $this->db->get_where("items_meta", ["key" => $key]);
        return $query->result_object();
    }

    public function get_meta_by_id($id)
    {
        $query = $this->db->get_where("items_meta", ["id" => $id]);
        return $query->row_object();
    }

    public function get_meta_by_task_and_by_type_html($item_id, $type_html)
    {
        $items = $this->get_all_childs_item($item_id);

        $result = [];

        $query = $this->db->select('items_meta.*')
            ->from('items_meta')
            ->join('fields', 'fields.key = items_meta.key', 'left')
            ->where('fields.type_html', $type_html)
            ->where('items_meta.items_id', $item_id)
            ->where('items_meta.deleted_at', null)
            ->get();

        $result[] = $query->result_object();

        foreach ($items as $item) {
            $sub_query  = $this->db->select('items.id')
                ->from('items')
                ->join('items_meta', 'items_meta.items_id = items.id', 'left')
                ->join('fields', 'fields.key = items_meta.key', 'left')
                ->where('items.id', $item->id)
                ->where('fields.type_html', 'confirm')
                ->where_not_in('items_meta.value', ['hoanthanh|success'])
                ->group_by('items.id')
                ->get_compiled_select();

            $query      = $this->db->select('items_meta.value')
                ->from('items_meta')
                ->join('fields', 'fields.key = items_meta.key', 'left')
                ->where('fields.type_html', 'people')
                ->where("items_meta.items_id IN ($sub_query)", null, false)
                ->where('items_meta.deleted_at', null)
                ->get();

            $result[]   = $query->result_object();
        }

        return $result;
    }

    public function check_meta($id, $key)
    {
        return $this->get_meta($id, $key);
    }

    public function get_all_meta($id)
    {
        $query = $this->db->get_where("items_meta", ["items_id" => $id, 'deleted_at' => null]);
        return $query->result_object();
    }

    // Children
    public function get_child_items($id, $orderColumn = "position", $orderType = "asc")
    {
        $this->db->distinct();
        $this->db->from("items");
        $this->db->where(["parent_id" => $id, 'deleted_at' => null]);
        $this->db->order_by($orderColumn, $orderType);
        $query = $this->db->get();
        $result = $query->result_object();

        return $result;
    }

    public function get_groups_by_owner($id, $orderColumn = "position", $orderType = "asc")
    {
        $user_id = $this->session->userdata('user_id');
        $project = $this->find_by_id($id);

        $this->db->from("items");
        $this->db->where(["parent_id" => $id, 'type_id' => self::GROUP_ID, 'deleted_at' => null]);

        if (!in_array($user_id, explode(',', $project->owners))) {
            $this->db->where("FIND_IN_SET('$user_id', owners)");
        }

        $this->db->order_by($orderColumn, $orderType);
        $query = $this->db->get();
        $result = $query->result_object();
        return $result;
    }

    public function get_groups($id, $condition = [])
    {
        $result = $this->db->get_where("items", array_merge(["parent_id" => $id, 'type_id' => self::GROUP_ID, 'deleted_at' => null], $condition))->result_object();

        return $result;
    }

    public function get_child_items_order_by_created_at($id)
    {
        $query = $this->db->order_by('created_at', 'DESC')->get_where("items", ["parent_id" => $id, 'deleted_at' => null]);

        return $query->result_object();
    }

    public function get_all_childs_item($id, &$result = array())
    {

        $child_items = $this->get_child_items_order_by_created_at($id);

        foreach ($child_items as $child) {
            array_push($result, $child);
            $this->get_all_childs_item($child->id, $result);
        }

        return $result;
    }

    public function get_all_childs_item_include_deleted($id, &$result = array())
    {

        $child_items = $this->get_child_items_order_by_created_at_include_deleted($id);

        foreach ($child_items as $child) {
            array_push($result, $child);
            $this->get_all_childs_item_include_deleted($child->id, $result);
        }

        return $result;
    }

    public function get_child_items_order_by_created_at_include_deleted($id)
    {
        $query = $this->db->order_by('created_at', 'DESC')->get_where("items", ["parent_id" => $id]);

        return $query->result_object();
    }

    public function get_where($id, $condition)
    {
        $query = $this->db->get_where("items", array_merge(["parent_id" => $id, 'deleted_at' => null], $condition));
        return $query->result_object();
    }

    // Fields
    public function get_fields($id)
    {
        $query = $this->db->get_where("fields", ["items_id" => $id, "deleted_at" => null]);
        return $query->result_object();
    }

    public function get_field_by_key($key)
    {
        $query = $this->db->get_where("fields", ["key" => $key, "deleted_at" => null]);
        return $query->row();
    }

    public function add_field($data)
    {
        $result = $this->db->insert('fields', $data);
        return $result;
    }

    // Group 
    function add_group($data, $position = null)
    {
        $data['owners'] = $this->session->userdata('user_id');
        $data['user_id'] = $this->session->userdata('user_id');

        $result = $this->db->insert('items', $data);
        $group   = $this->find_by_id($this->db->insert_id());

        if ($result) {

            $log = [
                'type' => 'POST',
                'table' => 'items',
                'table_id' => $group->id,
                'message' => 'success',
                'value_old' => null,
                'value_new' => $group->title,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => $this->session->userdata('user_id'),
                'ip' => $this->session->userdata('ip'),
            ];

            $this->logs_model->create($log);

            // Notification
            $project = $this->find_by_id($group->parent_id);

            $receivers = explode(',', $project->owners);

            $title = $project->title;

            if (($key = array_search($this->session->userdata('user_id'), $receivers)) !== false) {
                unset($receivers[$key]);
            }

            $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));

            if ($receivers) {

                foreach ($receivers as $receiver) {

                    $receiver = trim($receiver);

                    $notification = [
                        'title' => $title,
                        'message' => 'Một nhóm công việc vừa được tạo bởi ' . $user->firstname . ' ' . $user->lastname,
                        'user_id' => $receiver,
                    ];


                    $result = $this->Notifications_model->create($notification);

                    if (!$result) {
                        log_message('error', 'Failed to create notification for user ID: ' . $receiver);
                    }
                }
            }


            // Handle update key code for group
            $this->db->update('items', ['key_code' => time() . $group->id], ['id' => $group->id]);

            // Handle sort group
            if (isset($position) && $position == 'top') {
                $array_id = [];

                $groups = $this->db->get_where('items', ['parent_id' => $data['parent_id']])->result_object();

                foreach ($groups as $key => $group) {
                    $array_id[] = $group->id;
                }

                if (count($array_id) > 0) {
                    // Reverse position
                    unset($array_id[count($array_id) - 1]);
                    array_unshift($array_id, $group->id);
                    // Handle sort group
                    $this->sort($array_id);
                }
            } else {

                $this->db->select_max('position');
                $this->db->where(['parent_id' => $data['parent_id'], 'type_id' => 5]);
                $this->db->from('items');
                $query = $this->db->get();

                if ($query->num_rows() > 0) {
                    $max_position = $query->row('position') + 1;
                    $this->db->update('items', ['position' => $max_position], ['id' => $group->id]);
                }
            }
        }

        return $group;
    }

    public function get_events_by_project_item($project_item_id)
    {
        $events = [];

        $groups = $this->get_groups_by_owner($project_item_id);

        $items_belongTo_project = [];

        foreach ($groups as $group) {
            $items = $this->get_child_items($group->id);
            foreach ($items as $item) {
                $items_belongTo_project[] = $item;
            }
        }

        // $items_belongTo_project = $this->get_all_childs_item($project_item_id);

        foreach ($items_belongTo_project as $item) {
            $query = $this->db
                ->distinct()
                ->select('items_meta.*, items.*')->from('items')
                ->join('items_meta', 'items.id = items_meta.items_id')
                ->join('fields', 'fields.key = items_meta.key')
                ->where('items.id', $item->id)
                ->where('fields.type_html', 'date')
                ->where('items.deleted_at', null)
                ->where('fields.deleted_at', null)
                ->where('items_meta.deleted_at', null)
                ->get();
            if ($query->num_rows() > 0) {
                array_push($events, $query->result_object());
            }
        }

        $result = [];

        foreach ($events as $subArray) {
            foreach ($subArray as $item) {
                $result[] = $item;
            }
        }

        return $result;
    }

    public function get_by_user($userId)
    {

        $query = $this->db
            ->distinct()
            ->select('items.*')
            ->from('items_meta')
            ->join('items', 'items_meta.items_id = items.id')
            ->join('fields', 'fields.key = items_meta.key')
            ->where('items_meta.value', $userId)
            ->where('fields.type_html', 'people')
            ->where('items.deleted_at', null)
            ->where('fields.deleted_at', null)
            ->where('items_meta.deleted_at', null)
            ->get();

        return $query->result_object();
    }

    public function sort($array_id)
    {

        $user_id = $this->session->userdata('user_id');

        for ($i = 0; $i < count($array_id); $i++) {

            $item_config = $this->db->get_where('item_configs', ['items_id' => $array_id[$i], 'user_id' => $user_id, 'key' => 'position', 'deleted_at' => null])->row_object();

            $_item_config = [
                'items_id' => $array_id[$i],
                'key' => 'position',
                'value' => $i,
                'user_id' => $user_id
            ];

            if (empty($item_config)) {
                $this->db->insert('item_configs', $_item_config);
            } else {
                $this->db->update('item_configs', $_item_config, ['id' => $item_config->id]);
            }
        }

        return true;
    }
    public function sort_task($array_id)
    {

        $user_id = $this->session->userdata('user_id');

        for ($i = 0; $i < count($array_id); $i++) {
            $this->db->update('items', ['position' => $i], ['id' => $array_id[$i], 'user_id' => $user_id]);
        }
        return true;
    }
    public function get_percent($group_id, $key, $status)
    {

        $group = $this->db->get_where("items", ['id' => $group_id, 'type_id' => self::GROUP_ID, 'deleted_at' => null])->row_object();

        $items = $this->db->get_where("items", ['parent_id' => $group->id, 'deleted_at' => null])->result_object();

        $sum   = 0;

        foreach ($items as $item) {

            $item_meta = $this->db->get_where("items_meta", ['items_id' => $item->id, 'key' => $key, 'deleted_at' => null])->row_object();

            if (in_array($status, explode("|", $item_meta->value))) {
                $sum++;
            }
        }

        return [($sum / count($items) * 100), $sum];
    }

    public function add_owner_to_project($item_id, $user_id)
    {
        $flag   = true;
        $_item  = $this->db->get_where('items', ['id' => $item_id, 'deleted_at' => null])->row_array();
        $owners = explode(',', $_item['owners']);

        foreach ($owners as $owner) {
            if ($owner == $user_id) {
                $flag = false;
            }
        }

        if ($flag) {
            $owners[] = $user_id;
        }

        $_item['owners'] = implode(',', $owners);

        return $this->update($_item, $item_id);
    }

    public function add_department_to_project($item_id, $department_id)
    {
        $_item  = $this->db->get_where('items', ['id' => $item_id, 'deleted_at' => null])->row_array();
        $users  = $this->User_model->get_users_by_department($department_id);

        $owners = explode(',', $_item['owners']);


        foreach ($users as $user) {
            if (!in_array($user->id, $owners)) {
                $owners[] = $user->id;
            }
        }

        $_item['owners'] = implode(',', $owners);

        return $this->update($_item, $item_id);
    }

    public function get_owners($item_id)
    {
        $_item = $this->find_by_id($item_id);

        $owners_id = explode(',', $_item->owners);

        $owners = [];

        foreach ($owners_id as $owner_id) {
            $owners[] = $this->User_model->get_user_by_id($owner_id);
        }

        return $owners;
    }

    public function delete_user_from_item($user_id, $item_id)
    {
        $item = $this->find_by_id($item_id);

        $flag = false;

        //Check owners của dự án
        if ($this->find_by_id($item->parent_id)) {
            if (in_array($this->session->userdata('user_id'), explode(',', $this->find_by_id($item->parent_id)->owners))) {
                $flag = true;
            }
        }

        $owners = explode(',', $item->owners);

        //Check owner của nhóm
        if ($this->session->userdata('user_id') == $owners[0]) {
            $flag = true;
        }

        if ($flag) {

            foreach ($owners as $key => $owner) {

                if ($owner == $user_id && $key !== 0) {

                    unset($owners[$key]);
                }
            }

            $update_owners = implode(',', $owners);

            $data = [
                'owners' => $update_owners
            ];

            $this->update($data, $item_id);
            return $this->get_owners($item_id);
        }
        return false;
    }

    public function restore($id)
    {
        $item = $this->db->get_where('items', ['id' => $id])->row_object();

        if (!empty($item)) {
            $data = [
                'deleted_at' => null,
                'deleted_by' => null,
            ];

            $result = $this->db->update('items', $data, ['id' => $id]);

            $childs_of_item = $this->get_all_childs_item_include_deleted($id);

            foreach ($childs_of_item as $child_of_item) {

                $result = $this->db->update('items', $data, ['id' => $child_of_item->id]);

                //Restore các fields của dự án (nếu tồn tại)
                $fields = $this->Items_model->get_fields($child_of_item->id);

                if (!empty($fields)) {
                    foreach ($fields as $field) {
                        $this->Fields_model->restore($field->id);
                    }
                }

                //Restore các meta của dự án (nếu tồn tại)
                $items_meta = $this->get_all_meta($child_of_item->id);

                if (!empty($items_meta)) {
                    foreach ($items_meta as $item_meta) {
                        $this->restore_meta($item_meta->id);
                    }
                }
            }
            return $result;
        }
        return false;
    }
    public function restore_meta($item_meta_id)
    {
        $data = [
            'deleted_at' => null,
            'deleted_by' => null,
        ];

        return $this->db->update('items_meta', $data, ['id' => $item_meta_id]);
    }

    public function get_all_items_by_user_id($userId)
    {
        $this->db->from("items");
        $this->db->where(["user_id" => $userId]);
        $this->db->order_by("position", "asc");
        $query  = $this->db->get();
        $result = $query->result_object();

        return $result;
    }
    public function search_items($search_key)
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->select("projects.*, folder.id as folder_id");
        $this->db->from("items as projects");
        $this->db->join("items as sub_folder", 'projects.parent_id = sub_folder.id');
        $this->db->join("items as folder", 'sub_folder.parent_id = folder.id');
        $this->db->like('projects.title', $search_key, 'both');
        $this->db->where("projects.parent_id >=", "0");
        $this->db->where_in("projects.type_id", [6, 30, 31, 32]);
        $this->db->where('find_in_set("' . $user_id . '", projects.owners) <> 0');
        $this->db->where("projects.deleted_at", null);

        // $sql = "SELECT * FROM items WHERE title LIKE '%$search_key%' and owners like '%" . 
        // $this->session->userdata('user_id') . "%' 
        // and parent_id >= 0 and type_id in (6,30,31,32) and deleted_at IS NULL";

        $query = $this->db->get();

        return $query->result_object();
    }

    public function get_owner($item_id)
    {
        $_item = $this->find_by_id($item_id);

        $owner_id = $_item->user_id;

        $project_owner =  $this->User_model->get_user_by_id($owner_id);

        return $project_owner;
    }
    public function get_items_general($parent_id = null, $type_id = [self::PROJECT_ID, self::TABLE_ID, self::TIMETALBE_ID])
    {
        $user_id = $this->session->userdata("user_id");

        $type_id_str = implode(',', array_map('intval', $type_id));

        if ($parent_id == null) {
            $sql = "SELECT * FROM items WHERE type_id IN ($type_id_str) AND deleted_at IS NULL";
        } else {
            $sql = "SELECT * FROM items WHERE type_id IN ($type_id_str) AND parent_id = ? AND deleted_at IS NULL";
        }

        $query = $this->db->query($sql, array($parent_id));

        $items = $query->result();

        $result = [];

        foreach ($items as $key => $item) {

            $owners_arr = isset($item->owners) ? explode(",", $item->owners) : [];
            unset($owners_arr[0]);

            if (in_array($user_id, $owners_arr)) {
                $result[] = $item;
            } else {
                foreach ($this->get_all_childs_item($item->id) as $child_item) {
                    $owners_arr = isset($child_item->owners) ? explode(",", $child_item->owners) : [];
                    unset($owners_arr[0]);

                    if (in_array($user_id, $owners_arr)) {
                        $result[] = $item;
                        break;
                    }
                }
            }
        }

        return count($result) > 0 ? $result : [];
    }

    public function get_items_by_folder($id, $user_id = null)
    {
        $this->db->select('*')->from("items");
        $this->db->where('parent_id', $id);
        $this->db->where_in('type_id', [self::PROJECT_ID, self::TABLE_ID, self::TIMETALBE_ID]);
        $this->db->where('deleted_at', null);

        $items_of_folder = $this->db->get()->result_object();

        $result = [];

        foreach ($items_of_folder as $item) {

            if (in_array($user_id, explode(',', $item->owners))) {

                $result[] = $item;
            } else {

                $groups_of_item = $this->db->get_where('items', ['parent_id' => $item->id, 'type_id' => self::GROUP_ID, 'deleted_at' => null])->result_object();

                foreach ($groups_of_item as $group) {

                    if (in_array($user_id, explode(',', $group->owners))) {
                        $result[] = $item;
                        break;
                    }
                }
            }
        }

        return $result;
    }

    // const GROUP_ID = 5;
    // const PROJECT_ID = 6;
    // const TASK_ID = 8;
    // const SUB_TASK = 28;
    // const FOLDER_ID = 7;
    // const BOARD_ID = 27;
    // const DEPARTMENT_ID = 30;
    // const TABLE_ID = 31;
    // const TIMETALBE_ID = 32;
    // const TABLEITEM_ID = 35;
}
