<?php

namespace Anax\User;

/**
 * To manage users
 *
 */
class UserController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    use \Anax\MVC\TRedirectHelpers;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->model = new \Anax\User\UserModel();
        $this->model->setDI($this->di);
    }

    /**
     * List all users.
     *
     * @return void
     */
    public function listAction()
    {
        $users = $this->model->findAll();
        $this->di->views->add('users/list', [
            'users' => $users
        ], 'full');
    }

    /**
     * List user with id.
     *
     * @param null $userId
     * @internal param int $id of user to display
     */
    public function idAction($userId = null)
    {
        $user = $this->model->find($userId);

        $this->db
            ->select('q.Id, q.Title, 
             (SELECT count(*) 
             FROM wgtotw_Answer a 
             WHERE a.QuestionId = q.Id) AS AnswerCount')
            ->from('Question q')
            ->where('q.CreatedBy = ?')
            ->execute([$userId]);
        $asked = $this->db->fetchAll();

        $this->db
            ->select('DISTINCT q.Id, q.Title')
            ->from('Question q')
            ->join('Answer a', 'a.QuestionId = q.Id AND a.CreatedBy = ?')
            ->execute([$userId]);
        $answered = $this->db->fetchAll();

        $this->theme->setTitle("Användare");
        $this->views->add('users/view', [
            'user' => $user,
            'asked' => $asked,
            'answered' => $answered
        ], 'full');
    }

    /**
     * Add new user.
     *
     * @internal param string $acronym of user to add.
     */
    public function addAction()
    {
        $form = new UserForm();
        $form->setDI($this->di);
        $form->check();
        $this->theme->setTitle("Skapa ny");
        $this->di->views->add('default/page', [
            'title' => "Skapa ny användare",
            'content' => $form->getHTML()
        ]);
    }

    public function updateAction($id)
    {
        if ((!$this->di->UserSession->isAuthenticated())) {
            $this->redirectTo($this->url->create('users'));
            return;
        }
        if ($this->di->UserSession->getId() != $id) {
            $this->redirectTo($this->url->create('users'));
            return;
        }

        $user = $this->model->find($id);
        $form = new UserForm($user);
        $form->setDI($this->di);
        $form->check();

        $this->theme->setTitle("Uppdatera");
        $this->di->views->add('default/page', [
            'title' => "Uppdatera användare",
            'content' => $form->getHTML()
        ]);
    }

    /**
     *  Checks if current user is logged in
     */
    public function currentAction()
    {
        if ($this->di->UserSession->isAuthenticated()) {
            $this->idAction($this->di->UserSession->getId());
        } else {
            $this->loginAction();
        }
    }

    public function loginAction()
    {
        $form = new \Anax\User\LoginForm();
        $form->setDI($this->di);
        $form->check();
        $this->theme->setTitle("Logga in");
        $this->di->views->add('users/login', [
            'title' => "Logga in",
            'content' => $form->getHTML()
        ], 'main');
    }

    public function logoutAction()
    {
        $session = $this->di->UserSession;
        if ($session->isAuthenticated()) {
            $session->logout();
        }
        $this->redirectTo('');
    }
}
