<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    //set the route path to load the sent emails ui defaults to /sentemails
    'routepath'  => 'admin/sentemails',

    // set the route middlewares to apply on the sent emails ui
    'middleware' => ['web', 'auth'],

    // emails per page
    'perPage' => 10,

    'noEmailsMessage' => 'No emails found.'
];