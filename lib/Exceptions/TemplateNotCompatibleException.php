<?php
/**
 * CMS Pico - Create websites using Pico CMS for Nextcloud.
 *
 * @copyright Copyright (c) 2019, Daniel Rudolf (<picocms.org@daniel-rudolf.de>)
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace OCA\CMSPico\Exceptions;

class TemplateNotCompatibleException extends \Exception
{
	/** @var string */
	private $templateName;

	/** @var string */
	private $reason;

	/** @var array */
	private $reasonData;

	/**
	 * PluginNotCompatibleException constructor.
	 *
	 * @param string $templateName
	 * @param string $reason
	 * @param array  $reasonData
	 */
	public function __construct(string $templateName, string $reason = "", array $reasonData = [])
	{
		$this->templateName = $templateName;
		$this->reason = $reason;
		$this->reasonData = $reasonData;

		$parsedReason = $this->getReason() ?: 'Incompatible template';
		$message = sprintf("Unable to load template '%s': %s", $templateName, $parsedReason);

		parent::__construct($message);
	}

	/**
	 * @return string
	 */
	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	/**
	 * @return string
	 */
	public function getReason(): string
	{
		if (!$this->reason) {
			return '';
		}

		$reasonData = $this->reasonData;
		$replaceCallback = function (array $matches) use ($reasonData) {
			return $reasonData[$matches[1]] ?? '';
		};

		return preg_replace_callback('/{([^{}]*)}/', $replaceCallback, $this->reason) ?: '';
	}

	/**
	 * @return string
	 */
	public function getRawReason(): string
	{
		return $this->reason;
	}

	/**
	 * @return array
	 */
	public function getRawReasonData(): array
	{
		return $this->reasonData;
	}
}
