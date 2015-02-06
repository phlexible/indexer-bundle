<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Test\Storage\UpdateQuery;

use Phlexible\Bundle\IndexerBundle\Document\Document;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Phlexible\Bundle\IndexerBundle\Storage\StorageInterface;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommandCollection;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\UpdateQuery;
use Phlexible\Bundle\QueueBundle\Entity\Job;
use Phlexible\Bundle\QueueBundle\Model\JobManagerInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TestDocument extends Document
{
    public function getName()
    {
        return 'test';
    }
}

/**
 * Update query test
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class UpdateQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
      * @var UpdateQuery
      */
    private $updateQuery;

    /**
     * @var JobManagerInterface|ObjectProphecy
     */
    private $jobManager;

    /**
     * @var StorageInterface|ObjectProphecy
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
        $this->updateQuery = new UpdateQuery($this->jobManager->reveal(), $this->eventDispatcher);
    }

    /**
     * @return CommandCollection
     */
    private function createCommands()
    {
        return $this->updateQuery->createCommands();
    }

    public function testRunWithCommitIsCalledForStorageWithCommitableInterface()
    {
        $this->storage->willImplement('Phlexible\Bundle\IndexerBundle\Storage\Commitable');
        $this->storage->commit()->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->commit());

        $this->assertTrue($result);
    }

    public function testQueueWithCommitIsCalled()
    {
        $job = new Job('indexer:index', array('--commit'));
        $this->jobManager->addJob($job)->shouldBeCalled();

        $result = $this->updateQuery
            ->queue($this->createCommands()->commit());

        $this->assertTrue($result);
    }

    public function testRunWithRollbackIsCalledForStorageWithRollbackableInterface()
    {
        $this->storage->willImplement('Phlexible\Bundle\IndexerBundle\Storage\Rollbackable');
        $this->storage->rollback()->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->rollback());

        $this->assertTrue($result);
    }

    public function testQueueWithRollbackIsCalled()
    {
        $job = new Job('indexer:index', array('--rollback'));
        $this->jobManager->addJob($job)->shouldBeCalled();

        $result = $this->updateQuery
            ->queue($this->createCommands()->rollback());

        $this->assertTrue($result);
    }

    public function testRunWithOptimizeIsCalledForStorageWithOptimizableInterface()
    {
        $this->storage->willImplement('Phlexible\Bundle\IndexerBundle\Storage\Optimizable');
        $this->storage->optimize()->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->optimize());

        $this->assertTrue($result);
    }

    public function testQueueWithOptimizeIsCalled()
    {
        $job = new Job('indexer:index', array('--optimize'));
        $this->jobManager->addJob($job)->shouldBeCalled();

        $result = $this->updateQuery
            ->queue($this->createCommands()->optimize());

        $this->assertTrue($result);
    }

    public function testRunWithFlushIsCalledForStorageWithFlushableInterface()
    {
        $this->storage->willImplement('Phlexible\Bundle\IndexerBundle\Storage\Flushable');
        $this->storage->flush()->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->flush());

        $this->assertTrue($result);
    }

    public function testQueueWithFlushIsCalled()
    {
        $job = new Job('indexer:index', array('--flush'));
        $this->jobManager->addJob($job)->shouldBeCalled();

        $result = $this->updateQuery
            ->queue($this->createCommands()->flush());

        $this->assertTrue($result);
    }

    public function testRunWithAddDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storage->addDocument($doc)->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->addDocument($doc));

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

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->addDocument($doc));

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

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->addDocument($doc));

        $this->assertTrue($result);
        $this->assertSame(1, $called);
    }

    public function testRunWithUpdateDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storage->updateDocument($doc)->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->updateDocument($doc));

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

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->updateDocument($doc));

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

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->updateDocument($doc));

        $this->assertTrue($result);
        $this->assertSame(1, $called);
    }

    public function testRunWithDeleteDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storage->deleteDocument($doc)->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->deleteDocument($doc));

        $this->assertTrue($result);
    }

    public function testRunWithDeleteIsCalled()
    {
        $this->storage->delete('testIdentifier')->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->delete('testIdentifier'));

        $this->assertTrue($result);
    }

    public function testRunWithDeleteTypeIsCalled()
    {
        $this->storage->deleteType('testType')->shouldBeCalledTimes(1);

        $result = $this->updateQuery
            ->run($this->storage->reveal(), $this->createCommands()->deleteType('testType'));

        $this->assertTrue($result);
    }
}
