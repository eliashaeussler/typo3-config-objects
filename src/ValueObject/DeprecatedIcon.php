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

/**
 * DeprecatedIcon.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 *
 * @implements Contracts\Arrayable<array{
 *     since: non-empty-string|null,
 *     until: non-empty-string|null,
 *     replacement: non-empty-string|null,
 * }>
 */
final class DeprecatedIcon implements Contracts\Arrayable
{
    /**
     * @param non-empty-string|null $since
     * @param non-empty-string|null $until
     * @param non-empty-string|null $replacement
     */
    public function __construct(
        public readonly ?string $since = null,
        public readonly ?string $until = null,
        public readonly ?string $replacement = null,
    ) {}

    public function toArray(): array
    {
        return [
            'since' => $this->since,
            'until' => $this->until,
            'replacement' => $this->replacement,
        ];
    }
}
