<?php
/**
 * Created by PhpStorm.
 * User: slebedeva
 * Date: 08.03.2019
 * Time: 12:30
 */

class Notes extends EntitiesHelper
{
public function get_data() {
    return [
        'element_id' => $this->_element_id, // айди конкретной сущности, к которой пробрасывается примечание
        'element_type' => $this->_element_type, //айди таблицы
        'note_type' => $this->_note_type, // тип примечания (обычное)
        'text' => $this->_text,
    ];
}
    public function get_data_call() {
        return [
            'element_id' => $this->_element_id, // айди конкретной сущности, к которой пробрасывается примечание
            'element_type' => $this->_element_type, //айди таблицы
            'note_type' => $this->_note_type, // тип примечания (обычное)
            'params' => [
                "UNIQ" => "BCEFA2341",
                "DURATION" => "33",
                "SRC" => "http://example.com/calls/1.mp3",
                "LINK" => "http://example.com/calls/1.mp3",
                "PHONE" => "89175421999",
            ]
        ];
    }
}