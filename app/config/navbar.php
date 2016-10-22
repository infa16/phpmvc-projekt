<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    'class' => 'navbar',

    'items' => [

        'home' => [
            'text' => 'Hem',
            'url' => $this->di->get('url')->create(''),
            'title' => 'Startsida'
        ],

        'questions' => [
            'text' => 'Frågor',
            'url' => $this->di->get('url')->create('questions'),
            'title' => 'Fråga på om PoGo',
        ],

        'tags' => [
            'text' => 'Taggar',
            'url' => $this->di->get('url')->create('tags'),
            'title' => 'Taggar',
        ],

        'users' => [
            'text' => 'Användare',
            'url' => $this->di->get('url')->create('users'),
            'title' => 'Användare',
        ],


        'about' => [
            'text' => 'Om',
            'url' => $this->di->get('url')->create('about'),
            'title' => 'Om sidan'
        ],

        'login' => [
            'class' => 'login',
            'text' => '<i class="fa fa-user fa-2x" aria-hidden="true"></i>',
            'url' => $this->di->get('url')->create('users/current'),
            'title' => '',
            'submenu' => [
                'items' => [
                    'profile' => [
                        'text' => '<i class="fa fa-user" aria-hidden="true"></i> Profil',
                        'url' => $this->di->get('url')->create('users/current'),
                        'title' => 'Profil'],
                    'adduser' => [
                        'text' => '<i class="fa fa-user-plus" aria-hidden="true"></i> Skapa användare',
                        'url' => $this->di->get('url')->create('users/add'),
                        'title' => 'Lägg till en ny användare'],
                    'login' => [
                        'text' => '<i class="fa fa-sign-in" aria-hidden="true"></i> '
                            . ($this->di->get('UserSession')->isAuthenticated() ? 'Logga ut' : 'Logga in'),
                        'url' => $this->di->get('UserSession')->isAuthenticated()
                            ? $this->di->get('url')->create('users/logout')
                            : $this->di->get('url')->create('users/login'),
                        'title' => $this->di->get('UserSession')->isAuthenticated() ? 'Logga ut' : 'Logga in'],
                ],
            ],
        ],
    ],


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
        return false;
    },

    /**
     * Callback to check if current page is a descendant of the menu-item, this check applies for those
     * menu-items that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },

    /**
     * Callback to create the url, if needed, else comment out.
     *
     */
    /*
     'create_url' => function ($url) {
         return $this->di->get('url')->create($url);
     },
     */
];
