<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribeUser extends Model {
	protected $table = 'TelegrammUserSubscribe';
	use HasFactory;
	protected $fillable = [
		"numeroUser",
		'username',
		'ferstName',
		'lastName',
		'Lastmessage',
	];
}
