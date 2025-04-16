<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/typo3-config-objects".
 *
 * Copyright (C) 2025 Elias Häußler <elias@haeussler.dev>
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

namespace EliasHaeussler\Typo3ConfigObjects\Tests\Configuration;

use EliasHaeussler\Typo3ConfigObjects as Src;
use EliasHaeussler\Typo3ConfigObjects\Tests;
use PHPUnit\Framework;

/**
 * MiddlewareConfigurationTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Configuration\MiddlewareConfiguration::class)]
final class MiddlewareConfigurationTest extends Framework\TestCase
{
    private Src\Configuration\MiddlewareConfiguration $subject;

    public function setUp(): void
    {
        $this->subject = Src\Configuration\MiddlewareConfiguration::create();
    }

    #[Framework\Attributes\Test]
    public function addToBackendStackAppendsGivenRequestMiddlewaresToBackendStack(): void
    {
        $middlewareA = Src\ValueObject\RequestMiddleware::create('foo')
            ->setTarget(Tests\Fixtures\DummyMiddleware::class)
            ->before('baz')
            ->after('boo')
        ;
        $middlewareB = Src\ValueObject\RequestMiddleware::create('baz')
            ->disable()
        ;

        $this->subject->addToBackendStack($middlewareA, $middlewareB);

        $expected = [
            'backend' => [
                'foo' => [
                    'target' => Tests\Fixtures\DummyMiddleware::class,
                    'before' => [
                        'baz',
                    ],
                    'after' => [
                        'boo',
                    ],
                ],
                'baz' => [
                    'disabled' => true,
                ],
            ],
            'frontend' => [],
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function addToFrontendStackAppendsGivenRequestMiddlewaresToFrontendStack(): void
    {
        $middlewareA = Src\ValueObject\RequestMiddleware::create('foo')
            ->enable()
        ;
        $middlewareB = Src\ValueObject\RequestMiddleware::create('baz')
            ->disable()
        ;

        $this->subject->addToFrontendStack($middlewareA, $middlewareB);

        $expected = [
            'backend' => [],
            'frontend' => [
                'foo' => [
                    'disabled' => false,
                ],
                'baz' => [
                    'disabled' => true,
                ],
            ],
        ];

        self::assertSame($expected, $this->subject->toArray());
    }
}
