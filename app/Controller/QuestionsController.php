<?php
App::uses('CakeEmail', 'Network/Email');
class QuestionsController extends AppController
{
    var $name = "Questions";
    public $components = array('RequestHandler');

    function take_survey($surveyid)
    {
        $this->Session->delete('qform');
        $this->loadModel('Survey');
        $su = $this->Survey->findById($surveyid);      
        $this->set('survey', $su);
        $q['q'] = $su["Question"][0]["Id"];
        $q['a'] = null;
        $this->Session->write('qform.n', array());
        $this->Session->write('qform.q', array($q));
        //$s = $su["Survey"]["Id"];
        $this->Session->write('qform.s', $su['Survey']);  
    }

    public function getquestionInfo ($id,$res)
    {
        $this->autoRender = false;
        $v = $this->Session->read('qform.q');
        $last_index = count($v)-1;
        $v[$last_index]['a'] = $res;
        if($id != "null" && $id !="-1")
        {
            $new['q'] = $id;
            array_push($v, $new);
            $this->Session->write('qform.q', $v);
            $this->loadModel('Question');
            $q = $this->Question->findById($id, array('recursive' => 0,));
            return json_encode($q['Question']);
        }
        else {
            $this->Session->write('qform.q', $v);
            return "done"; 
        }
        //Debugger::log('error', LOG_DEBUG, 4); 
        //return json_encode($q);
    }

    public function cache_note()
    {
        $this->autoRender = false;
        if ($this->request->isAjax())
        {
          $note = $this->request->data['note'];
            if (!empty($note)) {
                $notes = $this->Session->read('qform.n');
                if($notes == null)
                {
                  $notes = array();
                }
                array_push($notes, $note);
                $this->Session->write('qform.n',$notes);
            }
            echo "done";
        }
        else {
            echo "Something went wrong";
        }
      
    }

    function submit()
    {
        $survey = $this->Session->read('qform.s');  
        $responses = $this->Session->read('qform.q');
        $notes = $this->Session->read('qform.n');
        $this->loadModel('Visitor');
        $v = array('Vdate' => date("Y-m-d") , );
        /*$this->Visitor->set(array(
            'Vdate' => date("Y-m-d")
        ));
        $this->Visitor->save();*/
        $this->Visitor->save($v);
        $visitorid = $this->Visitor->getLastInsertId();
        foreach ($responses as $respons) {
            $re["Response"] = array('Visitor_id' => number_format($visitorid),
                'Question_id'=>  number_format($respons["q"]),
                'Response'=> number_format($respons["a"]),
             );
            $this->loadModel('Response');
            if ($this->Response->save($re)) {
                $this->Response->clear();
            } 
            else
            {
                debug($this->Response->validationErrors);
                die();
            }          
        }
        try {
            foreach ($notes as $note) {
                $n["Note"] = array(
                    'Visitor_id' => $visitorid,
                    'Note' =>  $note,
                );
                $this->loadModel('Note');
                if ($this->Note->save($n)) {
                    $this->Note->clear();
                } /*else {
            debug($this->Note->validationErrors);
            die();
        }*/
            }   
        } catch (\Throwable $th) {
            //throw $th;
        }
        $this->Session->delete('qform');

        /**
         * SEND EMAIL TO NOTIFY THE USER.
         */
        /*$this->loadModel('Survey');
        $survey = $this->Survey->findById($surveyid, array('recursive' => 0,));
        $this->Survey->clear();*/
        $for = $survey['Email'];
        $name = $survey['Name'];
        $Email = new CakeEmail('gmail');
        try {
            $Email->from(array('raghad.lonesystem@gmail.com' => 'Survey Site'))
            ->to($for)
                ->subject('A new survey has been taken')
                ->send('The ' . $name . ' survey has been taken. For more detail check the website.');
        } catch (\Throwable $th) {            //throw $th;
           
        }
        finally
        {
            $this->redirect(array('controller' => 'responses', 'action' => 'view_response', $visitorid));

        }

        //$this->sendEmail($surveyid);
    }
}