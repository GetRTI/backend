<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ApiTestForm extends CFormModel
{
    public $name;
    public $department;
    public $description;
    public $file;
    
    public function rules() {
        return array(
		// name, email, subject and body are required
		array('name, department, file', 'required'),
	);
    }
}