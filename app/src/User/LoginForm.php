<?php
/**
 * Created by PhpStorm.
 * User: Bergman
 * Date: 2016-10-19
 * Time: 20:29
 */

namespace Anax\User;


class LoginForm extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionAware;
    use \Anax\MVC\TRedirectHelpers;

    public function __construct($question = null)
    {
        parent::__construct([], [
            'user' => [
                'type' => 'text',
                'value' => null,
                'label' => 'Användarnamn:',
                'required' => true,
                'validation' => ['not_empty'],
            ],
            'password' => [
                'type' => 'password',
                'value' => null,
                'label' => 'Lösenord:',
                'required' => true,
                'validation' => ['not_empty']
            ],
            'submit' => [
                'type' => 'submit',
                'callback' => [$this, 'callbackSubmit'],
                'value' => 'Logga in',
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
        $user = $this->value('user');
        $password = $this->value('password');
        return $this->di->UserSession->login($user, $password);
    }

    /**
     * Callback success - Form gets processed
     */
    public function callbackSuccess()
    {
        $this->redirectTo('');
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->redirectTo('users/login');
    }
}