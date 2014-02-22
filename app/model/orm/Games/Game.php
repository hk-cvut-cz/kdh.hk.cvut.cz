<?php

namespace App;

use Clevis\Skeleton\Entity;
use Clevis\Users\User;
use Nette\DateTime;
use dflydev\markdown\MarkdownExtraParser as Markdown;


/**
 * @property string $name
 * @property string $url
 * @property string $status {enum self::getStatuses()}
 *
 * @property string $tags
 * @property string $players (ex 2-5)
 * @property string $time (ex 100 min)
 * @property int $published (ex 2012)
 * @property string $publisher
 * @property double $rating
 * @property string $cover url
 * @property string $text markdown
 * 
 * @property Orm\OneToMany $reservations {1:m App\ReservationsRepository $game}
 * @property Orm\OneToMany $votes {1:m App\VotesRepository $game}
 * @property Orm\ManyToMany $playersSearching {m:m Clevis\Users\UsersRepository $gamesSearching}
 */
class Game extends Entity
{

	const AVAILABLE = 'available';
	const PURCHASED = 'purchased';
	const BROKEN = 'broken';
	const PROPOSED = 'proposed';

	/** @var mixed */
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
			$this->meta = $this->repository->getMetaRepository()->getByGameUrl($this->url);
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

	public function reserve(User $user, DateTime $date)
	{
		if (!$this->isAvailable())
		{
			throw new ReservationException('Hra se momentálně nedá vypůjčit.');
		}

		$reservation = $this->reservations->add([
			'user' => $user,
			'date' => $date,
		]);

		try {
			$this->model->flush();
		}
		catch (\DibiDriverException $e)
		{
			if ($e->getCode() !== 1062) {
				throw $e;
			} else {
				throw new ReservationException("Hra už je na datum " . $date->format('j. n. Y') . " zarezervovaná.");
			}
		}
	}

	public function vote(User $user)
	{
		$this->votes->add([
			'user' => $user,
		]);
		$this->model->flush();
	}

	public function getUpcomingReservations()
	{
		return $this->reservations->get()->where('date >= Date(Now())');
	}

	public function getTextAsHtml()
	{
		$md = new Markdown;
		return $md->transformMarkdown($this->getValue('text'));
	}

}
