<?php

namespace Anax\Questions;

class AnswerForm extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionAware;
    use \Anax\MVC\TRedirectHelpers;

    public function __construct($answer = null)
    {
        parent::__construct([], [

            
            'content' => [
                'type' => 'text',
                'value' => null,
                'label' => 'Svar:',
                'required' => true,
                'validation' => ['not_empty']
            ],
            'createdTime' => [
                'type' => 'date',
                'value' => null,
            ],
            'createdBy' => [
                'type' => 'text',
                'value' => null,
            ],
            'vote' => [
                'type' => 'int',
                'value' => null
            ]
        ]);

    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        $now = gmdate('Y-m-d H:i:s');
        $user = new \Anax\Users\User();
        $questions = new \Anax\Questions\Question();
        $questions->setDI($this->di);

        $result = $questions->save([
            'id' => empty($this->user) ? null : $this->user->id,
            'header' => $this->value('header'),
            'content' => $this->value('content'),

            'createdBy' => $user->value('name'),

            'createdTime' => empty($this->questions) ? $now : $this->questions->createdTime,
            'vote' => $this->value('vote'),
        ]);
        return $result;
    }


}