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

namespace EliasHaeussler\Typo3ConfigObjects\Configuration;

use EliasHaeussler\Typo3ConfigObjects\ValueObject;

/**
 * MiddlewareConfiguration.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 *
 * @phpstan-import-type MiddlewareOptions from ValueObject\RequestMiddleware
 *
 * @implements Configuration<array{
 *     backend: array<string, MiddlewareOptions>,
 *     frontend: array<string, MiddlewareOptions>,
 * }>
 */
final class MiddlewareConfiguration implements Configuration
{
    /**
     * @var list<ValueObject\RequestMiddleware>
     */
    private array $backendStack = [];

    /**
     * @var list<ValueObject\RequestMiddleware>
     */
    private array $frontendStack = [];

    private function __construct() {}

    public static function create(): self
    {
        return new self();
    }

    public function addToBackendStack(ValueObject\RequestMiddleware ...$requestMiddlewares): self
    {
        foreach ($requestMiddlewares as $requestMiddleware) {
            $this->backendStack[] = $requestMiddleware;
        }

        return $this;
    }

    public function addToFrontendStack(ValueObject\RequestMiddleware ...$requestMiddlewares): self
    {
        foreach ($requestMiddlewares as $requestMiddleware) {
            $this->frontendStack[] = $requestMiddleware;
        }

        return $this;
    }

    public function toArray(): array
    {
        $configuration = [
            'backend' => [],
            'frontend' => [],
        ];

        foreach ($this->backendStack as $requestMiddleware) {
            $configuration['backend'][$requestMiddleware->name] = $requestMiddleware->toArray();
        }

        foreach ($this->frontendStack as $requestMiddleware) {
            $configuration['frontend'][$requestMiddleware->name] = $requestMiddleware->toArray();
        }

        return $configuration;
    }
}
