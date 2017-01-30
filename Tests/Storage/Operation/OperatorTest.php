<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Tests\Storage\Operation;

use Phlexible\Bundle\IndexerBundle\Document\Document;
use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Phlexible\Bundle\IndexerBundle\Storage\Commitable;
use Phlexible\Bundle\IndexerBundle\Storage\Flushable;
use Phlexible\Bundle\IndexerBundle\Storage\Operation\Operations;
use Phlexible\Bundle\IndexerBundle\Storage\Operation\Operator;
use Phlexible\Bundle\IndexerBundle\Storage\Optimizable;
use Phlexible\Bundle\IndexerBundle\Storage\Rollbackable;
use Phlexible\Bundle\IndexerBundle\Storage\StorageInterface;
use Phlexible\Bundle\QueueBundle\Entity\Job;
use Phlexible\Bundle\QueueBundle\Model\JobManagerInterface;
use Prophecy\Argument;
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
 * Operator test.
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
        $this->jobManager = $this->prophesize(JobManagerInterface::class);
        $this->storage = $this->prophesize(StorageInterface::class);
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
        $this->storage->willImplement(Commitable::class);
        $this->storage->commit()->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->commit());

        $this->assertTrue($result);
    }

    public function testQueueWithCommitIsCalled()
    {
        $this->jobManager->addJob(Argument::that(function(Job $job) {
            $this->assertSame('indexer:index', $job->getCommand());
            $this->assertSame(array('--commit'), $job->getArguments());
            return true;
        }))->shouldBeCalled();

        $result = $this->operator
            ->queue($this->createOperations()->commit());

        $this->assertTrue($result);
    }

    public function testRunWithRollbackIsCalledForStorageWithRollbackableInterface()
    {
        $this->storage->willImplement(Rollbackable::class);
        $this->storage->rollback()->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->rollback());

        $this->assertTrue($result);
    }

    public function testQueueWithRollbackIsCalled()
    {
        $this->jobManager->addJob(Argument::that(function(Job $job) {
            $this->assertSame('indexer:index', $job->getCommand());
            $this->assertSame(array('--rollback'), $job->getArguments());
            return true;
        }))->shouldBeCalled();

        $result = $this->operator
            ->queue($this->createOperations()->rollback());

        $this->assertTrue($result);
    }

    public function testRunWithOptimizeIsCalledForStorageWithOptimizableInterface()
    {
        $this->storage->willImplement(Optimizable::class);
        $this->storage->optimize()->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->optimize());

        $this->assertTrue($result);
    }

    public function testQueueWithOptimizeIsCalled()
    {
        $this->jobManager->addJob(Argument::that(function(Job $job) {
            $this->assertSame('indexer:index', $job->getCommand());
            $this->assertSame(array('--optimize'), $job->getArguments());
            return true;
        }))->shouldBeCalled();

        $result = $this->operator
            ->queue($this->createOperations()->optimize());

        $this->assertTrue($result);
    }

    public function testRunWithFlushIsCalledForStorageWithFlushableInterface()
    {
        $this->storage->willImplement(Flushable::class);
        $this->storage->flush()->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->flush());

        $this->assertTrue($result);
    }

    public function testQueueWithFlushIsCalled()
    {
        $this->jobManager->addJob(Argument::that(function(Job $job) {
            $this->assertSame('indexer:index', $job->getCommand());
            $this->assertSame(array('--flush'), $job->getArguments());
            return true;
        }))->shouldBeCalled();

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
            ++$called;
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_ADD_DOCUMENT, function() use (&$called) {
            ++$called;
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
            ++$called;
            $event->stopPropagation();
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_ADD_DOCUMENT, function() use (&$called) {
            ++$called;
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
            ++$called;
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_UPDATE_DOCUMENT, function() use (&$called) {
            ++$called;
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
            ++$called;
            $event->stopPropagation();
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_UPDATE_DOCUMENT, function() use (&$called) {
            ++$called;
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

    public function testRunWithDeleteIdentityIsCalled()
    {
        $identity = new DocumentIdentity('testIdentity');

        $this->storage->delete($identity)->shouldBeCalledTimes(1);

        $result = $this->operator
            ->execute($this->storage->reveal(), $this->createOperations()->deleteIdentity($identity));

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
