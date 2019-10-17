<?php

use Flarum\Extend;
use Flarum\Foundation\Event\Validating;
use Flarum\User\UserValidator;
use Illuminate\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->listen(Validating::class, function (Validating $event){
        if ($event->type instanceof UserValidator) {

            $rules = $event->validator->getRules();
            if (isset($rules['username'])) {
                foreach ($rules['username'] as $k => $v) {
                    if (strpos($v, 'regex:') === 0) {
                        $rules['username'][$k] = 'regex:/^[-_a-zA-Z0-9\x7f-\xff]+$/i';
                    }
                    if (strpos($v, 'min:') === 0) {
                        $rules['username'][$k] = 'min:1';
                    }
                }
            }
            $event->validator->setRules($rules);
        }
    });
};