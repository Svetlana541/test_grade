<?php
/**
 * Created by PhpStorm.
 * User: slebedeva
 * Date: 04.03.2019
 * Time: 17:04
 */

class Customer extends EntitiesHelper
{

    public function get_data() {
        return [
            "name" => $this->_name,
            "contacts_id" => $this->_contacts_id,
            "company_id" => $this->_company_id
        ];
    }
}