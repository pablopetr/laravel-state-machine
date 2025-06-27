# Laravel State Machine

Laravel State Machine is a package that provides a elegant and easy way to create State Machines. 

## Installation
```
composer require laravel/state-machine
```

## Usage
To use the State Machine, you need to create a state machine class that extends the `StateMachine` class. You can define your states and transitions in this class.

```php
use Laravel\StateMachine\StateMachine;

class OrderStateMachine extends StateMachine
{
    public static function field(): string
    {
        return 'state';
    }

    public static function initialState(): string
    {
        return 'pending';
    }

    protected function states(): array
    {
        return [
            'pending',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
        ];
    }

    protected function transitions(): array
    {
        return [
            'process' => ['from' => 'pending', 'to' => 'processing'],
            'ship' => ['from' => 'processing', 'to' => 'shipped'],
            'deliver' => ['from' => 'shipped', 'to' => 'delivered'],
            'cancel' => ['from' => ['pending', 'processing'], 'to' => 'cancelled'],
        ];
    }
    
    public static function callbacks(): array
    {
        return [];
    }
     
    public static function guessModel(): string
    {
        return Order::class;
    }
}
```

You can then use this state machine in your application to manage the state of your orders.

```php
$orderStateMachine = new OrderStateMachine();
$orderStateMachine->setState('pending');
$orderStateMachine->process(); // Changes state to 'processing'
$orderStateMachine->ship(); // Changes state to 'shipped'
$orderStateMachine->deliver(); // Changes state to 'delivered'
```

You can also check if a transition is allowed:

```php
$orderStateMachine = new OrderStateMachine();
$orderStateMachine->setState('pending');
$isAllowed = $orderStateMachine->can('process'); // Returns true
$isAllowed = $orderStateMachine->can('ship'); // Returns false
$isAllowed = $orderStateMachine->can('deliver'); // Returns false
$isAllowed = $orderStateMachine->can('cancel'); // Returns true
```
