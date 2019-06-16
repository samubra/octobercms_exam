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
    public $rules = [
        'question_subject_id' => 'required',
        'question_description' => 'required',
        'question_type' => 'required',
        'answer' => 'required',
    ];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {
                //trace_sql();
                if (Question::where('question_tq_id', $data['question_tq_id'])->get()->count()){
                    $this->logSkipped($row, '该题目已存在，已经跳过！');
                }else{
                    $question = new Question();
                    $question->fill($data);
                    $question_type = $question->question_type;


                    $question->question_difficulty = isset($data['question_difficulty']) ? $data['question_difficulty'] : '1';
                    $question->question_enabled = isset($data['question_enabled']) ? $data['question_enabled'] : 1;
                    $question->question_auto_next = isset($data['question_auto_next']) ? $data['question_auto_next'] : 1;

                    if ($question->question_type === '5') {
                        $question->question_type = '1';
                    }
                    $question->save();
                    //trace_log($question->question_id);

                    $answerList = [];
                    if ($question_type === '5') {
                        $answerList = [
                            new Answer([
                                'answer_description' => '对',
                                'answer_isright' => $data['answer'] === 'A',
                                'answer_enabled' => '1',
                                'answer_question_id' => $question->question_id
                            ]),
                            new Answer([
                                'answer_description' => '错',
                                'answer_isright' => $data['answer'] === 'B',
                                'answer_enabled' => '1',
                                'answer_question_id' => $question->question_id
                            ]),
                        ];
                    } else {
                        $answerList = [
                            new Answer([
                                'answer_description' => $data['answer_one'],
                                'answer_isright' => \Str::contains($data['answer'], 'A'),
                                'answer_enabled' => '1',
                                'answer_question_id' => $question->question_id
                            ]),
                            new Answer([
                                'answer_description' => $data['answer_two'],
                                'answer_isright' => \Str::contains($data['answer'], 'B'),
                                'answer_enabled' => '1',
                                'answer_question_id' => $question->question_id
                            ]),
                            new Answer([
                                'answer_description' => $data['answer_three'],
                                'answer_isright' => \Str::contains($data['answer'], 'C'),
                                'answer_enabled' => '1',
                                'answer_question_id' => $question->question_id
                            ]),
                        ];
			if(isset($data['answer_four'])){
				$answerList[] = new Answer([
                                'answer_description' => $data['answer_four'],
                                'answer_isright' => \Str::contains($data['answer'], 'D'),
                                'answer_enabled' => '1',
                                'answer_question_id' => $question->question_id
                            ]);
			}
			if(isset($data['answer_five'])){
                                $answerList[] = new Answer([
                                'answer_description' => $data['answer_five'],
                                'answer_isright' => \Str::contains($data['answer'], 'E'),
                                'answer_enabled' => '1',
                                'answer_question_id' => $question->question_id
                            ]);
                        }
			if(isset($data['answer_six'])){
                                $answerList[] = new Answer([
                                'answer_description' => $data['answer_six'],
                                'answer_isright' => \Str::contains($data['answer'], 'F'),
                                'answer_enabled' => '1',
                                'answer_question_id' => $question->question_id
                            ]);
                        }
                    }

                    //$question->answers()->addMany($answerList);

                    foreach ($answerList as $answer) {
                        $answer->save();
                    }
                    $this->logCreated();

                }
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
		

        }
    }
}
