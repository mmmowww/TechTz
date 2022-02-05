<?php

namespace App\Http\Controllers;
use App\Models\Api;
use App\Models\TelegramBot;

class BotController extends Controller {
	private $Api;
	private $Bot;
	public function __construct() {
		$this->Api = new Api();
		$this->Bot = new TelegramBot();
	}
	//Дергать через крон раз в месяц
	public function createNewWrite($NameTool) {
		$api = $this->Api;
		$api->GetWriteTable($NameTool);
	}
	//Вызываем все сообщения
	public function getAllMessage() {
		$api = $this->Bot;
		$api->AddAllUsersAndMessage();
	}
	// Если нашли заносим в базу
	public function SearchSubscribeUser() {
		$bot = $this->Bot;
		$bot->SearchSubscribeUser();
	}
	public function SendSubcribeUser() {
		$bot = $this->Bot;
		$sUser = $bot->SearchSubscribeUser();
		$bot->SendSubcribeUser($sUser);
	}
}
