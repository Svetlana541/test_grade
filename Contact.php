<?php
/**
 * Created by PhpStorm.
 * User: slebedeva
 * Date: 04.03.2019
 * Time: 17:04
 */

class Contact extends EntitiesHelper
{

    public function get_data() {
        return [
            "name" => $this->_name,
            "company_id" => $this->_company_id
        ];
    }
    public function random(array $arr) {
        $values = [];
        $random_value = array_rand($arr, mt_rand(2,  count($arr)));
        foreach ($random_value as $arr_key ) {
            $values[] = $arr[$arr_key];
        }
        return $values;
    }
    public function get_data_update($field) {
        return [
            "id" => $this->_id,
            "updated_at" => time(),
            "custom_fields" => [
                [
                   "id" => $field->get_id(),
                    "values" => $this->random($field->get_enums()),
                ]
            ]

        ];
    }
}