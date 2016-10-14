<?php
require __DIR__ . '/config_with_app.php';
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');

$di->set('form', '\Mos\HTMLForm\CForm');

$di->set('UsersController', function () use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->setShared('db', function () {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_mysql.php');
    $db->connect();
    return $db;
});

$app->router->add('', function () use ($app) {
    $app->theme->setTitle("Hem");

    $app->views
        ->add('start/start', [], 'full');
    /*
        $app->dispatcher->forward([
            'controller' => 'comment',
            'action'     => 'viewNewest',
            'params' => ["question", 4, "triptych-1"],
        ]);
        $app->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'viewMostReputation',
            'params' => [4, "triptych-2"],
        ]);
        $app->dispatcher->forward([
            'controller' => 'comment',
            'action'     => 'viewMostCommonTags',
            'params' => [4, "triptych-3"],
        ]);
     */
});

$app->router->add('questions', function () use ($app) {
    $app->theme->setTitle("Frågor");

    $app->views
        ->add('questions/list', [], 'main');
});

$app->router->add('tags', function () use ($app) {
    $app->theme->setTitle("Taggar");

    $app->views
        ->add('tags/view', [], 'full');
});

$app->router->add('users', function () use ($app) {
    $app->theme->setTitle("Användare");

    $app->views->add('users/allusers', [], 'full');
});

$app->router->add('about', function () use ($app) {
    $app->theme->setTitle("Om");
    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    $app->views->add('page', ['content' => $content]);
});

$app->router->add('login', function () use ($app) {
    $app->theme->setTitle("Logga in");
    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    $app->views->add('page', ['content' => $content]);
});

$app->router->add('users', function () use ($app) {
    $app->theme->setTitle("Användare");
    $app->dispatcher->forward(['controller' => 'users', 'action' => 'manage']);
});

$app->router->add('setup', function () use ($app) {
    $app->db->dropTableIfExists('Question')->execute();
    $app->db->createTable(
        'Question',
        [
            'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'Title' => ['varchar(100)', 'not null'],
            'Content' => ['text', 'not null'],
            'CreatedBy' => ['integer', 'not null'],
            'CreatedTime' => ['datetime', 'not null'],
            'Vote' => ['integer', 'not null']
        ]
    )->execute();

    $app->db->dropTableIfExists('Answer')->execute();
    $app->db->createTable(
        'Answer',
        [
            'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'QuestionId' => ['integer', 'not null'],
            'Content' => ['text', 'not null'],
            'CreatedBy' => ['integer', 'not null'],
            'CreatedTime' => ['datetime', 'not null'],
            'Vote' => ['integer', 'not null'],
            'Accepted' => ['boolean', 'not null']
        ]
    )->execute();

    $app->db->dropTableIfExists('Comment')->execute();
    $app->db->createTable(
        'Comment',
        [
            'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'ReferenceId' => ['integer', 'not null'], //Question.Id or Answer.Id
            'ReferenceType' => ['integer', 'not null'], //1 = Question; 2 = Answer
            'Content' => ['text', 'not null'],
            'CreatedBy' => ['integer', 'not null'],
            'CreatedTime' => ['datetime', 'not null'],
        ]
    )->execute();

    $app->db->dropTableIfExists('User')->execute();
    $app->db->createTable(
        'User',
        [
            'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'Username' => ['varchar(20)', 'not null'],
            'Name' => ['varchar(80)', 'not null'],
            'Email' => ['varchar(80)', 'not null'],
            'Password' => ['varchar(255)', 'not null'],
            'CreatedTime' => ['datetime', 'not null']
        ]
    )->execute();

    $app->db->dropTableIfExists('Tag')->execute();
    $app->db->createTable(
        'Tag',
        [
            'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'Tag' => ['varchar(80)', 'not null'],
            'Description' => ['varchar(255)', 'not null']
        ]
    )->execute();

    $app->db->dropTableIfExists('QuestionTagLink')->execute();
    $app->db->createTable(
        'QuestionTagLink',
        [
            'QuestionId' => ['integer', 'not null'],
            'TagId' => ['integer', 'not null'],
            'PRIMARY KEY (QuestionId, TagId)' => []
        ]
    )->execute();

    $app->db->dropTableIfExists('QuestionAnswerLink')->execute();
    $app->db->createTable(
        'QuestionAnswerLink',
        [
            'QuestionId' => ['integer', 'not null'],
            'AnswerId' => ['integer', 'not null'],
            'PRIMARY KEY (QuestionId, AnswerId)' => []
        ]
    )->execute();

    $app->db->insert(
        'User',
        ['Username', 'Name', 'Email', 'Password', 'CreatedTime']
    );

    $now = gmdate('Y-m-d H:i:s');

    $app->db->execute([
        'admin',
        'Administrator',
        'admin@dbwebb.se',
        password_hash('admin', PASSWORD_DEFAULT),
        $now
    ]);
    $adminId = $app->db->lastInsertId();

    $app->db->execute([
        'doe',
        'Jane Doe',
        'doe@dbwebb.se',
        password_hash('doe', PASSWORD_DEFAULT),
        $now
    ]);
    $doeId = $app->db->lastInsertId();


    $app->db->insert(
        'Question',
        ['Title', 'Content', 'CreatedBy', 'CreatedTime', 'Vote']
    );

    $app->db->execute([
        'Hur vet jag vilken evolution Eevee får?',
        'Kan jag på något sätt bestämma vilken typ Eevee blir efter att jag har evolvat den?',
        $adminId,
        $now,
        0
    ]);
    $evolutionId = $app->db->lastInsertId();

    $app->db->execute([
        'Vilken är den bästa att ha som Buddy?',
        'Vilken pokemon ska jag välja som buddy? Får man olika mycket godis beroende på hur mycket jag går?',
        $doeId,
        $now,
        0
    ]);
    $buddyId = $app->db->lastInsertId();

    //todo Add more questions!!

    
});


$app->router->handle();
$app->theme->render();
