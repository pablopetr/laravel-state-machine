<?php

namespace StateMachine\Events;

class StateTransitioned
{
    public function __construct(
        public object $model,
        public string $from,
        public string $to,
        public string $action
    ) {}
}
