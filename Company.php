<?php
/**
 * Created by PhpStorm.
 * User: slebedeva
 * Date: 04.03.2019
 * Time: 17:04
 */

class Company extends EntitiesHelper
{

    public function get_data() {
        return [
            "name" => $this->_name,
        ];
    }
}