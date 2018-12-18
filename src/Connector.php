<?php

namespace Hatamiarash7\Nazer;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Connector
{
	protected $os = "";
	protected $device = "";
	protected $browser = "";

	public function sendException(Exception $e)
	{
		try {
			DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
			$device = new DeviceDetector($userAgent);
			$device->discardBotInformation();
			$device->parse();

			if ($device->isBot()) {
				$this->device = "bot";
			} else {
				$this->os = $device->getOs();
				$this->browser = $this->parseUserAgent($device->getUserAgent());
				$this->device = $device->getDevice();
			}

			$client = new Client();
			$client->request(
				'POST',
				'http://n.arash-hatami.ir/api/laravel/exception',
				[
					'json' => [
						'api' => config('nazer.api-key'),
						'app' => config('nazer.app-id'),
						'message' => $e->getMessage(),
						'line' => $e->getLine(),
						'trace' => $e->getTraceAsString(),
						'file' => $e->getFile(),
						'os' => $this->os,
						'browser' => $this->browser,
						'device' => $this->device,
					]
				]);
		} catch (GuzzleException $ignore) {
		}
	}

	private function parseUserAgent($userAgent)
	{
		if (stristr($userAgent, 'Firefox'))
			return "Firefox";
		elseif (stristr($userAgent, 'Opera'))
			return "Opera";
		elseif (stristr($userAgent, 'Chrome'))
			return "Chrome";
		else
			return "Other";
	}
}