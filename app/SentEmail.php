<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{

    protected $fillable = ['to', 'subject', 'message', 'service'];

    public function store($data)
    {
        return $this->create([
            'to' => $data['to'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'service' => config('mail.service')
        ]);
    }
}
