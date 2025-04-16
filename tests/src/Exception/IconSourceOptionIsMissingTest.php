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

/**
 * IconSourceOptionIsMissingTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Exception\IconSourceOptionIsMissing::class)]
final class IconSourceOptionIsMissingTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    public function constructorReturnsExceptionForSourceOption(): void
    {
        $actual = new Src\Exception\IconSourceOptionIsMissing();

        self::assertSame(
            'The configured icon provider requires an icon source to be present.',
            $actual->getMessage(),
        );
        self::assertSame(1744789796, $actual->getCode());
    }
}
