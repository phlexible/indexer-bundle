<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Query\Query;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\AddCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommandInterface;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommitCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteQueryCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteTypeCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\FlushCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\OptimizeCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\RollbackCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\UpdateCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Update
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class UpdateQuery
{
    /**
      * @var EventDispatcherInterface
      */
    protected $dispatcher;

    /**
     * @var CommandInterface[]
     */
    protected $commands = array();

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return CommandInterface[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function add(DocumentInterface $document)
    {
        return $this->addCommand(new AddCommand($document));
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function addUpdate(DocumentInterface $document)
    {
        return $this->addCommand(new UpdateCommand($document));
    }

    /**
     * @param null $identifier
     *
     * @return $this
     */
    public function addDeleteByIdentifier($identifier = null)
    {
        return $this->addCommand(new DeleteCommand($identifier));
    }

    /**
     * @param Query $query
     *
     * @return $this
     */
    public function addDeleteByQuery(Query $query)
    {
        return $this->addCommand(new DeleteQueryCommand($query));
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function addDeleteByType($type)
    {
        return $this->addCommand(new DeleteTypeCommand($type));
    }

    /**
     * @return $this
     */
    public function addCommit()
    {
        return $this->addCommand(new CommitCommand());
    }

    /**
     * @return $this
     */
    public function addRollback()
    {
        return $this->addCommand(new RollbackCommand());
    }

    /**
     * @return $this
     */
    public function addFlush()
    {
        return $this->addCommand(new FlushCommand());
    }

    /**
     * @return $this
     */
    public function addOptimize()
    {
        return $this->addCommand(new OptimizeCommand());
    }

    /**
     * @param CommandInterface $command
     *
     * @return $this
     */
    private function addCommand(CommandInterface $command)
    {
        $this->commands[] = $command;

        return $this;
    }
}
