<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tooluser extends Model {
	protected $table = 'tooluser';
	protected $fillable = [
		"numeroUser",
	];
	use HasFactory;
}
