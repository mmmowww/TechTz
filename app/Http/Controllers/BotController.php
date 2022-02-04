<?php

namespace App\Http\Controllers;
use App\Models\Api;
use App\Models\TelegramBot;

class BotController extends Controller {
	public function createNewWrite() {
		$bot = new TelegramBot();
		$bot->Send();
		$api = new Api();
		//$api->GetWriteTable("USD");
		//dd($api->GetTool("USD"));
	}
}
