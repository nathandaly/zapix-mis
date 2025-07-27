<?php

Route::get('/', static function () {
    return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
});
