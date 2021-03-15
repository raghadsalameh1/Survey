<?php
class ResponsesController extends AppController
{
    var $components = array('Export.Export');
    var $name = "Responses";
    function view_response($vistitorid)
    {
        $this->loadModel('Response');
        $allresponses = $this->Response->find('all', array(
            'conditions' => array('Response.Visitor_id' => $vistitorid)
        ));
        $this->loadModel('Note');
        $allnotes = $this->Note->find('all', array(
            'conditions' => array('Note.Visitor_id' => $vistitorid)
        ));
        $this->set('responses', $allresponses );
        $this->set('notes', $allnotes);
    }

    function survey_submission($surveyid)
    {
        $this->loadModel('Visitor');
        $options = array(
            'conditions' => array('Question.Survey_id' => $surveyid,),
            'recursive' => 0,
            'fields' => array('Visitor.Id', 'Visitor.Vdate'),
            'joins' => array(
                'LEFT JOIN `responses` AS Response ON `Response`.`Visitor_id` = `Visitor`.`Id`',
                'LEFT JOIN `questions` As Question ON `Response`.`Question_id` = `Question`.`Id`'),
            'group' => '`Visitor`.`Id`',
        );
        //'contain' => array('Domain' => array('fields' => array('title')))
        $submitions = $this->Visitor->find('all', $options);
        $this->set('submitions',$submitions);
    }

    function export_submission($surveyid,$surveyName)
    {
        $this->autoRender = false;
        $this->loadModel('Visitor');
        $options = array(
            'conditions' => array('Question.Survey_id' => $surveyid,),
            'recursive' => 1 ,
            'fields' => array('Visitor.Id', 'Visitor.Vdate'),
            'joins' => array(
                'LEFT JOIN `responses` AS Response ON `Response`.`Visitor_id` = `Visitor`.`Id`',
                'LEFT JOIN `questions` As Question ON `Response`.`Question_id` = `Question`.`Id`'
            ),
            'group' => '`Visitor`.`Id`',
        );

        // 'contain' => array('Domain' => array('fields' => array('Response','Note'))),
        $submitions = $this->Visitor->find('all', $options);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Submition.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('SurveyID', $surveyid));
        fputcsv($output, array('SurveyName', $surveyName));
        foreach ($submitions as $submition) {
            fputcsv($output, array());
            fputcsv($output, array('SubmitionId' , $submition['Visitor']['Id']));
            fputcsv($output, array('SubmitionDate' , $submition['Visitor']['Vdate']));
            fputcsv($output, array());
            fputcsv($output, array('Question', 'Answer'));
            foreach ($submition['Response'] as $response) {
                $r = ($response['Response'] == true) ? 'Yes' : 'No';
                $this->loadModel('Question');
                $q = $this->Question->findById($response['Question_id'], array('recursive' => 0,));
                fputcsv($output, array($q['Question']['Question'] , $r ));      
            }
            fputcsv($output, array('Notes'));
            foreach ($submition["Note"] as $note) {
                fputcsv($output, array($note['Note']));
            }
        }
        fclose($output);
        //$this->Export->exportCsv($submitions, 'submitions.csv');
    }
}