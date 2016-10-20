<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\Operation;

use Countable;
use Doctrine\Common\Collections\ArrayCollection;
use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;
use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;

/**
 * Operation collection
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class Operations implements Countable
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
        if (!$this->operations->contains($operation)) {
            $this->operations->add($operation);
        }

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
     * @param DocumentIdentity $identity
     *
     * @return $this
     */
    public function addIdentity(DocumentIdentity $identity)
    {
        return $this->addOperation(new AddIdentityOperation($identity));
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
     * @param DocumentIdentity $identity
     *
     * @return $this
     */
    public function updateIdentity(DocumentIdentity $identity)
    {
        return $this->addOperation(new UpdateIdentityOperation($identity));
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
     * @param DocumentIdentity $identity
     *
     * @return $this
     */
    public function deleteIdentity(DocumentIdentity $identity)
    {
        return $this->addOperation(new DeleteIdentityOperation($identity));
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

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->operations);
    }
}
