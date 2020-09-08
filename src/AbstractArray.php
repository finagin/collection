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

use ArrayIterator;
use Traversable;

use function count;
use function serialize;
use function unserialize;

/**
 * This class provides a basic implementation of `ArrayInterface`, to minimize
 * the effort required to implement this interface.
 */
abstract class AbstractArray implements ArrayInterface
{
    /**
     * @var array<array-key, mixed>
     */
    protected array $data = [];

    /**
     * @param array<array-key, mixed> $data The initial items to add to this array.
     */
    public function __construct(array $data = [])
    {
        /**
         * Invoke offsetSet() for each value added; in this way, sub-classes
         * may provide additional logic about values added to the array object.
         *
         * @var array-key $key
         * @var mixed $value
         */
        foreach ($data as $key => $value) {
            /** @var mixed */
            $this[$key] = $value;
        }
    }

    /**
     * @return ArrayIterator<array-key, mixed>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @param array-key $offset The offset to check.
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param array-key $offset The offset for which a value should be returned.
     *
     * @return mixed|null the value stored at the offset, or null if the offset
     *     does not exist.
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * @param array-key|null $offset The offset to set. If `null`, the value may be
     *     set at a numerically-indexed offset.
     * @param mixed $value The value to set at the given offset.
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * @param array-key $offset The offset to remove from the array.
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function serialize(): string
    {
        return serialize($this->data);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized): void
    {
        /** @var array<array-key, mixed> */
        $this->data = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->data;
    }

    public function isEmpty(): bool
    {
        return count($this->data) === 0;
    }
}
