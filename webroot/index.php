<?php
require __DIR__ . '/config_with_app.php';
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');

$di->set('form', '\Mos\HTMLForm\CForm');

$di->set('CommentController', function () use ($di) {
    $controller = new Anax\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

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
    $app->theme->setVariable('title', "Hem");
    $content = $app->fileContent->get('hem.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    $app->views->add('page', ['content' => $content]);
});


$app->router->handle();
$app->theme->render();
