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
use EliasHaeussler\Typo3ConfigObjects\Tests;
use PHPUnit\Framework;
use TYPO3\CMS\Core;

/**
 * IconTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\ValueObject\Icon::class)]
final class IconTest extends Framework\TestCase
{
    private Src\ValueObject\Icon $subject;

    public function setUp(): void
    {
        $this->subject = Src\ValueObject\Icon::create('foo');
    }

    #[Framework\Attributes\Test]
    public function useSvgIconProviderConfiguresSvgIconProvider(): void
    {
        $this->subject->useSvgIconProvider('baz');

        $expected = [
            'source' => 'baz',
            'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function useBitmapIconProviderConfiguresBitmapIconProvider(): void
    {
        $this->subject->useBitmapIconProvider('baz');

        $expected = [
            'source' => 'baz',
            'provider' => Core\Imaging\IconProvider\BitmapIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function useCustomIconProviderConfiguresCustomIconProvider(): void
    {
        $this->subject->useCustomIconProvider(Tests\Fixtures\DummyIconProvider::class);

        $expected = [
            'provider' => Tests\Fixtures\DummyIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function setSourceAppliesGivenSourceToOptions(): void
    {
        $this->subject->setSource('baaaz');

        $expected = [
            'source' => 'baaaz',
            'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function setDeprecatedAppliesDeprecatedInformationToOptions(): void
    {
        $config = new Src\ValueObject\DeprecatedIcon('TYPO3 v12', 'TYPO3 v13', 'icon-baz');

        $this->subject->setSource('baz');
        $this->subject->setDeprecated($config);

        $expected = [
            'source' => 'baz',
            'deprecated' => [
                'since' => 'TYPO3 v12',
                'until' => 'TYPO3 v13',
                'replacement' => 'icon-baz',
            ],
            'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function undeprecateRemovesDeprecatedInformationFromOptions(): void
    {
        $config = new Src\ValueObject\DeprecatedIcon('TYPO3 v12', 'TYPO3 v13', 'icon-baz');

        $this->subject->setSource('baz');
        $this->subject->setDeprecated($config);
        $this->subject->undeprecate();

        $expected = [
            'source' => 'baz',
            'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function addOptionForwardsDeprecatedOptionToDedicatedMethod(): void
    {
        $config = new Src\ValueObject\DeprecatedIcon('TYPO3 v12', 'TYPO3 v13', 'icon-baz');

        $this->subject->setSource('baz');
        $this->subject->addOption('deprecated', $config);

        $expected = [
            'source' => 'baz',
            'deprecated' => [
                'since' => 'TYPO3 v12',
                'until' => 'TYPO3 v13',
                'replacement' => 'icon-baz',
            ],
            'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function addOptionThrowsExceptionIfInvalidDeprecatedOptionIsGiven(): void
    {
        $this->expectException(Src\Exception\IconOptionIsNotProperlyConfigured::class);

        $this->subject->addOption('deprecated', 'foo');
    }

    #[Framework\Attributes\Test]
    public function addOptionForwardsSourceOptionToDedicatedMethod(): void
    {
        $this->subject->addOption('source', 'baaaz');

        $expected = [
            'source' => 'baaaz',
            'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function addOptionThrowsExceptionIfInvalidSourceOptionIsGiven(): void
    {
        $this->expectException(Src\Exception\IconOptionIsNotProperlyConfigured::class);

        $this->subject->addOption('source', null);
    }

    #[Framework\Attributes\Test]
    public function addOptionAddsGivenOptionToIconOptions(): void
    {
        $this->subject->setSource('baz');
        $this->subject->addOption('foo', 'bar');

        $expected = [
            'source' => 'baz',
            'foo' => 'bar',
            'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        ];

        /* @phpstan-ignore staticMethod.impossibleType (until https://github.com/phpstan/phpstan/issues/8438 is fixed) */
        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function removeOptionThrowsExceptionIfSourceOptionIsRemoved(): void
    {
        $this->expectExceptionObject(
            new Src\Exception\IconSourceOptionCannotBeRemoved(),
        );

        $this->subject->removeOption('source');
    }

    #[Framework\Attributes\Test]
    public function removeOptionAllowsUnsettingSourceOptionIfCustomIconProviderIsUsed(): void
    {
        $this->subject->useCustomIconProvider(Tests\Fixtures\DummyIconProvider::class);
        $this->subject->setSource('baz');
        $this->subject->removeOption('source');

        $expected = [
            'provider' => Tests\Fixtures\DummyIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function removeOptionRemovesOptionFromIconOptions(): void
    {
        $this->subject->setSource('baz');
        $this->subject->addOption('foo', 'bar');
        $this->subject->removeOption('foo');

        $expected = [
            'source' => 'baz',
            'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        ];

        self::assertSame($expected, $this->subject->toArray());
    }

    #[Framework\Attributes\Test]
    public function toArrayThrowsExceptionIfSourceOptionIsMissing(): void
    {
        $this->expectExceptionObject(
            new Src\Exception\IconSourceOptionIsMissing(),
        );

        $this->subject->toArray();
    }
}
