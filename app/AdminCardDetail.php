<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Notifications\Notifiable;
//use Illuminate\Foundation\Auth\Admin as Authenticatable;
use Illuminate\Support\Facades\Mail;
use App\AdminCardDetail;

class AdminCardDetail extends Model
{	
	//use Notifiable;
    protected $table = 'admin_card_detail';
}


