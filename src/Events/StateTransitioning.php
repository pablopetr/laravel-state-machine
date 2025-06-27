<?php

namespace StateMachine\Events;

class StateTransitioning
{
    public function __construct(
        public object $model,
        public string $from,
        public string $to,
        public string $action
    ) {}
}
