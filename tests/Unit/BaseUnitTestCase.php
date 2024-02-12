<?php

namespace Lexik\Bundle\CurrencyBundle\Tests\Unit;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Lexik\Bundle\CurrencyBundle\Tests\Fixtures\CurrencyData;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use PHPUnit\Framework\TestCase;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Configuration;

/**
 * Base unit test class providing functions to create a mock entity manger, load schema and fixtures.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
abstract class BaseUnitTestCase extends TestCase
{
    /**
     * Create the database schema.
     */
    protected function createSchema(EntityManager $em): void
    {
        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());
    }

    /**
     * Load test fixtures.
     */
    protected function loadFixtures(EntityManager $em): void
    {
        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);

        $executor->execute([new CurrencyData()], false);
    }

    /**
     * EntityManager mock object together with annotation mapping driver and
     * pdo_sqlite database in memory
     *
     * @return EntityManager
     */
    protected function getMockSqliteEntityManager()
    {
        // xml driver
        $xmlDriver = new SimplifiedXmlDriver([
            __DIR__.'/../../src/Resources/config/doctrine' => 'Lexik\Bundle\CurrencyBundle\Entity',
        ]);

        $config = new Configuration();
        $config->setMetadataDriverImpl($xmlDriver);
        $config->setProxyDir(sys_get_temp_dir());
        $config->setProxyNamespace('Proxy');
        $config->setLazyGhostObjectEnabled(true);

        $conn = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $connection = DriverManager::getConnection($conn, $config);

        return new EntityManager($connection, $config);
    }

    protected  function getMockDoctrine()
    {
        $em = $this->getMockSqliteEntityManager();

        $doctrine = $this->createMock(Registry::class);

        $doctrine
            ->method('getManager')
            ->willReturn($em);

        return $doctrine;
    }

    protected function getEntityManager()
    {
        return $this->doctrine->getManager();
    }
}
