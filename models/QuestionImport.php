<?php
/**
 * Created by PhpStorm.
 * User: samub
 * Date: 2019/4/3
 * Time: 16:34
 */

namespace Samubra\Exam\Models;


class QuestionImport extends \Backend\Models\ImportModel
{

    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {
                $question = new Question();
                $question->fill($data);
                $question_type = $question->question_type;

                if($question->question_type === '5'){
                    $question->question_type = '1';
                }
                $question->save();

                $answerList = [];
                if($question_type === '5'){
                    $answerList = [
                        new Answer([
                            'answer_description'=>'对',
                            'answer_isright'=> $data['answer'] === '1',
                            'answer_enabled' => '1'
                        ]),
                        new Answer([
                            'answer_description'=>'错',
                            'answer_isright'=> $data['answer'] === '0',
                            'answer_enabled' => '1'
                        ]),
                    ];
                }else{
                    $answerList = [
                        new Answer([
                            'answer_description'=>$data['answer_one'],
                            'answer_isright'=> $data['answer'] === '1',
                            'answer_enabled' => '1'
                        ]),
                        new Answer([
                            'answer_description'=>$data['answer_two'],
                            'answer_isright'=> $data['answer'] === '2',
                            'answer_enabled' => '1'
                        ]),
                        new Answer([
                            'answer_description'=>$data['answer_three'],
                            'answer_isright'=> $data['answer'] === '3',
                            'answer_enabled' => '1'
                        ]),
                    ];
                }

                $question->answers()->addMany($answerList);


                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }
}