<?php

namespace App\Models;

use App\Models\Api;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
	//Рассылка всем подписавшимся
	public function SendSubcribeUser($userArrSubscribe) {
		$userArrSubscribe = DB::table("TelegrammUser")
			->join('TelegrammUserSubscribe', 'TelegrammUser.numeroUser', '=', 'TelegrammUserSubscribe.numeroUser')
			->get();
		$userArrSubscribe = $userArrSubscribe->toArray();
		$api = new Api();
		$valueTool = $api->GetTool("USD");
		foreach ($userArrSubscribe as $key => $value) {
			$url = "https://api.telegram.org/bot" . $this->key . "/sendMessage?chat_id=" . $value["numeroUser"] . '&text=' . $valueTool;

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
		$url = "https://api.telegram.org/bot" . $this->key . "/getUpdates";
		$response = $client->request('GET', $url);
		$arrayMessage = $response->getBody();
		$newUser = array();
		foreach ($arrayMessage as $key => $value) {
			if ($value["message"]['text'] == "\start") {
				$allMessageAndUserSubscribe[] = [
					"id" => $value["message"]["from"]["id"],
					"username" => $value["message"]["from"]["username"],
					"ferstName" => $value["message"]["from"]["first_name"],
					"lastName" => $value["message"]["from"]["last_name"],
					"message" => $value["message"]['text'],
				];
				$url = "https://api.telegram.org/bot" . $this->key . "/sendMessage?chat_id=" . $value["message"]["from"]["id"] . '&text=' . (string) $api->GetTool("USD");
				Tool::UpdateOrCreate(
					[
						"numeroUser" => $value["message"]["from"]["id"],
						'username' => $value["message"]["from"]["username"],
						'ferstName' => $value["message"]["from"]["first_name"],
						'lastName' => $value["message"]["from"]["last_name"],
						'Lastmessage' => $value["message"]['text'],
					]
				);
			}
		}
	}
	// Проходимся по всем сообщениям записываем только последние
	public function EndMessage() {
		$api = new Api();
		$client = $api->GetClient();
		$url = "https://api.telegram.org/bot" . $this->key . "/getUpdates";
		$response = $client->request('GET', $url);
		$arrayMessage = $response->getBody();
		$newUser = array();
		foreach ($arrayMessage as $key => $value) {
			Tool::UpdateOrCreate(
				[
					"numeroUser" => $value["message"]["from"]["id"],
					'username' => $value["message"]["from"]["username"],
					'ferstName' => $value["message"]["from"]["first_name"],
					'lastName' => $value["message"]["from"]["last_name"],
					'Lastmessage' => $value["message"]['text'],
				]
			);

		}
	}
	public function SubscribeUser() {
		$api = new Api();
		$client = $api->GetClient();
		$url = "https://api.telegram.org/bot" . $this->key . "/getUpdates";
		$response = $client->request('GET', $url);
		$arrayMessage = $response->getBody();
		foreach ($arrayMessage as $key => $value) {
			if ($value["message"]['text'] == "Subscribe") {
				Tool::UpdateOrCreate(
					[
						"numeroUser" => $value["message"]["from"]["id"],
						'username' => $value["message"]["from"]["username"],
						'ferstName' => $value["message"]["from"]["first_name"],
						'lastName' => $value["message"]["from"]["last_name"],
						'Lastmessage' => $value["message"]['text'],
					]
				);
				Tool::UpdateOrCreate(
					[
						"numeroUser" => $value["message"]["from"]["id"],
					]
				);
			}
		}
	}
	public function GetHistory() {
		$api = new Api();
		$client = $api->GetClient();
		$url = "https://api.telegram.org/bot" . $this->key . "/getUpdates";
		$arrayMessage = $response->getBody();
		$newUserHistory = array();
		foreach ($arrayMessage as $key => $value) {
			if ($value["message"]['text'] == "History") {
				$newUserHistory[] = $value["message"]["from"]["id"];
				Tool::UpdateOrCreate(
					[
						"numeroUser" => $value["message"]["from"]["id"],
						'username' => $value["message"]["from"]["username"],
						'ferstName' => $value["message"]["from"]["first_name"],
						'lastName' => $value["message"]["from"]["last_name"],
						'Lastmessage' => $value["message"]['text'],
					]
				);

			}
		}
		$arrUserGetHistory = DB::('TelegrammUser')->where("Lastmessage","=","History" )->get();
		$arrUserGetHistory = $arrUserGetHistory->toArray();
		$tool = tooluser::all();
		$tool = $tool->toArray();
		foreach ($newUserHistory as $key => $value) {
			$url = "https://api.telegram.org/bot" . $this->key . "sendMessage?chat_id=" . $value . "&text=";
			foreach ($tool as $keyl => $valuel) {
				$client->request('GET', $url.$valuel);
			}
		Tool::UpdateOrCreate(
					[
						"numeroUser" => $value["message"]["from"]["id"],
						'username' => $value["message"]["from"]["username"],
						'ferstName' => $value["message"]["from"]["first_name"],
						'lastName' => $value["message"]["from"]["last_name"],
						'Lastmessage' => "HistorySend",
					]
				);	
		}
	}
}
