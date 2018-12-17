<?php

namespace Hatamiarash7\Nazer;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Connector
{
	public function sendException(Exception $e)
	{
		try {
			$client = new Client();
			$client->requestAsync(
				'POST',
				'http://n.arash-hatami.ir/api/laravel/exception',
				[
					'json' => [
						'body' => $e->getMessage()
					]
				]);
		} catch (GuzzleException $ignore) {
		}
	}
}