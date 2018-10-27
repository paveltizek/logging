<?php declare(strict_types = 1);

namespace Contributte\Logging\Slack\Formatter;

use Contributte\Logging\Exceptions\Logical\InvalidStateException;
use Contributte\Logging\ILogger;
use Exception;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
final class ColorFormatter implements IFormatter
{

	/**
	 * @param string|Exception $message
	 * @param string $priority
	 */
	public function format(SlackContext $context, $message, $priority): SlackContext
	{
		switch ($priority) {
			case ILogger::ERROR:
				$color = 'warning';
				break;
			case ILogger::EXCEPTION:
				$color = '#ff0000';
				break;
			case ILogger::CRITICAL:
				$color = 'danger';
				break;
			default:
				throw new InvalidStateException(sprintf('Unsupported priority "%s".', $priority));
		}

		$context = clone $context;
		$context->setColor($color);

		return $context;
	}

}
