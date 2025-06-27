<?php

namespace StateMachine\Support;

use StateMachine\Attributes\StateMachine;
use ReflectionClass;

class ReflectionUtils
{
    /**
     * Retorna todas as classes de state machine aplicadas no model.
     *
     * @return array<class-string>
     */
    public static function getStateMachineClasses(object|string $model): array
    {
        $reflection = new ReflectionClass($model);
        $attributes = $reflection->getAttributes(StateMachine::class);

        $classes = [];

        foreach ($attributes as $attribute) {
            /** @var StateMachine $instance */
            $instance = $attribute->newInstance();

            foreach ($instance->definitionClasses as $definitionClass) {
                $classes[] = $definitionClass;
            }
        }

        return $classes;
    }

    public static function getStateMachineClass(object|string $model): ?string
    {
        return static::getStateMachineClasses($model)[0] ?? null;
    }
}
