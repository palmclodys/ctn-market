<?php 

App::uses('AppModel', 'Model');

class Credit extends AppModel {

	public $name = 'Credit';

	public $belongsTo = array(
        'User' => array(
            'className'    => 'User',
            'foreignKey'   => 'user_id'
        )
    );

}