<?php

namespace app\lib;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider {

    public function register() {
        
    }

    public function boot() {
        $this->app->validator->resolver(function($translator, $data, $rules, $messages) {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }

}
