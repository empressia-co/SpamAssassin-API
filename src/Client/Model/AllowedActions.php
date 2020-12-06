<?php

namespace App\Client\Model;

use App\Model\ValueObject;

final class AllowedActions implements ValueObject
{
    /**
     * @var string[]
     */
    public array $actions = [];

    /**
     * @param string[]
     */
    public static function createFromArray(array $actions): self
    {
        return new self($actions);
    }

    /**
     * @param string[]
     */
    public function __construct(array $actions)
    {
        foreach ($actions as $action) {
            $this->allow($action);
        }
    }

    /** @return string[] */
    public function actions(): array
    {
        return $this->actions;
    }

    public function allow(string $action): void
    {
        if (!$this->isAllowed($action)) {
            $this->actions[] = \strtoupper($action);

            sort($this->actions);
        }
    }

    public function disallow(string $action): void
    {
        if ((false !== $index = \array_search(\strtoupper($action), $this->actions))) {
            \array_splice($this->actions, $index, 1);
        }
    }

    public function isAllowed(string $action): bool
    {
        return \in_array(\strtoupper($action), $this->actions, true);
    }

    public function sameValueAs(ValueObject $other): bool
    {
        // we assume actions array is always sorted

        return $other instanceof AllowedActions && $this->actions === $other->actions;
    }
}
