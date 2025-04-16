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

namespace EliasHaeussler\Typo3ConfigObjects\Tests\ValueObject;

use EliasHaeussler\Typo3ConfigObjects as Src;
use PHPUnit\Framework;

/**
 * DeprecatedIconTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\ValueObject\DeprecatedIcon::class)]
final class DeprecatedIconTest extends Framework\TestCase
{
    private Src\ValueObject\DeprecatedIcon $subject;

    public function setUp(): void
    {
        $this->subject = new Src\ValueObject\DeprecatedIcon('TYPO3 v12', 'TYPO3 v13', 'icon-baz');
    }

    #[Framework\Attributes\Test]
    public function toArrayReturnsArrayRepresentation(): void
    {
        $expected = [
            'since' => 'TYPO3 v12',
            'until' => 'TYPO3 v13',
            'replacement' => 'icon-baz',
        ];

        self::assertSame($expected, $this->subject->toArray());
    }
}
