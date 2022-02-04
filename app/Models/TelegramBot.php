<?php

namespace App\Models;

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
		$this->bot = new \TelegramBot\Api\BotApi($_ENV["APP_KEYTELEGRAMBOT"]);
	}
	public function Send() {
		$bot = new \TelegramBot\Api\BotApi($_ENV["APP_KEYTELEGRAMBOT"]);
		dd("->", $this->key, $this->bot);
	}
}
