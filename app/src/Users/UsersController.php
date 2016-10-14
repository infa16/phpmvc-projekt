<?php

namespace Anax\Users;

/**
 * To manage users
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
    }

    /**
     * List all users.
     *
     * @return void
     */
    public function manageAction($filter = "all")
    {
        $this->initialize();

        switch ($filter) {
            case "active":
                $users = $this->users->query()
                    ->where('active IS NOT NULL')
                    ->andWhere('deleted is NULL')
                    ->execute();
                break;
            case "inactive":
                $users = $this->users->query()
                    ->where('active IS NULL')
                    ->andWhere('deleted is NULL')
                    ->execute();
                break;
            case "deleted":
                $users = $this->users->query()
                    ->where('deleted is NOT NULL')
                    ->execute();
                break;
            case "all":
            default:
                $filter = "all";
                $users = $this->users->findAll();
                break;
        }

        $this->views->add('users/manage', [
            'users' => $users,
            'title' => "Användare",
            'filter' => $filter
        ]);
    }

    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        $this->initialize();

        $user = $this->users->find($id);

        $this->theme->setTitle("Användare");
        $this->views->add('users/view', [
            'title' => "Användarinformation",
            'user' => $user,
        ]);
    }

    /**
     * Add new user.
     *
     * @param string $acronym of user to add.
     *
     * @return void
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
        if (!isset($id)) {
            die("Missing id");
        }
        $user = $this->users->find($id);
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
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
        $res = $this->users->delete($id);
        $url = $this->url->create('users');
        $this->response->redirect($url);
    }

    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $now = gmdate('Y-m-d H:i:s');

        $user = $this->users->find($id);

        $user->deleted = $now;
        $user->save();
        $this->response->redirect($this->url->create('users'));
    }

    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function unDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
        $user = $this->users->find($id);

        $user->deleted = null;
        $user->save();
        $this->response->redirect($this->url->create('users'));
    }

    public function setupAction()
    {
        $this->di->db->dropTableIfExists('User')->execute();
        $this->di->db->createTable(
            'User',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'acronym' => ['varchar(20)', 'unique', 'not null'],
                'email' => ['varchar(80)'],
                'name' => ['varchar(80)'],
                'password' => ['varchar(255)']                
            ]
        )->execute();
        $this->di->db->insert(
            'User',
            ['acronym', 'email', 'name', 'password']
        );

        $now = gmdate('Y-m-d H:i:s');

        $this->di->db->execute([
            'admin',
            'admin@dbwebb.se',
            'Administrator',
            password_hash('admin', PASSWORD_DEFAULT),
            $now,
            $now
        ]);

        $this->di->db->execute([
            'doe',
            'doe@dbwebb.se',
            'John/Jane Doe',
            password_hash('doe', PASSWORD_DEFAULT),
            $now,
            $now
        ]);

        $content = $this->fileContent->get('setup.md');
        $content = $this->textFilter->doFilter($content, 'shortcode, markdown');
        $this->views->add('me/page', ['content' => $content]);
    }
}
