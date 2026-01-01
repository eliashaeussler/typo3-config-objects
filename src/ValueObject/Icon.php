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

namespace EliasHaeussler\Typo3ConfigObjects\ValueObject;

use EliasHaeussler\Typo3ConfigObjects\Contracts;
use EliasHaeussler\Typo3ConfigObjects\Exception;
use TYPO3\CMS\Core;

use function in_array;
use function is_string;

/**
 * Icon.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 *
 * @phpstan-type IconOptions array{
 *     source?: non-empty-string,
 *     deprecated?: array{
 *         since: non-empty-string|null,
 *         until: non-empty-string|null,
 *         replacement: non-empty-string|null,
 *     },
 *     provider?: class-string<Core\Imaging\IconProviderInterface>,
 * }
 *
 * @implements Contracts\Arrayable<IconOptions>
 */
final class Icon implements Contracts\Arrayable
{
    private const DEPRECATED_KEY = 'deprecated';
    private const SOURCE_KEY = 'source';

    /**
     * @var IconOptions&array<string, mixed>
     */
    private array $options = [];

    /**
     * @param non-empty-string                                 $name
     * @param class-string<Core\Imaging\IconProviderInterface> $iconProvider
     */
    private function __construct(
        public readonly string $name,
        private string $iconProvider,
    ) {}

    /**
     * @param non-empty-string                                 $name
     * @param class-string<Core\Imaging\IconProviderInterface> $iconProvider
     */
    public static function create(
        string $name,
        string $iconProvider = Core\Imaging\IconProvider\SvgIconProvider::class,
    ): self {
        return new self($name, $iconProvider);
    }

    /**
     * @param non-empty-string $source
     */
    public function useSvgIconProvider(string $source): self
    {
        $this->iconProvider = Core\Imaging\IconProvider\SvgIconProvider::class;
        $this->setSource($source);

        return $this;
    }

    /**
     * @param non-empty-string $source
     */
    public function useBitmapIconProvider(string $source): self
    {
        $this->iconProvider = Core\Imaging\IconProvider\BitmapIconProvider::class;
        $this->setSource($source);

        return $this;
    }

    /**
     * @param class-string<Core\Imaging\IconProviderInterface> $iconProvider
     */
    public function useCustomIconProvider(string $iconProvider): self
    {
        $this->iconProvider = $iconProvider;

        return $this;
    }

    /**
     * @param non-empty-string $source
     */
    public function setSource(string $source): self
    {
        $this->options[self::SOURCE_KEY] = $source;

        return $this;
    }

    public function setDeprecated(DeprecatedIcon $config): self
    {
        $this->options[self::DEPRECATED_KEY] = $config->toArray();

        return $this;
    }

    public function undeprecate(): self
    {
        unset($this->options[self::DEPRECATED_KEY]);

        return $this;
    }

    /**
     * @param non-empty-string $name
     *
     * @throws Exception\IconOptionIsNotProperlyConfigured
     */
    public function addOption(string $name, mixed $value): self
    {
        if (self::DEPRECATED_KEY === $name) {
            if ($value instanceof DeprecatedIcon) {
                return $this->setDeprecated($value);
            }

            throw new Exception\IconOptionIsNotProperlyConfigured(self::DEPRECATED_KEY, $value, DeprecatedIcon::class, 'setDeprecated');
        }

        if (self::SOURCE_KEY === $name) {
            if (is_string($value) && '' !== $value) {
                return $this->setSource($value);
            }

            throw new Exception\IconOptionIsNotProperlyConfigured(self::SOURCE_KEY, $value, 'non-empty-string', 'setSource');
        }

        /* @phpstan-ignore assign.propertyType (until https://github.com/phpstan/phpstan/issues/8438 is fixed) */
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * @param non-empty-string $name
     *
     * @throws Exception\IconSourceOptionCannotBeRemoved
     */
    public function removeOption(string $name): self
    {
        if (self::SOURCE_KEY === $name && $this->isCommonIconProvider()) {
            throw new Exception\IconSourceOptionCannotBeRemoved();
        }

        unset($this->options[$name]);

        return $this;
    }

    /**
     * @throws Exception\IconSourceOptionIsMissing
     */
    public function toArray(): array
    {
        $this->validate();

        $iconArray = $this->options;
        $iconArray['provider'] = $this->iconProvider;

        return $iconArray;
    }

    /**
     * @throws Exception\IconSourceOptionIsMissing
     */
    private function validate(): void
    {
        if (!isset($this->options[self::SOURCE_KEY]) && $this->isCommonIconProvider()) {
            throw new Exception\IconSourceOptionIsMissing();
        }
    }

    private function isCommonIconProvider(): bool
    {
        return in_array(
            $this->iconProvider,
            [
                Core\Imaging\IconProvider\BitmapIconProvider::class,
                Core\Imaging\IconProvider\SvgIconProvider::class,
            ],
            true,
        );
    }
}
