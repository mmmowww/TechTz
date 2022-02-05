<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TelegrammUser extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('TelegrammUser', function (Blueprint $table) {
			$table->id();
			$table->integer("numeroUser");
			$table->string('username');
			$table->string('ferstName');
			$table->string('lastName');
			$table->string('Lastmessage'); // Хроним только последнее сообщение
			$table->timestamps();
		});
		// Хроним айдишники всех тех кто подписался на рассылку
		Schema::create('TelegrammUserSubscribe', function (Blueprint $table) {
			$table->id();
			$table->integer("numeroUser");
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('tooluser');
		Schema::dropIfExists('TelegrammUserSubscribe');
	}
}
