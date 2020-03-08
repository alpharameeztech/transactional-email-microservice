<?php

namespace App\Interfaces\EmailInterface;

interface Email
{
    public function send($data);

    public function log($data);
}
