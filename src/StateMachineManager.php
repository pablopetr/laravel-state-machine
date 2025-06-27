<?php

namespace StateMachine;

use StateMachine\Exceptions\InvalidTransitionException;
use StateMachine\Contracts\DefinesStateMachine;
use StateMachine\Events\StateTransitioning;
use StateMachine\Events\StateTransitioned;
use StateMachine\Support\ReflectionUtils;

class StateMachineManager
{
    public function __construct(
        protected object $model,
        protected DefinesStateMachine $definition
    ) {}

    public static function for(object $model): self
    {
        $definitionClass = ReflectionUtils::getStateMachineClass($model);

        return new self($model, new $definitionClass);
    }

    public function current(): string
    {
        return $this->model->{$this->definition::field()};
    }

    public function can(string $action): bool
    {
        $current = $this->current();
        $transition = $this->definition::transitions()[$action] ?? null;

        return $transition && in_array($current, $transition['from']);
    }

    public function transition(string $action): void
    {
        $transitions = $this->definition::transitions();
        $field = $this->definition::field();
        $current = $this->current();

        if (!isset($transitions[$action]) || !in_array($current, $transitions[$action]['from'])) {
            throw new InvalidTransitionException("Invalid transition '$action' from '$current'");
        }

        $to = $transitions[$action]['to'];

        event(new StateTransitioning($this->model, $current, $to, $action));

        $this->model->{$field} = $to;
        $this->model->save();

        $callback = $this->definition::callbacks()["after:$action"] ?? null;

        if ($callback) {
            (new $callback)($this->model);
        }

        event(new StateTransitioned($this->model, $current, $to, $action));
    }

    public static function allFor(object $model): array
    {
        $classes = ReflectionUtils::getStateMachineClasses($model);

        return array_map(
            fn(string $class) => new self($model, new $class),
            $classes
        );
    }
}
