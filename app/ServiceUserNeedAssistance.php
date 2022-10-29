<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;

class ServiceUserNeedAssistance extends Authenticatable
{
    use Notifiable;
    protected $table = 'su_need_assistance';
}