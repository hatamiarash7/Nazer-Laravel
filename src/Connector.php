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
			$client->request(
				'POST',
				'http://n.arash-hatami.ir/api/laravel/exception',
				[
					'json' => [
						'api' => config('NAZER_API'),
						'message' => $e->getMessage(),
						'line' => $e->getLine(),
						'trace' => $e->getTraceAsString(),
						'file' => $e->getFile(),
						'previous' => $e->getPrevious()
					]
				]);
		} catch (GuzzleException $ignore) {
		}
	}
}