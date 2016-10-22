<?php

namespace Anax\Question;

class AnswerForm extends \Mos\HTMLForm\CForm
{

    use \Anax\DI\TInjectionAware;
    use \Anax\MVC\TRedirectHelpers;

    private $questionId;

    public function __construct($questionId)
    {
        $this->questionId = $questionId;
        parent::__construct([], [
            'content' => [
                'type' => 'textarea',
                'value' => null,
                'label' => 'Svar:',
                'required' => true,
                'validation' => ['not_empty']
            ],
            'submit' => [
                'type' => 'submit',
                'callback' => [$this, 'callbackSubmit'],
                'value' => 'Spara',
            ],
        ]);
    }

    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail handler to call if function returns true.
     * @return bool|null
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        $now = gmdate('Y-m-d H:i:s');

        $answer = new AnswerModel();
        $answer->setDI($this->di);
        $result = $answer->save([
            'QuestionId' => $this->questionId,
            'Content' => $this->value('content'),
            'CreatedBy' => $this->di->UserSession->getId(),
            'CreatedTime' => $now
        ]);

        $this->answer = $answer;
        return $result;
    }

    /**
     * Callback success - Form gets processed
     */
    public function callbackSuccess()
    {
        $this->redirectTo($this->di->url->create('questions/id/' . $this->questionId));
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->redirectTo($this->di->url->create('questions/id/' . $this->questionId));
    }
}
