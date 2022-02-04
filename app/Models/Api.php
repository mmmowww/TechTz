<?php

namespace App\Models;

use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model {
	use HasFactory;
	public function GetTool(string $nameTool) {
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', 'http://www.cbr.ru/scripts/XML_daily.asp');
		$xml = $response->getBody();
		$xml = simplexml_load_string($xml);
		$xml = $xml->Valute;
		foreach ($xml as $key => $value) {
			if ((string) $value->CharCode === (string) $nameTool) {
				return (double) str_replace(",", ".", $value->Value);
			}

		}

	}
	public function GetWriteTable(string $nameTool) {
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', 'http://www.cbr.ru/scripts/XML_daily.asp');
		$xml = $response->getBody();
		$xml = simplexml_load_string($xml);
		$xml = $xml->Valute;
		foreach ($xml as $key => $value) {
			if ((string) $value->CharCode === (string) $nameTool) {
				$writeTool = (double) str_replace(",", ".", $value->Value);
				$startDate = Carbon::today()->subDays(30);
				$endDate = Carbon::today()->addDays(30);
				$oneTool = Tool::whereMonth('created_at', $startDate->format('m'))
					->whereDay('created_at', '>=', $startDate->format('d'))
					->whereDay('created_at', '<=', $endDate->format('d'))
					->one();
				if ($oneTool === array()) {
					$oneTool = Tool::create([
						"name" => $nameTool,
						"value" => $writeTool,
					]);
				} else {
					$oneTool = Tool::update([
						"name" => $nameTool,
						"value" => $writeTool,
					]);
				}

			}

		}
	}
}
