<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/typo3-config-objects".
 *
 * Copyright (C) 2025-2026 Elias Häußler <elias@haeussler.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace EliasHaeussler\Typo3ConfigObjects\Tests\ValueObject;

use EliasHaeussler\Typo3ConfigObjects as Src;
use EliasHaeussler\Typo3ConfigObjects\Tests;
use PHPUnit\Framework;

/**
 * RequestMiddlewareTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\ValueObject\RequestMiddleware::class)]
final class RequestMiddlewareTest extends Framework\TestCase
{
    private Src\ValueObject\RequestMiddleware $subject;

    public function setUp(): void
    {
        $this->subject = Src\ValueObject\RequestMiddleware::create('foo');
    }

    #[Framework\Attributes\Test]
    public function setTargetConfiguresGivenTarget(): void
    {
        $this->subject->setTarget(Tests\Fixtures\DummyMiddleware::class);

        $expected = [
            'target' => Tests\Fixtures\DummyMiddleware::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function disableSetsDisabledOptionToTrue(): void
    {
        $this->subject->disable();

        $expected = [
            'disabled' => true,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function enableSetsDisabledOptionToFalse(): void
    {
        $this->subject->enable();

        $expected = [
            'disabled' => false,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function beforeAddsDependencies(): void
    {
        $this->subject->before('baz', 'boo');

        $expected = [
            'before' => [
                'baz',
                'boo',
            ],
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function afterAddsDependencies(): void
    {
        $this->subject->after('baz', 'boo');

        $expected = [
            'after' => [
                'baz',
                'boo',
            ],
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function toArrayThrowsExceptionIfTargetIsMissingAndMiddlewareIsNotFurtherConfigured(): void
    {
        $this->expectExceptionObject(
            new Src\Exception\MiddlewareTargetIsMissing(),
        );

        $this->subject->toArray();
    }
}
