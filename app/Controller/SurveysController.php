<?php
App::uses('CakeEmail', 'Network/Email');
class SurveysController extends AppController
{
    var $name = "Surveys";
    /**
     * delete session values when going back to index
     * you may want to keep the session alive instead
     */
    function index()
    {
        //Cache::clear();
        $this->Session->delete('form');
        $this->Session->delete('numberOfQuesValidationError');
        //$var = $this->Survey->find('list', array('fields' => array('Survey.Id', 'Survey.Name', 'Survey.Email')));
        //$var = $this->Survey->Question->find('all', array('conditions' => array('Survey.Id' => 1)));
        //$var = $this->Survey->find('all',array('limit'=>2));
        $this->set('surveys', $this->Survey->find('all', array('recursive' => 0,)));
    }
    /**
     * use beforeRender to send session parameters to the layout view
     */
    public function beforeRender()
    {
        parent::beforeRender();
        $params = $this->Session->read('form.params');
        $this->set('params', $params);
    }

    /**
     * this method is executed before starting the form and retrieves one important parameter:
     * the form steps number
     * you can hardcode it, but in this example we are getting it by counting the number of files that start with msf_step_
     */
    public function msf_setup()
    {
        App::uses('Folder', 'Utility');
        $surveysViewFolder = new Folder(APP . 'View' . DS . 'Surveys');
        $steps = count($surveysViewFolder->find('msf_step_.*\.ctp'));
        $this->Session->write('form.params.steps', $steps);
        $this->Session->write('form.params.maxProgress', 0);
        $this->redirect(array('action' => 'msf_step', 1));
    }

    /**
     * this is the core step handling method
     * it gets passed the desired step number, performs some checks to prevent smart users skipping steps
     * checks fields validation, and when succeding, it saves the array in a session, merging with previous results
     * if we are at last step, data is saved
     * when no form data is submitted (not a POST request) it sets this->request->data to the values stored in session
     */
    public function msf_step($stepNumber)
    {

        /**
         * check if a view file for this step exists, otherwise redirect to index
         */
        if (!file_exists(APP . 'View' . DS . 'Surveys' . DS . 'msf_step_' . $stepNumber . '.ctp')) {
            $this->redirect('/surveys/index');
        }

        /**
         * determines the max allowed step (the last completed + 1)
         * if choosen step is not allowed (URL manually changed) the user gets redirected
         * otherwise we store the current step value in the session
         */
        $maxAllowed = $this->Session->read('form.params.maxProgress') + 1;
        if ($stepNumber > $maxAllowed) {
            $this->redirect('/surveys/msf_step/' . $maxAllowed);
        } else {
            $this->Session->write('form.params.currentStep', $stepNumber);
        }

        /**
         * check if some data has been submitted via POST
         * if not, sets the current data to the session data, to automatically populate previously saved fields
         */
        if ($this->request->is('post')) {

            /**
             * set passed data to the model, so we can validate against it without saving
             */
            if ($stepNumber == "1") {
                $this->Survey->set($this->request->data);
                $var = $this->Survey->validates();
                $number_of_questions = $this->request->data['numberOfQues'];
                if ($number_of_questions <= 0 || empty($number_of_questions)) {
                    $this->Session->write('numberOfQuesValidationError', 'number of questions should be greater than zero');
                    $var = false;
                }
                $this->Session->write('number_of_questions', $number_of_questions);
            } else {
                $this->Session->delete('numberOfQuesValidationError');
                //$data = $this->request->data;
                //$var =true;
                $this->loadModel('Question');
                $questions = $this->request->data;
                $prevSessionData = $this->Session->read('form.data');
                $number = $prevSessionData['numberOfQues'];
                $var = true;
                for ($i = 1; $i <= $number; $i++) 
                {
                    $question = $questions['Question' . $i];
                    if (empty($question))
                    { 
                      $var = false;
                      break;
                    }    
                }
            }

            /**
             * if data validates we merge previous session data with submitted data, using CakePHP powerful Hash class (previously called Set)
             */
            if ($var) {
                $prevSessionData = $this->Session->read('form.data');
                $currentSessionData = Hash::merge((array) $prevSessionData, $this->request->data);

                /**
                 * if this is not the last step we replace session data with the new merged array
                 * update the max progress value and redirect to the next step
                 */
                if ($stepNumber < $this->Session->read('form.params.steps')) {
                    $this->Session->write('form.data', $currentSessionData);
                    $this->Session->write('form.params.maxProgress', $stepNumber);
                    $this->redirect(array('action' => 'msf_step', $stepNumber + 1));
                } else {
                    /**
                     * otherwise, this is the final step, so we have to save the data to the database
                     */
                    $this->Survey->set(array(
                        'Name' => $currentSessionData['Name'],
                        'Email'=> $currentSessionData['Email']
                    ));
                    $this->Survey->save();
                    $Surveyid = $this->Survey->getLastInsertId();
                    $number = $currentSessionData['numberOfQues'];
                    for ($i = 1; $i <= $number; $i++) {
                        $question = $currentSessionData['Question' . $i];
                        $in['Question'] = array(
                            'Question' => $question,
                            'Survey_id' => $Surveyid,
                            'Yes_id' => null,
                            'No_id' => null
                        );

                        if ($this->Question->save($in)) {
                            $questionId = $this->Question->getLastInsertId();
                        } else {
                            debug($this->Question->validationErrors);
                            die();
                        }
                        $questionId = $this->Question->getLastInsertId();
                        $this->Question->clear();
                        $quesId[$i] = $questionId;
                    }
                    for ($i = 1; $i <= $number; $i++) {
                        //$q = $this->Question->findById($quesId[$i]);
                        $yy = $currentSessionData['Yes' . $i];
                        if ($yy != 0)
                            $y = $this->Question->findById($quesId[$yy]);
                        else
                            $y = null;
                        $nn = $currentSessionData['No' . $i];
                        if ($nn != 0)
                            $n = $this->Question->findById($quesId[$nn]);
                        else
                            $n = null;
                        $this->loadModel('Question');
                        //$this->Question->set($q);
                        $this->Question->Id = $quesId[$i];
                        $yes = ($y != null) ? $y["Question"]["Id"] : null;
                        $No =  ($n != null) ? $n["Question"]["Id"] : null;
                        //
                        $this->Question->updateAll(array("Yes_id" => $yes, "No_id" => $No), array("Question.Id" => $quesId[$i]));
                        //$this->Question->saveField('Yes_id', ($y != null) ? $y["Question"]["Id"] : null);
                        //$this->Question->saveField('No_id', ($n != null) ? $n["Question"]["Id"] : null);
                    }
                    // $currentSessionData['Yes' . $i]
                    //$this->User->save($currentSessionData);
                    $this->Session->setFlash('Survey created Successfully!');
                    $this->redirect('/surveys/index');
                }
            }
        } else {
            $this->Session->delete('numberOfQuesValidationError');
            $this->request->data = $this->Session->read('form.data');
            $this->set('requestData', $this->request->data);
        }

        /**
         * validation
         */
        if ($this->request->is('post')) {
            if ($stepNumber == "1") {
                $this->set('validationErrorsArray', $this->Survey->validationErrors);
                $this->set('requestData', $this->request->data);
            } else if ($stepNumber == "2") {
                //$validation = $this->Question->validationErrors; 
                $this->set('validationErrorsArray', "please fill all the fields.");
                $this->set('requestData', $this->request->data);
            }
        }
        /**
         * here we load the proper view file, depending on the stepNumber variable passed via GET
         */
        $this->render('msf_step_' . $stepNumber);
    }

    function getsurveys()
    {
        $this->autoRender = false;
        $this->loadModel('Survey');
        $survies = $this->Survey->find('all', array('recursive' => 0));
        $counts = array();
        foreach ($survies as $survie) {
            $this->loadModel('Visitor');
            $options = array(
                'conditions' => array('Question.Survey_id' => $survie['Survey']['Id']),
                'recursive' => 0,
                'fields' => array('Visitor.Id'),
                'joins' => array(
                    'LEFT JOIN `responses` AS Response ON `Response`.`Visitor_id` = `Visitor`.`Id`',
                    'LEFT JOIN `questions` As Question ON `Response`.`Question_id` = `Question`.`Id`'
                ),
                'group' => '`Visitor`.`Id`',
            );
            $count = $this->Visitor->find('count', $options);
            if($count == false) $count =0;
            array_push($counts, array('survey' => $survie['Survey']['Name'] , 'count' => $count ));
        }
        echo json_encode($counts);
    }
}
