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

namespace EliasHaeussler\Typo3ConfigObjects\Tests\Configuration;

use EliasHaeussler\Typo3ConfigObjects as Src;
use PHPUnit\Framework;
use TYPO3\CMS\Core;

/**
 * IconConfigurationTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Configuration\IconConfiguration::class)]
final class IconConfigurationTest extends Framework\TestCase
{
    private Src\Configuration\IconConfiguration $subject;

    public function setUp(): void
    {
        $this->subject = Src\Configuration\IconConfiguration::create();
    }

    #[Framework\Attributes\Test]
    public function addAppendsGivenIconsToListOfConfiguredIcons(): void
    {
        $iconA = Src\ValueObject\Icon::create('foo')->setSource('baz');
        $iconB = Src\ValueObject\Icon::create('baz')->setSource('foo');

        $this->subject->add($iconA, $iconB);

        $expected = [
            'foo' => [
                'source' => 'baz',
                'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
            ],
            'baz' => [
                'source' => 'foo',
                'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
            ],
        ];

        self::assertSame($expected, $this->subject->toArray());
    }
}
