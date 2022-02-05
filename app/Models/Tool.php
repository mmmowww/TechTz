<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model {
	protected $table = 'TelegrammUser';
	protected $fillable = [
		"numeroUser",
		'username',
		'ferstName',
		'lastName',
		'Lastmessage',
	];
	use HasFactory;
}
