<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Tests\Storage\Operation;

use Phlexible\Bundle\IndexerBundle\Document\Document;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Phlexible\Bundle\IndexerBundle\Storage\Operation\Operations;
use Phlexible\Bundle\IndexerBundle\Storage\Operation\Operator;
use Phlexible\Bundle\QueueBundle\Entity\Job;
use Phlexible\Bundle\QueueBundle\Model\JobManagerInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TestDocument extends Document
{
    public function getName()
    {
        return 'test';
    }
}

/**
 * Operator test
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class OperatorTest extends \PHPUnit_Framework_TestCase
{
    /**
      * @var Operator
      */
    private $operator;

    /**
     * @var JobManagerInterface|ObjectProphecy
     */
    private $jobManager;

    /**
     * @var \Phlexible\Bundle\IndexerBundle\Storage\StorageInterface|ObjectProphecy
     */
    private $storage;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    protected function setUp()
    {
        $this->eventDispatcher = new EventDispatcher();
        $this->jobManager = $this->prophesize('Phlexible\Bundle\QueueBundle\Model\JobManagerInterface');
        $this->storage = $this->prophesize('Phlexible\Bundle\IndexerBundle\Storage\StorageInterface');
        $this->operator = new Operator($this->jobManager->reveal(), $this->eventDispatcher);
    }

    /**
     * @return Operations
     */
    private function createOperations()
    {
        return $this->operator->createOperations();
    }

    public function testRunWithCommitIsCalledForStorageWithCommitableInterface()
    {
        $this->storage->willImplement('Phlexible\Bundle\IndexerBundle\Storage\Commitable');
        $this->storage->commit()->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->commit());

        $this->assertTrue($result);
    }

    public function testQueueWithCommitIsCalled()
    {
        $job = new Job('indexer:index', array('--commit'));
        $this->jobManager->addJob($job)->shouldBeCalled();

        $result = $this->operator
            ->queue($this->createOperations()->commit());

        $this->assertTrue($result);
    }

    public function testRunWithRollbackIsCalledForStorageWithRollbackableInterface()
    {
        $this->storage->willImplement('Phlexible\Bundle\IndexerBundle\Storage\Rollbackable');
        $this->storage->rollback()->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->rollback());

        $this->assertTrue($result);
    }

    public function testQueueWithRollbackIsCalled()
    {
        $job = new Job('indexer:index', array('--rollback'));
        $this->jobManager->addJob($job)->shouldBeCalled();

        $result = $this->operator
            ->queue($this->createOperations()->rollback());

        $this->assertTrue($result);
    }

    public function testRunWithOptimizeIsCalledForStorageWithOptimizableInterface()
    {
        $this->storage->willImplement('Phlexible\Bundle\IndexerBundle\Storage\Optimizable');
        $this->storage->optimize()->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->optimize());

        $this->assertTrue($result);
    }

    public function testQueueWithOptimizeIsCalled()
    {
        $job = new Job('indexer:index', array('--optimize'));
        $this->jobManager->addJob($job)->shouldBeCalled();

        $result = $this->operator
            ->queue($this->createOperations()->optimize());

        $this->assertTrue($result);
    }

    public function testRunWithFlushIsCalledForStorageWithFlushableInterface()
    {
        $this->storage->willImplement('Phlexible\Bundle\IndexerBundle\Storage\Flushable');
        $this->storage->flush()->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->flush());

        $this->assertTrue($result);
    }

    public function testQueueWithFlushIsCalled()
    {
        $job = new Job('indexer:index', array('--flush'));
        $this->jobManager->addJob($job)->shouldBeCalled();

        $result = $this->operator
            ->queue($this->createOperations()->flush());

        $this->assertTrue($result);
    }

    public function testRunWithAddDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storage->addDocument($doc)->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->addDocument($doc));

        $this->assertTrue($result);
    }

    public function testRunWithAddDocumentDispatchesEvents()
    {
        $doc = new TestDocument();

        $called = 0;
        $this->eventDispatcher->addListener(IndexerEvents::BEFORE_STORAGE_ADD_DOCUMENT, function() use (&$called) {
            $called++;
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_ADD_DOCUMENT, function() use (&$called) {
            $called++;
        });

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->addDocument($doc));

        $this->assertTrue($result);
        $this->assertSame(2, $called);
    }

    public function testRunWithAddDocumentIsStoppedOnCancelledEvent()
    {
        $doc = new TestDocument();

        $called = 0;
        $this->eventDispatcher->addListener(IndexerEvents::BEFORE_STORAGE_ADD_DOCUMENT, function($event) use (&$called) {
            $called++;
            $event->stopPropagation();
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_ADD_DOCUMENT, function() use (&$called) {
            $called++;
        });

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->addDocument($doc));

        $this->assertTrue($result);
        $this->assertSame(1, $called);
    }

    public function testRunWithUpdateDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storage->updateDocument($doc)->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->updateDocument($doc));

        $this->assertTrue($result);
    }

    public function testRunWithUpdateDocumentDispatchesEvents()
    {
        $doc = new TestDocument();

        $called = 0;
        $this->eventDispatcher->addListener(IndexerEvents::BEFORE_STORAGE_UPDATE_DOCUMENT, function() use (&$called) {
            $called++;
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_UPDATE_DOCUMENT, function() use (&$called) {
            $called++;
        });

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->updateDocument($doc));

        $this->assertTrue($result);
        $this->assertSame(2, $called);
    }

    public function testRunWithUpdateDocumentIsStoppedOnCancelledEvent()
    {
        $doc = new TestDocument();

        $called = 0;
        $this->eventDispatcher->addListener(IndexerEvents::BEFORE_STORAGE_UPDATE_DOCUMENT, function($event) use (&$called) {
            $called++;
            $event->stopPropagation();
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_UPDATE_DOCUMENT, function() use (&$called) {
            $called++;
        });

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->updateDocument($doc));

        $this->assertTrue($result);
        $this->assertSame(1, $called);
    }

    public function testRunWithDeleteDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storage->deleteDocument($doc)->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->deleteDocument($doc));

        $this->assertTrue($result);
    }

    public function testRunWithDeleteIdentifierIsCalled()
    {
        $this->storage->delete('testIdentifier')->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->deleteIdentifier('testIdentifier'));

        $this->assertTrue($result);
    }

    public function testRunWithDeleteTypeIsCalled()
    {
        $this->storage->deleteType('testType')->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->deleteType('testType'));

        $this->assertTrue($result);
    }
}
