<?php

namespace Anax\User;

class UserSession
{
    use \Anax\DI\TInjectable;
    use \Anax\MVC\TRedirectHelpers;

    /**
     * 
     * 
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {
        $this->db
            ->select('Id, Username, Name, Password')
            ->from('User')
            ->where('Username = ?')
            ->execute([$username]);
        $res = $this->db->fetchAll();
        if (count($res) > 0 && password_verify($password, $res[0]->Password)) {
            $_SESSION['user'] = $res[0];
            unset($res[0]->Password);
            return true;
        } else {
            unset($_SESSION['user']);
            return false;
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }

    public function requireSession()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectTo('users/login');
        }
    }

    public function isAuthenticated()
    {
        $state = isset($_SESSION['user']) ? $_SESSION['user']->Id : null;
        return $state;
    }

    public function getId()
    {
        return $_SESSION['user']->Id;
    }

    public function getUsername()
    {
        return $_SESSION['user']->Username;
    }

    public function getName()
    {
        return $_SESSION['user']->Name;
    }
}
