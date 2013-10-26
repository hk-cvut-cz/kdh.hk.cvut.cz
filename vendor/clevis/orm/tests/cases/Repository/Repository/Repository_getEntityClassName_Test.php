<?php

use Orm\RepositoryContainer;

/**
 * @covers Orm\Repository::getEntityClassName
 */
class Repository_getEntityClassName_Test extends TestCase
{
	private $r;

	protected function setUp()
	{
		$this->r = new Repository_getEntityClassNamesRepository(new RepositoryContainer, 'TestEntity');
	}

	public function testByProperty()
	{
		$this->r->entityClassName = 'Haha';
		$this->assertSame('Haha', $this->r->getEntityClassName());
		$this->assertSame('Haha', $this->r->getEntityClassName(array()));
	}

	public function testDefault()
	{
		$this->r->entityClassName = NULL;
		$this->assertSame('Repository_getEntityClassName', $this->r->getEntityClassName());
		$this->assertSame('Repository_getEntityClassName', $this->r->getEntityClassName(array()));
	}

	public function testNamespace()
	{
		if (PHP_VERSION_ID < 50300)
		{
			$this->markTestIncomplete('php 5.2 (namespace)');
		}
		$c = 'Repository_createMapper\Repository_createMapperRepository'; // aby nebyl parse error v php52
		$r = new $c(new RepositoryContainer);
		$r->entityClassName = NULL;
		$this->assertSame('Repository_createMapper\Repository_createMapper', $r->getEntityClassName());
		$this->assertSame('Repository_createMapper\Repository_createMapper', $r->getEntityClassName(array()));
	}

	public function testInflector()
	{
		$this->r->entityClassName = NULL;
		$h = new Repository_getEntityClassNames_RepositoryHelper;
		$this->r->getModel()->getContext()
			->removeService('repositoryHelper')
			->addService('repositoryHelper', $h)
		;
		$h->name = 'cities';
		$this->assertSame('city', $this->r->getEntityClassName());
		$this->assertSame('city', $this->r->getEntityClassName(array()));
	}

	public function testReflection()
	{
		$r = new ReflectionMethod('Orm\Repository', 'getEntityClassName');
		$this->assertTrue($r->isPublic(), 'visibility');
		$this->assertFalse($r->isFinal(), 'final');
		$this->assertFalse($r->isStatic(), 'static');
		$this->assertFalse($r->isAbstract(), 'abstract');
	}

}
