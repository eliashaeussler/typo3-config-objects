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
use Psr\Http\Server;

/**
 * RequestMiddleware.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 *
 * @phpstan-type MiddlewareOptions array{
 *     target?: class-string<Server\MiddlewareInterface>,
 *     disabled?: bool,
 *     before?: list<non-empty-string>,
 *     after?: list<non-empty-string>,
 * }
 *
 * @implements Contracts\Arrayable<MiddlewareOptions>
 */
final class RequestMiddleware implements Contracts\Arrayable
{
    /**
     * @var class-string<Server\MiddlewareInterface>|null
     */
    private ?string $target = null;
    private ?bool $disabled = null;

    /**
     * @var array{before: list<non-empty-string>, after: list<non-empty-string>}
     */
    private array $dependencies = [
        'before' => [],
        'after' => [],
    ];

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public readonly string $name,
    ) {}

    /**
     * @param non-empty-string $name
     */
    public static function create(string $name): self
    {
        return new self($name);
    }

    /**
     * @param class-string<Server\MiddlewareInterface> $target
     */
    public function setTarget(string $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function disable(): self
    {
        $this->disabled = true;

        return $this;
    }

    public function enable(): self
    {
        $this->disabled = false;

        return $this;
    }

    /**
     * @param non-empty-string ...$dependencies
     */
    public function before(string ...$dependencies): self
    {
        foreach ($dependencies as $dependency) {
            $this->dependencies['before'][] = $dependency;
        }

        return $this;
    }

    /**
     * @param non-empty-string ...$dependencies
     */
    public function after(string ...$dependencies): self
    {
        foreach ($dependencies as $dependency) {
            $this->dependencies['after'][] = $dependency;
        }

        return $this;
    }

    /**
     * @throws Exception\MiddlewareTargetIsMissing
     */
    public function toArray(): array
    {
        $this->validate();

        $middlewareArray = [];

        if (null !== $this->target) {
            $middlewareArray['target'] = $this->target;
        }
        if (null !== $this->disabled) {
            $middlewareArray['disabled'] = $this->disabled;
        }
        if ([] !== $this->dependencies['before']) {
            $middlewareArray['before'] = $this->dependencies['before'];
        }
        if ([] !== $this->dependencies['after']) {
            $middlewareArray['after'] = $this->dependencies['after'];
        }

        return $middlewareArray;
    }

    /**
     * @throws Exception\MiddlewareTargetIsMissing
     */
    private function validate(): void
    {
        if (null === $this->target
            && null === $this->disabled
            && [] === $this->dependencies['before']
            && [] === $this->dependencies['after']
        ) {
            throw new Exception\MiddlewareTargetIsMissing();
        }
    }
}
