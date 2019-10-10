<?php
/**
 * Created by PhpStorm.
 * User: slebedeva
 * Date: 08.03.2019
 * Time: 17:01
 */

class Task extends EntitiesHelper
{
    const API = 'api/v2/tasks';

    /*public function get_api() {
        return API_TASKS;
    }*/

    public function get_data()
    {
        return [
            'element_id' => $this->_element_id, //айди сущности, к которой крепится задача
            'element_type' => $this->_element_type, // айди таблицы
            'complete_till' => $this->_complete_till, // дата завершения
            'task_type' => $this->_task_type, // тип задачи
            'responsible_user_id' => $this->_user_id, // айди ответвтсвенного пользователя
            'text' => $this->_text,
            'is_completed' => $this->_is_completed, //статус задачи
        ];
    }

    public function get_data_update()
    {
        return [
            'id' => $this->_id, //айди задачи
            'updated_at' => $this->_date_update, //дата обновления
            'is_completed' => $this->_is_completed, //статус задачи
        ];
    }
}