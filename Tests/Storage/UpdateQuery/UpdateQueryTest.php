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
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\UpdateQuery;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
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
     * @var StorageInterface|MockObject
     */
    private $storageMock;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function setUp()
    {
        $this->eventDispatcher = new EventDispatcher();
        $this->storageMock = $this->getMockBuilder('\Phlexible\Bundle\IndexerBundle\Storage\StorageInterface')
            ->getMock();
        $this->updateQuery = new UpdateQuery($this->eventDispatcher);
    }

    public function testExecuteCommitIsNotCalledForStorageWithoutCommitableInterface()
    {
        $this->storageMock
            ->expects($this->never())
            ->method('commit');

        $this->updateQuery
            ->commit()
            ->execute($this->storageMock);
    }

    public function testExecuteRollbackIsNotCalledForStorageWithoutRollbackableInterface()
    {
        $this->storageMock
            ->expects($this->never())
            ->method('rollback');

        $this->updateQuery
            ->rollback()
            ->execute($this->storageMock);
    }

    public function testExecuteOptimizeIsNotCalledForStorageWithoutOptimizableInterface()
    {
        $this->storageMock
            ->expects($this->never())
            ->method('optimize');

        $this->updateQuery
            ->optimize()
            ->execute($this->storageMock);
    }

    public function testExecuteFlushIsNotCalledForStorageWithoutFlushableInterface()
    {
        $this->storageMock
            ->expects($this->never())
            ->method('flush');

        $this->updateQuery
            ->flush()
            ->execute($this->storageMock);
    }

    public function testExecuteAddDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storageMock
            ->expects($this->once())
            ->method('addDocument')
            ->with($doc);

        $this->updateQuery->addDocument($doc);
        $this->updateQuery->execute($this->storageMock);
    }

    public function testExecuteAddDocumentDispatchesEvents()
    {
        $doc = new TestDocument();

        $called = 0;
        $this->eventDispatcher->addListener(IndexerEvents::BEFORE_STORAGE_ADD_DOCUMENT, function() use (&$called) {
            $called++;
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_ADD_DOCUMENT, function() use (&$called) {
            $called++;
        });

        $this->updateQuery->addDocument($doc);
        $this->updateQuery->execute($this->storageMock);

        $this->assertSame(2, $called);
    }

    public function testExecuteAddDocumentIsStoppedOnCancelledEvent()
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

        $this->updateQuery->addDocument($doc);
        $this->updateQuery->execute($this->storageMock);

        $this->assertSame(1, $called);
    }

    public function testExecuteUpdateDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storageMock
            ->expects($this->once())
            ->method('updateDocument')
            ->with($doc);

        $this->updateQuery->updateDocument($doc);
        $this->updateQuery->execute($this->storageMock);
    }

    public function testExecuteUpdateDocumentDispatchesEvents()
    {
        $doc = new TestDocument();

        $called = 0;
        $this->eventDispatcher->addListener(IndexerEvents::BEFORE_STORAGE_UPDATE_DOCUMENT, function() use (&$called) {
            $called++;
        });
        $this->eventDispatcher->addListener(IndexerEvents::STORAGE_UPDATE_DOCUMENT, function() use (&$called) {
            $called++;
        });

        $this->updateQuery->updateDocument($doc);
        $this->updateQuery->execute($this->storageMock);

        $this->assertSame(2, $called);
    }

    public function testExecuteUpdateDocumentIsStoppedOnCancelledEvent()
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

        $this->updateQuery->updateDocument($doc);
        $this->updateQuery->execute($this->storageMock);

        $this->assertSame(1, $called);
    }

    public function testExecuteDeleteDocumentIsCalled()
    {
        $doc = new TestDocument();

        $this->storageMock
            ->expects($this->once())
            ->method('deleteDocument')
            ->with($doc);

        $this->updateQuery->deleteDocument($doc);
        $this->updateQuery->execute($this->storageMock);
    }

    public function testExecuteDeleteIsCalled()
    {
        $this->storageMock
            ->expects($this->once())
            ->method('delete')
            ->with('abc');

        $this->updateQuery->delete('abc');
        $this->updateQuery->execute($this->storageMock);
    }

    public function testExecuteDeleteTypeIsCalled()
    {
        $this->storageMock
            ->expects($this->once())
            ->method('deleteType')
            ->with('abc');

        $this->updateQuery->deleteType('abc');
        $this->updateQuery->execute($this->storageMock);
    }

    public function testExecuteDeleteAllIsCalled()
    {
        $this->storageMock
            ->expects($this->once())
            ->method('deleteType')
            ->with('testType');

        $this->updateQuery->deleteType('testType');
        $this->updateQuery->execute($this->storageMock);
    }
}
