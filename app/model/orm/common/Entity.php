<?php

namespace Clevis\Skeleton;

use Clevis\Skeleton\Orm\EntityRelationsRegistry;
use Orm;
use Orm\AnnotationMetaData;


/**
 * Base class for all entities
 */
abstract class Entity extends Orm\Entity
{

	/** @var EntityRelationsRegistry */
	public static $relationsRegistry;


	public static function createMetaData($entityClass)
	{
		// $metaData = parent::createMetaData($entityClass);
		$metaData = AnnotationMetaData::getMetaData($entityClass);

		return self::$relationsRegistry->completeMetaData(get_called_class(), $metaData);
	}

}
