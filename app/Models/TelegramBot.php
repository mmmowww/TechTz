<?php

namespace App\Models;

use App\Models\Api;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBot extends Model {
	use HasFactory;
	private $key;
	private $bot;
	public function __construct() {
		$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '\..\\..\\');
		$dotenv->load();
		$this->key = $_ENV["APP_KEYTELEGRAMBOT"];
	}
	// отправляем сообщение
	public function Send($numeroUser) {
		$litelArr = json_decode(file_get_contents("https://api.telegram.org/bot" . $this->key . "/getUpdates"), TRUE);
		//https://tlgrm.ru/docs/bots/api

	}
	//Собераем всех юзеров
	public function AddAllUsersAndMessage() {
		$responseArr = json_decode(file_get_contents("https://api.telegram.org/bot" . $this->key . "/getUpdates"), TRUE);
		if (isset($responseArr["result"])) {
			// Вызвать всех пользователей
			$allMessageAndUser = array();
			foreach ($responseArr["result"] as $key => $value) {
				// Проверяем есть ли пользователи
				$allMessageAndUser[] = [
					"id" => $value["message"]["from"]["id"],
					"username" => $value["message"]["from"]["username"],
					"ferstName" => $value["message"]["from"]["first_name"],
					"lastName" => $value["message"]["from"]["last_name"],
					"message" => $value["message"]['text'],
				];

			}

		}

		return $allMessageAndUser;

	}
	public function SearchSubscribeUser() {
		$responseArr = json_decode(file_get_contents("https://api.telegram.org/bot" . $this->key . "/getUpdates"), TRUE);
		if (isset($responseArr["result"])) {
			// Вызвать всех пользователей
			$allMessageAndUserSubscribe = array();
			foreach ($responseArr["result"] as $key => $value) {
				if ($value["message"]['text'] == "YES") {
					$allMessageAndUserSubscribe[] = [
						"id" => $value["message"]["from"]["id"],
						"username" => $value["message"]["from"]["username"],
						"ferstName" => $value["message"]["from"]["first_name"],
						"lastName" => $value["message"]["from"]["last_name"],
						"message" => $value["message"]['text'],
					];
				}

			}

		}

		return $allMessageAndUserSubscribe;
	}
	public function SendSubcribeUser($userArrSubscribe) {
		$arrValue = Tool::all();
		$arrValue = $arrValue->toArray();
		$api = new Api();
		$client = $api->GetClient();
		foreach ($userArrSubscribe as $key => $value) {
			$url = "https://api.telegram.org/bot" . $this->key . "/sendMessage?chat_id=" . $value["id"] . '&text=';
			foreach ($arrValue as $key => $value) {
				$url = $url . $value;
				$client->request('GET', $url);

			}
		}
	}
	// Деграть только тогда когда сообщений слишком много
	public function ClearArrayApiMessage() {
		$url = "https://api.telegram.org/bot" . $this->key;
		$request = new Request('GET', $url);
		$response = $client->send($request, ['timeout' => 2]);

	}
	// Если пользователя ещё нет в бд то отправляем ему сообщение
	public function NewUser() {
		$api = new Api();
		$client = $api->GetClient();
		$client->request('GET', $url);

	}

}
