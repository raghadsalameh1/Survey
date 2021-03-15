<?php
App::uses('AppModel', 'Model');
/**
 * Note Model
 *
 * @property Visitor $Visitor
 */
class Note extends AppModel {

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

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'Note' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

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
		)
	);

    private $encrypt_key = 'wt1U5MACWJFTXGenFoZoiLwQGrLgdbHA';
	private $cipher = "AES-128-CBC";
	private $iv = "��hņYa�a";

	public function beforeSave($options = array())
	{
		$value = $this->data['Note']['Note'];
		//$iv = openssl_random_pseudo_bytes(16);
		$encrypted = openssl_encrypt($value, $this->cipher, $this->encrypt_key,0,$this->iv);
		$this->data['Note']['Note'] = $encrypted;
		return true;
	    
	}

	public function afterFind($results, $primary = false)
	{
		//$ivlen = openssl_cipher_iv_length($this->cipher);
		//$iv = openssl_random_pseudo_bytes($this->iv);
		for ($i=0; $i < count($results) ; $i++) {
			if (isset($results[$i]['Note']['Note'])) {
				$results[$i]['Note']['Note'] = openssl_decrypt($results[$i]['Note']['Note'], "AES-128-CBC", $this->encrypt_key,0,$this->iv);
			}
		}
		return $results;
	}
}
