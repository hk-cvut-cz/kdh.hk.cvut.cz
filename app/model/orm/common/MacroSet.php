<?php

namespace Orm;

use Nette;
use Orm;


/**
 * Mysql SET. Funguje jako enum, ale umoznuje vice hodnot.
 *
 * <code>
 *
 * 	/**
 * 	 * @property Sim\Model\MacroSet $types {set self::TYPE_A, self::TYPE_B, self::TYPE_C}
 * 	 * /
 * 	class Foo extends Sim\Model\Entity
 *
 * 	$foo->types = array(self::TYPE_A, self::TYPE_C);
 * 	$foo->types->remove(self::TYPE_C);
 * 	$foo->types->add(self::TYPE_B);
 * 	$foo->types->has(self::TYPE_A) === true;
 * 	foreach ($foo->types as $type);
 *
 * </code>
 */
class MacroSet extends Nette\Object implements Orm\IEntityInjection, \Countable, \IteratorAggregate
{

	/** @var Orm\IEntity */
	private $entity;

	/** @var string */
	private $propertyName;

	/** @var array */
	private $permittedValues;

	/** @var string */
	private $permittedOriginalValues;

	/** @var array */
	private $values = array();

	/**
	 * @param Orm\IEntity
	 * @param string
	 * @param array scalar => scalar
	 * @param string
	 * @param array|string
	 */
	public function __construct(Orm\IEntity $entity, $propertyName, array $permittedValues, $permittedOriginalValues, $initValues = NULL)
	{
		$this->entity = $entity;
		$this->propertyName = $propertyName;
		$this->permittedValues = $permittedValues;
		$this->permittedOriginalValues = $permittedOriginalValues;
		if ($initValues !== NULL AND $initValues !== '')
		{
			$this->setInjectedValue($initValues);
		}
	}

	/**
	 * Hodnota ktera se bude ukladat do uloziste.
	 * @return string
	 */
	public function getInjectedValue()
	{
		return implode(',', $this->values);
	}

	/**
	 * To co prijde od uzivatele nebo z uloziste.
	 * @param array|string
	 * @return MacroSet $this
	 */
	public function setInjectedValue($values)
	{
		if (!is_array($values))
		{
			$values = explode(',', $values);
		}
		$this->values = array();
		array_map(array($this, 'add'), $values);
		return $this;
	}

	/**
	 * @param scalar
	 * @return MacroSet $this
	 */
	public function add($value)
	{
		$this->valid($value);
		$this->values[$value] = $value;
		return $this;
	}

	/**
	 * @param scalar
	 * @return MacroSet $this
	 */
	public function remove($value)
	{
		$this->valid($value);
		unset($this->values[$value]);
		return $this;
	}

	/**
	 * @param scalar
	 * @return bool
	 */
	public function has($value)
	{
		$this->valid($value);
		return isset($this->values[$value]);
	}

	/**
	 * @param scalar
	 * @return void
	 * @throws Orm\NotValidException
	 */
	protected function valid($value)
	{
		if (in_array($value, $this->permittedValues, true))
		{
		}
		else if (($tmp = array_search($value, $this->permittedValues)) !== false)
		{
			$value = $this->permittedValues[$tmp];
		}
		else
		{
			throw new Orm\NotValidException(array($this->entity, $this->propertyName, $this->permittedOriginalValues, $value));
		}
	}

	/** @return int */
	public function count()
	{
		return count($this->values);
	}

	/** @return \ArrayIterator */
	public function getIterator()
	{
		return new \ArrayIterator($this->values);
	}

}
