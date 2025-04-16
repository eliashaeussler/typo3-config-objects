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

namespace EliasHaeussler\Typo3ConfigObjects\Tests\Exception;

use EliasHaeussler\Typo3ConfigObjects as Src;
use PHPUnit\Framework;

use function sprintf;

/**
 * IconOptionIsNotProperlyConfiguredTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Exception\IconOptionIsNotProperlyConfigured::class)]
final class IconOptionIsNotProperlyConfiguredTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    public function constructorReturnsExceptionForExpectedClassType(): void
    {
        $actual = new Src\Exception\IconOptionIsNotProperlyConfigured(
            'foo',
            'baz',
            self::class,
            'setFoo',
        );

        self::assertSame(
            sprintf(
                'The "foo" icon option must be an instance of %s, string given. Please use the %s::setFoo() method instead.',
                self::class,
                Src\ValueObject\Icon::class,
            ),
            $actual->getMessage(),
        );
        self::assertSame(1744790988, $actual->getCode());
    }

    #[Framework\Attributes\Test]
    public function constructorReturnsExceptionForExpectedScalarType(): void
    {
        $actual = new Src\Exception\IconOptionIsNotProperlyConfigured(
            'foo',
            null,
            'non-empty-string',
            'setFoo',
        );

        self::assertSame(
            sprintf(
                'The "foo" icon option must be of type non-empty-string, null given. Please use the %s::setFoo() method instead.',
                Src\ValueObject\Icon::class,
            ),
            $actual->getMessage(),
        );
        self::assertSame(1744790988, $actual->getCode());
    }
}
