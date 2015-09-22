<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Tests\Document;

use Phlexible\Bundle\IndexerBundle\Document\Document;
use Phlexible\Bundle\IndexerBundle\Document\DocumentFactory;
use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;
use Phlexible\Bundle\IndexerBundle\Event\DocumentEvent;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TestDocument extends Document {
    public function getName() {}
}

/**
 * Document factory test
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class DocumentFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesDocument()
    {
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $factory = new DocumentFactory($eventDispatcher->reveal());

        $document = $factory->factory(TestDocument::class);

        $this->assertInstanceOf(TestDocument::class, $document);
    }

    public function testFactoryCreatesDocumentWithIdentity()
    {
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $factory = new DocumentFactory($eventDispatcher->reveal());

        $identity = new DocumentIdentity('abc');
        $document = $factory->factory(TestDocument::class, $identity);

        $this->assertInstanceOf(TestDocument::class, $document);
        $this->assertSame($identity, $document->getIdentity());
    }

    public function testFactoryDispatchesDocumentEvent()
    {
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $eventDispatcher->dispatch(IndexerEvents::CREATE_DOCUMENT, Argument::type(DocumentEvent::class))->shouldBeCalled();

        $factory = new DocumentFactory($eventDispatcher->reveal());

        $factory->factory(TestDocument::class);
    }
}
