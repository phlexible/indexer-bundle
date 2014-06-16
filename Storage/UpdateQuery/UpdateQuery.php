<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Storage\UpdateQuery;

use Phlexible\IndexerBundle\Document\DocumentInterface;
use Phlexible\IndexerBundle\Query\QueryInterface;
use Phlexible\IndexerBundle\Storage\UpdateQuery\Command\AddCommand;
use Phlexible\IndexerBundle\Storage\UpdateQuery\Command\CommandInterface;
use Phlexible\IndexerBundle\Storage\UpdateQuery\Command\CommitCommand;
use Phlexible\IndexerBundle\Storage\UpdateQuery\Command\DeleteCommand;
use Phlexible\IndexerBundle\Storage\UpdateQuery\Command\FlushCommand;
use Phlexible\IndexerBundle\Storage\UpdateQuery\Command\OptimizeCommand;
use Phlexible\IndexerBundle\Storage\UpdateQuery\Command\RollbackCommand;
use Phlexible\IndexerBundle\Storage\UpdateQuery\Command\UpdateCommand;
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
     * @return $this
     */
    public function add(DocumentInterface $document)
    {
        return $this->addCommand(new AddCommand($document));
    }

    /**
     * @param DocumentInterface $document
     * @return $this
     */
    public function addUpdate(DocumentInterface $document)
    {
        return $this->addCommand(new UpdateCommand($document));
    }

    /**
     * @param null $identifier
     * @return $this
     */
    public function addDeleteByIdentifier($identifier = null)
    {
        return $this->addCommand(new DeleteCommand($identifier));
    }

    /**
     * @param QueryInterface $query
     * @return $this
     */
    public function addDeleteByQuery(QueryInterface $query)
    {
        return $this->addCommand(new DeleteCommand($query));
    }

    /**
     * @return $this
     */
    public function addDeleteAll()
    {
        return $this->addCommand(new DeleteCommand());
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
     * @return $this
     */
    private function addCommand(CommandInterface $command)
    {
        $this->commands[] = $command;
        return $this;
    }
}
