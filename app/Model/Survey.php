<?php 
class Survey extends AppModel
{
  var $name = "Survey";
  /**
   * Defines any hasMany relationships
   * 
   * @var array
   */
  public $hasMany = array(
    'Question'
  );

  var $validate= array(
    'Name' => array(
      'notBlank' => array(
        'rule' => array('notBlank'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        'required' => true,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'message' => 'There is a survey with the same name'
      ),
    ),
    'Email'=>array(
     
      'notBlank' => array(
        'rule' => array('notBlank'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        'required' => true,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'required' => array(
        'rule' => array('email'),
        'message' => 'Kindly provide valid email.'
      ),
    )
  );
  /**
   * Callback: After a record is deleted, delete the cache.
   * 
   * @return void
   */
  public function afterDelete()
  {
    Cache::clearGroup('Survey');
  }

  /**
   * Callback: After a record is created or updated, delete the cache.
   * 
   * @param bool $created If true means a new record, else update
   * @param array $options The same as passed to Model::save()
   * @return void
   */
  public function afterSave($created, $options = array())
  {
    Cache::clearGroup('Survey');
  }

  /*public function findById($surveyId = null)
  {
    $survey = Cache::read("Survey.{$surveyId}", 'survey');
    if (!$survey) {
      // Check the type exists before caching
      if (!$this->exists($surveyId)) {
        return false;
      }

      // Write the data to the cache
      $question = $this->find('first', array(
        'conditions' => array(
          'Survey.Id' => $surveyId
        ),
        'fields' => array(
          'Survey.Id',
          'Survey.Name',
          'Survey.Email',
        )
      ));

      Cache::write("Survey.{$surveyId}", $survey, 'survey');
      $survey['Survey']['is_query'] = true;
    }
    return $survey;
  }*/
}