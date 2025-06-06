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

namespace EliasHaeussler\Typo3ConfigObjects\Configuration;

use EliasHaeussler\Typo3ConfigObjects\ValueObject;

/**
 * IconConfiguration.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 *
 * @phpstan-import-type IconOptions from ValueObject\Icon
 *
 * @implements Configuration<array<non-empty-string, IconOptions>>
 */
final class IconConfiguration implements Configuration
{
    /**
     * @var list<ValueObject\Icon>
     */
    private array $icons = [];

    private function __construct() {}

    public static function create(): self
    {
        return new self();
    }

    public function add(ValueObject\Icon ...$icons): self
    {
        foreach ($icons as $icon) {
            $this->icons[] = $icon;
        }

        return $this;
    }

    public function toArray(): array
    {
        $configuration = [];

        foreach ($this->icons as $icon) {
            $configuration[$icon->name] = $icon->toArray();
        }

        return $configuration;
    }
}
