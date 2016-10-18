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
            'url' => $this->di->get('url')->create('login'),
            'title' => 'Logga in',
            'submenu' => [
                'items' => [
                    'tema' => [
                        'text' => 'Profil',
                        'url' => $this->di->get('url')->create('regioner'),
                        'title' => 'Ett tema med LESS'],
                    'typography' => [
                        'text' => 'Skapa användare',
                        'url' => $this->di->get('url')->create('typography'),
                        'title' => 'Typografi-test'],
                    'font-awesome' => [
                        'text' => 'Logga in/ut',
                        'url' => $this->di->get('url')->create('font-awesome'),
                        'title' => 'Font-awesome'],
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
