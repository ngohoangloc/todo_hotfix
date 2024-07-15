<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fields extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('fields_model');
        $this->load->model('items_model');

        if (!$this->authenticate->is_authenticated()) {
            redirect('/login');
        }
    }
    public function index()
    {
        $this->load->admin('/admin/fields');
    }
    public function add()
    {
        if ($this->input->is_ajax_request()) {

            $type = $this->input->post('type');
            $item_id = $this->input->post('items_id');
            $group_id = $this->input->post('group_id');
            
            $type = $this->input->post('type');


            $group = $this->Items_model->find_by_id($group_id);

            $project = $this->Items_model->find_by_id($item_id);

            $data_request = $this->input->post();

            unset($data_request['group_id']);
            unset($data_request['type']);

            $field_id = $this->fields_model->add($data_request, $type);

            $field = $this->fields_model->get_by_id($field_id);
            $item_meta = $this->items_model->get_items_meta_by_key($field->key);

            $data['field'] = $field;
            $data['meta'] = $item_meta;
            $data['group'] = $group;
            $data['project'] = $project;

            $field_html = $this->load->view("admin/views/components/field-title", $data, true);

            if ($type == "customtable") {
                unset($data['group']);
            }

            $meta_html_arr = [];

            foreach ($item_meta as $key => $meta) {
                $data['meta'] = $meta;

                $meta_html_arr[] = [
                    'items_id' => $meta->items_id,
                    'meta_html' => $this->load->view("admin/views/components/task-meta", $data, true)
                ];
            }

            echo json_encode(array('success' => $field ? true : false, 'field_html' => $field_html, 'data_html' => $meta_html_arr));
        }
    }

    public function update($id)
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->fields_model->update($this->input->post(), $id);
            echo json_encode(array('success' => $result));
        }
    }

    public function update_all()
    {
        $project_id = $this->input->post("project_id");

        $data_update = [
            'display' => $this->input->post("display")
        ];

        $result = $this->fields_model->update_all($data_update, $project_id);
        echo json_encode(array('success' => $result));
    }

    public function fetchFields()
    {
        $result = $this->fields_model->get_all();
        echo json_encode(array('success' => true, 'data' => $result));
    }
    public function fetch_by_id($id)
    {
        if ($id) {
            $result = $this->fields_model->get_by_id($id);
            echo json_encode(array('success' => $result ? true : false, 'data' => $result ? $result : []));
        }
    }
    public function delete($id)
    {
        if ($id) {
            $result = $this->fields_model->delete($id);
            echo json_encode(array('success' =>  $result));
        }
    }

    public function get_meta()
    {
        $key = $this->input->post('key');
        $type_html = $this->input->post('type_html');

        $items_meta = $this->fields_model->get_meta_by_key_distint($key, $type_html);

        echo json_encode(array('success' => count($items_meta) > 0 ? true : false, 'data' => $items_meta, $type_html));
    }
}
