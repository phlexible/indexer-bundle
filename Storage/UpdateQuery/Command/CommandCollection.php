<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;

/**
 * Command collection
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class CommandCollection
{
    /**
     * @var CommandInterface[]
     */
    private $commands = array();

    /**
     * @return CommandInterface[]
     */
    public function all()
    {
        return $this->commands;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function addDocument(DocumentInterface $document)
    {
        return $this->addCommand(new AddDocumentCommand($document));
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function updateDocument(DocumentInterface $document)
    {
        return $this->addCommand(new UpdateDocumentCommand($document));
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function deleteDocument(DocumentInterface $document)
    {
        return $this->addCommand(new DeleteDocumentCommand($document));
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function delete($identifier)
    {
        return $this->addCommand(new DeleteCommand($identifier));
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function deleteType($type)
    {
        return $this->addCommand(new DeleteTypeCommand($type));
    }

    /**
     * @return $this
     */
    public function commit()
    {
        return $this->addCommand(new CommitCommand());
    }

    /**
     * @return $this
     */
    public function rollback()
    {
        return $this->addCommand(new RollbackCommand());
    }

    /**
     * @return $this
     */
    public function flush()
    {
        return $this->addCommand(new FlushCommand());
    }

    /**
     * @return $this
     */
    public function optimize()
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
