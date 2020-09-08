<?php

/**
 * This file is part of the ramsey/collection library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Ramsey\Collection;

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;
use Ramsey\Collection\Tool\TypeTrait;
use Ramsey\Collection\Tool\ValueToStringTrait;

/**
 * This class provides a basic implementation of `QueueInterface`, to minimize
 * the effort required to implement this interface.
 */
class Queue extends AbstractArray implements QueueInterface
{
    use TypeTrait;
    use ValueToStringTrait;

    /**
     * A queue's type is immutable once it is set. For this reason, this
     * property is set private.
     */
    private string $queueType;

    /**
     * The index of the head of the queue.
     */
    protected int $index = 0;

    /**
     * @param string $queueType The type (FQCN) associated with this queue.
     * @param mixed[] $data The initial items to store in the collection.
     */
    public function __construct(string $queueType, array $data = [])
    {
        $this->queueType = $queueType;
        parent::__construct($data);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        if ($this->checkType($this->getType(), $value) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->getType() . '; value is '
                . $this->toolValueToString($value),
            );
        }

        $this->data[] = $value;
    }

    /**
     * @inheritDoc
     */
    public function add($element): bool
    {
        /** @var mixed */
        $this[] = $element;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function element()
    {
        if ($this->count() === 0) {
            throw new NoSuchElementException(
                'Can\'t return element from Queue. Queue is empty.',
            );
        }

        return $this[$this->index];
    }

    /**
     * @inheritDoc
     */
    public function offer($element): bool
    {
        try {
            return $this->add($element);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function peek()
    {
        if ($this->count() === 0) {
            return null;
        }

        return $this[$this->index];
    }

    /**
     * @inheritDoc
     */
    public function poll()
    {
        if ($this->count() === 0) {
            return null;
        }

        /** @var mixed $head */
        $head = $this[$this->index];

        unset($this[$this->index]);
        $this->index++;

        return $head;
    }

    /**
     * @inheritDoc
     */
    public function remove()
    {
        if ($this->count() === 0) {
            throw new NoSuchElementException('Can\'t return element from Queue. Queue is empty.');
        }

        /** @var mixed $head */
        $head = $this[$this->index];

        unset($this[$this->index]);
        $this->index++;

        return $head;
    }

    public function getType(): string
    {
        return $this->queueType;
    }
}
