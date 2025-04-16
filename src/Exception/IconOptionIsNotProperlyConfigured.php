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

namespace EliasHaeussler\Typo3ConfigObjects\Exception;

use EliasHaeussler\Typo3ConfigObjects\ValueObject;

use function class_exists;
use function get_debug_type;
use function sprintf;

/**
 * IconOptionIsNotProperlyConfigured.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
final class IconOptionIsNotProperlyConfigured extends Exception
{
    public function __construct(string $name, mixed $value, string $expectedType, string $alternativeMethodCall)
    {
        if (class_exists($expectedType)) {
            $expectedType = 'an instance of '.$expectedType;
        } else {
            $expectedType = 'of type '.$expectedType;
        }

        parent::__construct(
            sprintf(
                'The "%s" icon option must be %s, %s given. Please use the %s::%s() method instead.',
                $name,
                $expectedType,
                get_debug_type($value),
                ValueObject\Icon::class,
                $alternativeMethodCall,
            ),
            1744790988,
        );
    }
}
