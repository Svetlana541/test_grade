<?php
/**
 * Created by PhpStorm.
 * User: slebedeva
 * Date: 06.03.2019
 * Time: 14:56
 */

class Field extends EntitiesHelper
{
    public function get_data() {
        return [
            "name" => 'мультиполе',
            "element_type" => $this->_element_type,
            "type" => 5,
            "origin" => "123",
            "is_editable" => 1,
            "enums" => [
                "1",
                "2",
                "3",
                "4",
                "5",
                "6",
                "7",
                "8",
                "9",
                "10"
            ],
        ];
    }
    public function get_text_field() {
        return [
            "element_id" => $this->_element_id,
            "name" => $this->_name,
            "element_type" => $this->_element_type,
            "type" => 1,
            "origin" => '321',
        ];
    }
    public function text_field_update($elem_id) {
        return [
            "id" => $elem_id,
            "updated_at" => time()+10000*60+1000*3343,
            "custom_fields" => [
                [
                    "id" => $this->_id,
                    "values" => [
                        [
                            "value" => $this->_value,
                        ]
                    ]
                ]
            ]
        ];
    }
}