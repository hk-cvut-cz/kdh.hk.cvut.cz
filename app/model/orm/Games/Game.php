<?php

namespace App;

use Clevis\Skeleton\Entity;


/**
 * @property string $name
 * @property string $url
 * @property string $status {enum self::getStatuses()}
 */
class Game extends Entity
{

	const AVAILABLE = 'available';
	const PURCHASED = 'purchased';
	const BROKEN = 'broken';
	const PROPOSED = 'proposed';

	/** @var GameMeta */
	private $meta;


	public static function getStatuses()
	{
		return [
			self::AVAILABLE => self::AVAILABLE,
			self::PURCHASED => self::PURCHASED,
			self::BROKEN => self::BROKEN,
			self::PROPOSED => self::PROPOSED,
		];
	}

	public function getMeta()
	{
		if (!$this->meta) {
			$obj = new GameMeta($this->url);
			$this->meta = $obj->getByGameUrl($this->url);
		}
		return $this->meta;
	}

	public function isAvailable()
	{
		return $this->status === self::AVAILABLE;
	}

	public function isBroken()
	{
		return $this->status === self::BROKEN;
	}

	public function isPurchased()
	{
		return $this->status === self::PURCHASED;
	}

	public function isProposed()
	{
		return $this->status === self::PROPOSED;
	}

}
