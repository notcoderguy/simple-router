<?php

declare(strict_types=1);

namespace App\Handler;

class Contact
{
    public function __invoke()
    {
        $title = 'Contact';
        require __DIR__ . '/../../views/contact.phtml';
    }

    public function post($params)
    {
        var_dump($params);
    }
}