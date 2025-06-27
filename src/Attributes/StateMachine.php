<?php

namespace StateMachine\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class StateMachine
{
    /**
     * @param array<class-string> $definitionClasses
     */
    public function __construct(public array $definitionClasses) {}
}
