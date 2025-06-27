<?php

namespace StateMachine\Contracts;

interface DefinesStateMachine
{
    public static function field(): string;
    public static function initialState(): string;
    public static function states(): array;
    public static function transitions(): array;
    public static function callbacks(): array;
    public static function guessModel(): string;
}
