<?php
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();

require __DIR__ . '/config_with_app.php';
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

$di->setShared('db', function () {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_mysql.php');
    $db->connect();
    return $db;
});

$di->setShared('UserSession', function () use ($di) {
    $session = new \Anax\User\UserSession();
    $session->setDI($di);
    return $session;
});

$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');

$di->set('form', '\Mos\HTMLForm\CForm');


$di->set('StartController', function () use ($di) {
    $controller = new \Anax\StartController();
    $controller->setDI($di);
    return $controller;
});

$di->set('UsersController', function () use ($di) {
    $controller = new \Anax\User\UserController();
    $controller->setDI($di);
    return $controller;
});

$di->set('QuestionsController', function () use ($di) {
    $controller = new \Anax\Question\QuestionController();
    $controller->setDI($di);
    return $controller;
});

$di->set('TagsController', function () use ($di) {
    $controller = new \Anax\Tag\TagController();
    $controller->setDI($di);
    return $controller;
});

$app->router->add('', function () use ($app) {
    $app->theme->setTitle("Hem");

    $app->dispatcher->forward([
        'controller' => 'Start',
    ]);
});

$app->router->add('questions', function () use ($app) {
    $app->theme->setTitle("Frågor");

    $app->dispatcher->forward([
        'controller' => 'Questions',
        'action' => 'list'
    ]);
});

$app->router->add('tags', function () use ($app) {
    $app->theme->setTitle("Taggar");

    $app->dispatcher->forward([
        'controller' => 'Tags',
        'action' => 'list'
    ]);
});

$app->router->add('users', function () use ($app) {
    $app->theme->setTitle("Användare");

    $app->dispatcher->forward([
        'controller' => 'Users',
        'action' => 'list'
    ]);
});

$app->router->add('about', function () use ($app) {
    $app->theme->setTitle("Om");
    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    $app->views->add('page', ['content' => $content]);
});

$app->router->add('setup', function () use ($app) {
    require __DIR__ . '/databaseSetup.php';
    $app->theme->setTitle("Databasen återställd");
    $app->views->add('page', ['content' => "Databasen återställd"]);
});


$app->router->handle();
$app->theme->render();
