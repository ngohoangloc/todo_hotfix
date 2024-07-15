<?php

class Table extends CI_Controller
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
    const TIMETALBE_ITEM_ID = 33;
    const TABLEITEM_ID = 35;


    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model("Items_model");
        $this->load->model("types_model");
        $this->load->model("fields_model");
        $this->load->model("conversations_model");
        $this->load->model("items_role_model");
        $this->load->library('parser');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }

    public function index()
    {
        $item_id = $this->input->get('item_id');

        var_dump($item_id);
        die();
    }

    function view($folder_id, $id)
    {


        if ($this->check_is_owner($id)) {


            $data['project'] = $this->Items_model->find_by_id($id);

            $data['groups'] = $this->Items_model->get_groups_by_owner($id, "position", "asc");
            $data['is_owner'] = false;
            $data['parent_id'] = $folder_id;

            if (in_array($this->session->userdata('user_id'), explode(',', $data['project']->owners))) {
                $data['is_owner'] = true;
            }

            $data['types'] = $this->types_model->get_all();

            // load trưởng dự án
            $data['project_owner'] = $this->Items_model->get_owner($id);


            // load roles for each group
            $data['items_roles_by_group'] = [];
            foreach ($data['groups'] as $group) {
                $data['items_roles_by_group'][$group->id] = $this->items_role_model->find_by_item_id($group->id);
            }

            // load item_permission
            $data['items_permission'] = $this->items_role_model->get_items_permission();


            $this->load->admin("admin/views/table", $data);
            
        } else {
            return redirect('/items');
        }
    }

    function add()
    {
        $this->Items_model->add($this->input->post());
    }

    function check_is_owner($item_id)
    {
        if ($this->session->userdata('user_id')) {
            $item = $this->Items_model->find_by_id($item_id);
            if (!$item) {
                return false;
            }

            $groups = $this->Items_model->get_groups($item->id);

            foreach ($groups as $group) {
                $owners = explode(',', $group->owners);

                if (in_array($this->session->userdata('user_id'), $owners)) {
                    return true;
                }
            }

            $owners = explode(',', $item->owners);

            return in_array($this->session->userdata('user_id'), $owners) ? true : false;
        } else {
            return false;
        }
    }
    public function filter()
    {
        $payload = $this->input->post("payload");
        $type_filter = $this->input->post("type_filter");

        $items_meta = $this->Items_model->search_meta($payload);

        $result = [];

        $item_type_id = null;

        if ($type_filter == "table") {
            $item_type_id = self::TASK_ID;
        } else if ($type_filter == "timetable") {
            $item_type_id = self::TIMETALBE_ITEM_ID;
        } else if ($type_filter == "customtable") {
            $item_type_id = self::TABLEITEM_ID;
        }

        foreach ($items_meta as $key => $meta) {
            $item = $this->Items_model->find_by_id_and_type($meta->items_id, $item_type_id);

            if (isset($item)) {
                $result[] =  $meta->items_id;
            }
        }

        echo json_encode(array("success" => true, 'data' => $result));
    }
    public function get_filter_item_html()
    {
        $project_id = $this->input->post("project_id");

        $data['project'] = $this->Items_model->find_by_id($project_id);;

        $html = $this->load->view("admin/views/components/filter-column-item", $data, true);

        echo json_encode(array("success" => true, 'data' => $html));
    }
}
