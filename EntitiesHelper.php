<?php
/**
 * Created by PhpStorm.
 * User: slebedeva
 * Date: 04.03.2019
 * Time: 17:05
 */
abstract class EntitiesHelper
{
    protected $_id;
    protected $_name;
    protected $_enums;
    protected $_company_id;
    protected $_contacts_id;
    protected $_element_id;
    protected $_element_type;
    protected $_note_type;
    protected $_text;
    protected $_task_type;
    protected $_user_id;
    protected $_complete_till;
    protected $_is_completed;
    protected $_date_update;
    protected $_params;
    protected $_value;
    protected $_phone_number;
    protected $_direction;
    protected $_field_type;

    /**
     * @param array $entity
     * @param $API
     * @return array
     */
    static public function mass_create(array $entity, $API) {
        $ids = [];
        foreach (array_chunk($entity, 250, TRUE) as $entity_chunk) {
            $data = [];
            foreach ($entity_chunk as $entities) {
                $data['add'][] = $entities->get_data();
            }
            $result = Curl::query($data, $API);
            foreach ($result['_embedded']['items'] as $item) {
                $ids[] = $item['id'];
            }
        }
        foreach ($entity as $key => $ent) {
            $ent->set_id($ids[$key]);
        }
        //var_dump($ids);
        return $ids;
    }

    /**
     * @param array $entity
     * @param $api
     * @param $field
     */
    static public function mass_update($entity, $api, $field) {
        foreach (array_chunk($entity, 150, TRUE) as $entity_chunk) {
            $data = [];
            foreach ($entity_chunk as $entities) {
                $data['update'][] = $entities->get_data_update($field);
            }
            Curl::query($data, $api);
        }
    }
    static public function update_text_field($id, $api, $field) {
        $data = [];
        $data['update'][] = $field->text_field_update($id);
        Curl::query($data, $api);
    }

    /**
     * @param $field
     * @param $field_type
     * @return mixed
     */
    static public function create_fields($field, $field_type) {
        if ($field_type == 5) {
            $data = [];
            $data['add'][] = $field->get_data();
            $result = Curl::query($data, API_FIELDS);
            $id = $result['_embedded']['items']['0']['id'];
            $field->set_id($id);
            $id_s = [];
            $res = Curl::query(NULL, 'api/v2/account?with=custom_fields');
            $res = $res['_embedded']['custom_fields']['contacts'][$id];
            if ($res['field_type'] == 5) {
                foreach ($res['enums'] as $key => $enum) {
                    $id_s[] = $key;
                }
                $field->set_enums($id_s);
            }

            return $field;
        }elseif ($field_type == 1) {
            $arr_text_field = [];
            $arr_text_field['add'][] = $field->get_text_field();
            $res = Curl::query($arr_text_field, API_FIELDS);
            $field_id = $res['_embedded']['items']['0']['id'];
            return $field_id;
        }
    }

    /**
     * @param $notes
     */
    static public function create_notes($notes) {
        $data = [];
        $data['add'][] = $notes->get_data();
        Curl::query($data, API_NOTES);
    }

    /**
     * @param $notes
     */
    static public function create_call_in($notes) {
        $data = [];
        $data['add'][] = $notes->get_data_call();
        Curl::query($data, API_NOTES);
    }

    /**
     * @param $task
     */
    static public function create_task($task) {
        $data = [];
        $data['add'][] = $task->get_data();
        Curl::query($data, API_TASKS);
    }

    /**
     * @param $id
     * @param $date_update
     * @param $is_completed
     */
    static public function update_task($task) {
        $data = [];
        $data['update'][] = $task->get_data_update();
        Curl::query($data, API_TASKS);
    }

    /**
     * @param $element_type
     * @return null
     */
    static public function check_text_field($element_type) {
        switch ($element_type) {
            case 1:
                $entity_name = 'contacts';
                break;
            case 2:
                $entity_name = 'leads';
                break;
            case 3:
                $entity_name = 'companies';
                break;
            case 12:
                $entity_name = 'customers';
                break;
        }
        $result = Curl::query(NULL, 'api/v2/account?with=custom_fields');
        $result = $result['_embedded']['custom_fields'][$entity_name];
        $field_id = NULL;
        foreach($result as $res) {
            if ($res['field_type'] == 1) {
                $field_id = $res['id'];
                break;
            }
        }
        return $field_id;
    }

    public function set_id($id) {
        $this->_id = $id;
        return $this;
    }
    public function set_field_type($field_type) {
        $this->_field_type = $field_type;
        return $this;
    }
    public function set_phone_number($phone_number) {
        $this->_phone_number = $phone_number;
        return $this;
    }
    public function set_direction($direction) {
        $this->_direction = $direction;
        return $this;
    }
    public function set_value($value) {
        $this->_value = $value;
        return $this;
    }
    public function set_params($params) {
        $this->_params = $params;
        return $this;
    }
    public function set_note_type($note_type) {
        $this->_note_type = $note_type;
        return $this;
    }
    public function set_text($_text) {
        $this->_text = $_text;
        return $this;
    }
    public function set_element_id($element_id) {
        $this->_element_id = $element_id;
        return $this;
    }
    public function set_element_type($element_type) {
        $this->_element_type = $element_type;
        return $this;
    }
    public function set_task_type($task_type) {
        $this->_task_type = $task_type;
        return $this;
    }
    public function set_user_id($user_id) {
        $this->_user_id = $user_id;
        return $this;
    }
    public function set_complete_till($complete_till) {
        $this->_complete_till = $complete_till;
        return $this;
    }
    public function set_is_completed($is_completed) {
        $this->_is_completed = $is_completed;
        return $this;
    }
    public function set_date_update($date_update) {
        $this->_date_update = $date_update;
        return $this;
    }
    public function set_enums(array $enums) {
        return $this->_enums = $enums;
    }
    public function set_company_id($id) {
        return $this->_company_id = $id;
    }
    public function set_contacts_id($id) {
        return $this->_contacts_id = $id;
    }
    public function set_name($name){
        return $this->_name = $name;
    }
    public function get_id() {
        return $this->_id;
    }
    public function get_field_type() {
        return $this->_field_type;
    }
    public function get_phone_number() {
        return $this->_phone_number;
    }
    public function get_direction() {
        return $this->_direction;
    }
    public function get_value() {
        return $this->_value;
    }
    public function get_params() {
        return $this->_params;
    }
    public function get_note_type() {
        return $this->_note_type;
    }
    public function get_text() {
        return $this->_text;
    }
    public function get_element_id() {
        return $this->_element_id;
    }
    public function get_element_type() {
        return $this->_element_type;
    }
    public function get_task_type() {
        return $this->_task_type;
    }
    public function get_user_id() {
        return $this->_user_id;
    }
    public function get_complete_till() {
        return $this->_complete_till;
    }
    public function get_is_completed() {
        return $this->_is_completed;
    }
    public function get_date_update() {
        return $this->_date_update;
    }
    public function get_enums() {
        return $this->_enums;
    }
    public function get_company_id() {
        return $this->_company_id;
    }
    public function get_contacts_id() {
        return $this->_contacts_id;
    }
    public function get_name() {
        return $this->_name;
    }
}