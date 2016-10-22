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

        if (empty($user)) {
            $elements = [
                'Username' => [
                    'type' => 'text',
                    'value' => null,
                    'label' => 'Användarnamn:',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'readonly' => !empty($user),
                ],
                'Name' => [
                    'type' => 'text',
                    'value' => null,
                    'label' => 'Namn:',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'readonly' => !empty($user),
                ],
                'Email' => [
                    'type' => 'text',
                    'value' => null,
                    'label' => 'E-mail:',
                    'required' => true,
                    'validation' => ['not_empty', 'email_adress'],
                ],
                'Password' => [
                    'type' => 'password',
                    'value' => null,
                    'label' => 'Lösenord:',
                    'required' => true,
                    'validation' => ['not_empty'],
                ],
                'submit' => [
                    'type' => 'submit',
                    'callback' => [$this, 'callbackSubmit'],
                    'value' => 'Spara',
                ],
            ];
        } else {
            $elements = [
                'Name' => [
                    'type' => 'text',
                    'value' => htmlentities($user->Name),
                    'label' => 'Namn:',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'readonly' => false,
                ],
                'Email' => [
                    'type' => 'text',
                    'value' => htmlentities($user->Email),
                    'label' => 'E-mail:',
                    'required' => true,
                    'validation' => ['not_empty', 'email_adress'],
                ],
                'Password' => [
                    'type' => 'password',
                    'value' => null,
                    'label' => 'Lösenord (lämna tomt för att behålla nuvarande):',
                    'required' => false,
                ],
                'submit' => [
                    'type' => 'submit',
                    'callback' => [$this, 'callbackSubmit'],
                    'value' => 'Spara',
                ]
            ];
        }

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
        $users = new \Anax\User\UserModel();
        $users->setDI($this->di);
        if (empty($this->user)) {
            $result = $users->save([
                'Id' => null,
                'Username' => $this->value('Username'),
                'Name' => $this->value('Name'),
                'Email' => $this->value('Email'),
                'Password' => password_hash($this->value('Password'), PASSWORD_DEFAULT),
                'CreatedTime' => $now,
            ]);
        } else {
            $values = [
                'Id' => $this->user->Id,
                'Name' => $this->value('Name'),
                'Email' => $this->value('Email'),
            ];

            $password = $this->value('Password');
            if (!empty($password)) {
                $values['Password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $result = $users->save($values);
        }
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
