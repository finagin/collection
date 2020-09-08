<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test;

use Faker\Factory;
use Faker\Generator;
use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Dev\Tools\TestCase as RamseyTestCase;

class TestCase extends RamseyTestCase
{
    use ProphecyTrait;

    protected Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }
}
