<?php
App::uses('AppModel', 'Model');
/**
 * Response Model
 *
 * @property Visitor $Visitor
 * @property Question $Question
 */
class Response extends AppModel {

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'Id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'Id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Visitor' => array(
			'className' => 'Visitor',
			'foreignKey' => 'Visitor_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'Question_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
