<?php

namespace Anax\User;

class UserForm extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionAware;
    use \Anax\MVC\TRedirectHelpers;

    private $user;

    /**
     * Constructor
     * @param array $user
     */
    public function __construct($user = null)
    {
        $this->user = $user;

        parent::__construct([], [
            'acronym' => [
                'type' => 'text',
                'value' => null,
                'label' => 'Användarnamn:',
                'required' => true,
                'validation' => ['not_empty'],
                'readonly' => !empty($user),
            ],
            'name' => [
                'type' => 'text',
                'value' => null,
                'label' => 'Namn:',
                'required' => true,
                'validation' => ['not_empty'],
            ],
            'email' => [
                'type' => 'text',
                'value' => null,
                'label' => 'E-mail:',
                'required' => true,
                'validation' => ['not_empty', 'email_adress'],
            ],
            'password' => [
                'type' => 'password',
                'value' => null,
                'label' => 'Lösenord:',
                'required' => true,
                'validation' => ['not_empty'],
            ],
            'created' => [
                'type' => 'date',
                'value' => null
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
        $users = new \Anax\Users\User();
        $users->setDI($this->di);
        $result = $users->save([
            'id' => empty($this->user) ? null : $this->user->id,
            'acronym' => $this->value('acronym'),
            'email' => $this->value('email'),
            'name' => $this->value('name'),
            'password' => password_hash($this->value('password'), PASSWORD_DEFAULT),
            'created' => empty($this->user) ? $now : $this->user->created,
        ]);
        return $result;
    }


    /**
     * Callback success - Form gets processed
     */
    public function callbackSuccess()
    {
        $this->redirectTo('users');
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->addOutput("<p><i>Något gick snett.</i></p>");
        $this->redirectTo();
    }
}

