<?php

namespace Contributte\Logging\Sentry;

use Contributte\Logging\ILogger;
use Eshop\Core\Exception\EshopException;
use Exception;
use Nette\Application\BadRequestException;
use Raven_Client;

final class SentryLogger implements ILogger
{

	/** @var array */
	private $config;

	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @param string|Exception $message
	 * @param string $priority
	 * @return void
	 */
	public function log($message, $priority):void
	{
		if (!in_array($priority, [ILogger::ERROR, ILogger::EXCEPTION, ILogger::CRITICAL], true))
			return;
		if (!($message instanceof Exception))
			return;
		if ($message instanceof BadRequestException)
			return;
		if ($message instanceof \Eshop\Core\Exception\BadRequestException)
			return;
//		if ($message instanceof EshopException)
//			return;

		// Send to Sentry
		$this->makeRequest($message);
	}

	/**
	 * @param Exception $message
	 * @return void
	 */
	protected function makeRequest(Exception $message)
	{
		$client = new Raven_Client($this->config['url']);
		$client->captureException($message);
	}

}
