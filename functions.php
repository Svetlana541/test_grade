<?php
require_once("display_errors.php");
require "config.php";
require "classes/autoload.php";
$auth = new Auth();
$auth->connect();

/*function debug($str) {
    echo "<pre>";
    print_r($str);
    echo "</pre>";
    exit;
}*/
set_time_limit(0);
if(isset($_POST['num'])) {
    $num = $_POST['num'];
    manage($num);
}

if(isset($_POST['data'], $_POST['entity_id_task'], $_POST['entity_type_task'], $_POST['task_type'], $_POST['task'])) {
    $data = $_POST['data'];
    $entity_type_task = $_POST['entity_type_task'];
    $entity_id_task = $_POST['entity_id_task'];
    $task_type = $_POST['task_type'];
    $text_task = $_POST['task'];
    task($text_task, $entity_id_task, $entity_type_task, $task_type, $data);
}

if(isset($_POST['note_text'], $_POST['entity_id_note'], $_POST['entity_type_note'], $_POST['note_list'])) {
    $text_note = $_POST['note_text'];
    $entity_id_note = $_POST['entity_id_note'];
    $entity_type_note = $_POST['entity_type_note'];
    $note_list = $_POST['note_list'];
    note($text_note, $entity_id_note, $entity_type_note, $note_list);
}

if(isset($_POST['id_task'])) {
    $id_task = $_POST['id_task'];
    task_update($id_task);
}

if($_POST['field_type'] == 1) {
    $entity_id_field = $_POST['entity_id_field'];
    $entity_type_field = $_POST['entity_type_field'];
    $field_text = $_POST['field_text'];
    $field_type = $_POST['field_type'];
    text_field($entity_id_field, $entity_type_field, $field_text, $field_type);
}elseif($_POST['field_type'] == 5) {
    $field_type = $_POST['field_type'];
    multiple_field($field_type);
}

/**
 * @param $num
 * @return bool|null
 */
function manage ($num) {
    $result = NULL;
    if ($num > 10000 || $num < 0) {
        $result = FALSE;
    } elseif ($result !== FALSE) {
        $companies = [];
        for ($i = 0; $i < $num; $i++) {
            $company = new Company();
            $company->set_name('Company ' . $i);
            $companies[] = $company;
        }
        EntitiesHelper::mass_create($companies, API_COMPANY);
        $contacts = [];
        foreach ($companies as $key => $company) {
            $contact = new Contact();
            $contact->set_name('Contact ' . $i);
            $contact->set_company_id($company->get_id());
            $contacts[] = $contact;
        }
        EntitiesHelper::mass_create($contacts, API_CONTACTS);
        $deals = [];
        foreach ($companies as $key => $company) {
            $deal = new Lead();

            $deal->set_name('Deal ' . $i);
            $deal->set_contacts_id([$contacts[$key]->get_id()]);
            $deal->set_company_id($company->get_id());
            $deals[] = $deal;
        }
        EntitiesHelper::mass_create($deals, API_LEADS);
        $customers = [];
        foreach ($companies as $key => $company) {
            $customer = new Customer();
            $customer->set_name('Customer ' . $i);
            $customer->set_contacts_id([$contacts[$key]->get_id()]);
            $customer->set_company_id($company->get_id());
            $customers[] = $customer;
        }
        EntitiesHelper::mass_create($customers, API_CUSTOMERS);
        $result = TRUE;
    }
    header('Location: http://localhost/index.php');
    return $result;
}

/**
 * @param $text_task
 * @param $entity_id_task
 * @param $entity_type_task
 * @param $task_type
 * @param $data
 */
function task($text_task, $entity_id_task, $entity_type_task, $task_type, $data) {
    $user_id = (int) '3287101';
    $task = new Task;
    $task->set_element_id($entity_id_task);
    $task->set_element_type($entity_type_task);
    $task->set_task_type($task_type);
    $task->set_user_id($user_id);
    $task->set_complete_till($data);
    $task->set_text($text_task);
    $task->set_is_completed(0);
    EntitiesHelper::create_task($task);
    header('Location: http://localhost/index.php');
}

/**
 * @param $id_task
 */
function task_update($id_task) {
    $date_update = time();
    $task = new Task;
    $task->set_id($id_task);
    $task->set_date_update($date_update);
    $task->set_is_completed(1);
    EntitiesHelper::update_task($task);
    header('Location: http://localhost/index.php');
}

/**
 * @param $text_note
 * @param $entity_id_note
 * @param $entity_type_note
 * @param $note_list
 */
function note($text_note, $entity_id_note, $entity_type_note, $note_list) {
    $notes = new Notes;
    $notes->set_element_id($entity_id_note);
    $notes->set_element_type($entity_type_note);
    $notes->set_note_type($note_list);
    $notes->set_text($text_note);
        if ($note_list == 4) {
            EntitiesHelper::create_notes($notes);
        }elseif ($note_list == 10) {
            EntitiesHelper::create_call_in($notes);
        }
    header('Location: http://localhost/index.php');
}

/**
 * @param $entity_id_field
 * @param $entity_type_field
 * @param $field_text
 * @param $field_type
 */
function text_field($entity_id_field, $entity_type_field, $field_text, $field_type) {
        switch ($entity_type_field) {
            case 1:
                $api = API_CONTACTS;
                break;
            case 2:
                $api = API_LEADS;
                break;
            case 3:
                $api = API_COMPANY;
                break;
            case 12:
                $api = API_CUSTOMERS;
                break;
        }
        $name = 'Текстовое поле';
        $field_id = EntitiesHelper::check_text_field($entity_type_field);
        $field = new Field();
        $field->set_id($field_id);//задаем айди поля
        $field->set_value($field_text);
        if ($field_id == NULL) {
            $field->set_element_type($entity_type_field);
            $field->set_name($name);
            $field->set_element_id($entity_id_field);
            $field_id = EntitiesHelper::create_fields($field, $field_type);
        }
        $field->set_id($field_id);
        EntitiesHelper::update_text_field($entity_id_field, $api, $field);
        header('Location: http://localhost/index.php');
}

/**
 * @param $field_type
 */
function multiple_field($field_type) {
    $offset = 0;
    $contacts = [];
    for ($i = 0; $i < 2; $i++) {
        $res = Curl::query(NULL, "api/v2/contacts/?limit_rows=500&limit=$offset");
        $offset += 500;
        foreach ($res['_embedded']['items'] as $item) {
            $contact = new Contact();
            $contact->set_id($item['id']);
            $contacts[] = $contact;
        }
    }
    $field = new Field();
    $field->set_name("multiple");
    $field->set_element_type(1);
    $fields = EntitiesHelper::create_fields($field, $field_type);
    EntitiesHelper::mass_update($contacts, API_CONTACTS, $fields);
    header('Location: http://localhost/index.php');
}
