<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyWork extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $query = $this->db->select('items.*')
            ->from('items')
            ->join('fields', 'fields.items_id = items.id')
            ->join('items_meta', 'items_meta.key = fields.key')
            ->where('fields.type_html', 'people')
            ->where("FIND_IN_SET('" . $this->session->userdata('user_id') . "', items_meta.value)")
            ->where('items.deleted_at', null)
            ->group_by('items.id')
            ->get();

        $data['items'] = $query->result_object();

        foreach ($data['items'] as $item_key => $item) {

            $fields = $this->Items_model->get_fields($item->id);

            $data['items'][$item_key]->fields = $fields;

            $groups = $this->Items_model->get_child_items($item->id);

            $data['items'][$item_key]->groups = [];

            foreach ($groups as $group_key => $group) {
                $tasks = $this->Items_model->get_child_items($group->id);

                foreach ($tasks as $task_key => $task) {
                    foreach ($fields as $field) {
                        if ($field->type_html == 'people') {

                            $meta = $this->Items_model->get_meta_by_field($task->id, $field->key);

                            $members = explode(',', $meta->value);
                            foreach ($members as $member) {
                                if ($member == $this->session->userdata('user_id')) {

                                    if (!in_array($group, $data['items'][$item_key]->groups)) {
                                        $data['items'][$item_key]->groups[] = $group;
                                    }

                                    $groupIndex = array_search($group, $data['items'][$item_key]->groups);
                                    $data['items'][$item_key]->groups[$groupIndex]->tasks[] = $task;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        // echo "<pre>";
        // var_dump(array_keys($data));
        // die();

        $this->load->admin('admin/views/my_work', $data);
    }
}
