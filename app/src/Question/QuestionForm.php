<?php

namespace Anax\Question;

class QuestionForm extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionAware;
    use \Anax\MVC\TRedirectHelpers;

    private $model;
    private $tags;

    public function __construct($di)
    {
        $this->setDI($di);
        $this->model = new QuestionModel();
        $this->model->setDI($di);

        $elements = [
            'title' => [
                'type' => 'text',
                'value' => null,
                'label' => 'Rubrik:',
                'required' => true,
                'validation' => ['not_empty'],
            ],
            'content' => [
                'type' => 'textarea',
                'value' => null,
                'label' => 'Fråga:',
                'required' => true,
                'validation' => ['not_empty']
            ]
        ];

        $di->db
            ->select('*')
            ->from('Tag')
            ->execute();
        $this->tags = $di->db->fetchAll();

        foreach ($this->tags as $tag) {
            $elements["tag_" . $tag->Id] = [
                'type' => 'checkbox',
                'value' => null,
                'label' => $tag->Tag,
                'required' => false
            ];
        }

        $elements['submit'] = [
            'type' => 'submit',
            'callback' => [$this, 'callbackSubmit'],
            'value' => 'Spara',
        ];

        parent::__construct([], $elements);
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

        $result = $this->model->save([
            'Title' => $this->value('title'),
            'Content' => $this->value('content'),
            'CreatedBy' => $this->di->UserSession->getId(),
            'CreatedTime' => $now
        ]);

        if ($result) {
            $qtlm = new QuestionTagLinkModel();
            $qtlm->setDI($this->di);

            foreach ($this->elements as $element) {
                if ($element->attributes['type'] == 'checkbox'
                    && substr($element->attributes['name'], 0, 4) == "tag_"
                    && $element->attributes['checked']
                ) {
                    $qtlm->create([
                        'QuestionId' => $this->model->Id,
                        'TagId' => intval(str_replace('tag_', '', $element->attributes['name']))
                    ]);
                }
            }
        }

        return $result;
    }

    /**
     * Callback success - Form gets processed
     */
    public function callbackSuccess()
    {
        $this->redirectTo($this->di->url->create('questions/id/' . $this->model->Id));
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->redirectTo($this->di->url->create('questions'));
    }
}
