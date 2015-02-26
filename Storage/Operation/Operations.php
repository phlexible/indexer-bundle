<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\Operation;

use Doctrine\Common\Collections\ArrayCollection;
use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;

/**
 * Operation collection
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class Operations
{
    /**
     * @var OperationInterface[]|ArrayCollection
     */
    private $operations;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->operations = new ArrayCollection();
    }

    /**
     * @return OperationInterface[]
     */
    public function getOperations()
    {
        return $this->operations->toArray();
    }

    /**
     * @param OperationInterface $operation
     *
     * @return $this
     */
    public function addOperation(OperationInterface $operation)
    {
        $this->operations->add($operation);

        return $this;
    }

    /**
     * @param OperationInterface $operation
     *
     * @return $this
     */
    public function removeOperation(OperationInterface $operation)
    {
        if ($this->operations->contains($operation)) {
            $this->operations->removeElement($operation);
        }

        return $this;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function addDocument(DocumentInterface $document)
    {
        return $this->addOperation(new AddDocumentOperation($document));
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function addIdentifier($identifier)
    {
        return $this->addOperation(new AddIdentifierOperation($identifier));
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function updateDocument(DocumentInterface $document)
    {
        return $this->addOperation(new UpdateDocumentOperation($document));
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function updateIdentifier($identifier)
    {
        return $this->addOperation(new UpdateIdentifierOperation($identifier));
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function deleteDocument(DocumentInterface $document)
    {
        return $this->addOperation(new DeleteDocumentOperation($document));
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function deleteIdentifier($identifier)
    {
        return $this->addOperation(new DeleteIdentifierOperation($identifier));
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function deleteType($type)
    {
        return $this->addOperation(new DeleteTypeOperation($type));
    }

    /**
     * @return $this
     */
    public function commit()
    {
        return $this->addOperation(new CommitOperation());
    }

    /**
     * @return $this
     */
    public function rollback()
    {
        return $this->addOperation(new RollbackOperation());
    }

    /**
     * @return $this
     */
    public function flush()
    {
        return $this->addOperation(new FlushOperation());
    }

    /**
     * @return $this
     */
    public function optimize()
    {
        return $this->addOperation(new OptimizeOperation());
    }
}
