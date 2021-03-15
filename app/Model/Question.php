<?php 
class Question extends AppModel
{
    var $name = "Question";
    public $belongsTo = array(
        'Survey',
        'YesQues' => array(
            'className' => 'Question',
            'foreignKey' => 'Yes_id'
        ),
        'NoQues' => array(
            'className' => 'Question',
            'foreignKey' => 'No_id'
        )
    );

    var $validate = array(
        'Question' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
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
        Cache::clearGroup('Question');
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
        Cache::clearGroup('Question');
    }

    public function findById($questionId = null)
    {
        $question = Cache::read("Question.{$questionId}", 'question');
        if (!$question) {
            // Check the type exists before caching
            if (!$this->exists($questionId)) {
                return false;
            }

            // Write the data to the cache
            $question = $this->find('first', array(
                'conditions' => array(
                    'Question.Id' => $questionId
                ),
                'fields' => array(
                    'Question.Id',
                    'Question.Question',
                    'Question.Survey_id',
                    'Question.Yes_id',
                    'Question.No_id'
                )
            ));

            Cache::write("Question.{$questionId}", $question, 'question');
            $question['Question']['is_query'] = true;
        }       
        return $question;
    }
}