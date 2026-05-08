<?php

declare(strict_types=1);

namespace Core;

use Closure;
use ReflectionClass;
use RuntimeException;

final class Container
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            throw new RuntimeException('Container has not been initialized.');
        }
        return self::$instance;
    }

    public static function setInstance(self $container): void
    {
        self::$instance = $container;
    }

    /** @var array<string, Closure> */
    private array $bindings = [];

    /** @var array<string, object> */
    private array $singletons = [];

    /** @var array<string, true> */
    private array $singletonKeys = [];

    public function bind(string $abstract, Closure $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    public function singleton(string $abstract, Closure $factory): void
    {
        $this->bindings[$abstract] = $factory;
        $this->singletonKeys[$abstract] = true;
    }

    public function make(string $abstract): object
    {
        if (isset($this->singletonKeys[$abstract])) {
            if (!isset($this->singletons[$abstract])) {
                $this->singletons[$abstract] = ($this->bindings[$abstract])($this);
            }
            return $this->singletons[$abstract];
        }

        if (isset($this->bindings[$abstract])) {
            return ($this->bindings[$abstract])($this);
        }

        return $this->autowire($abstract);
    }

    private function autowire(string $class): object
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException("Cannot instantiate [{$class}].");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $args = array_map(
            function (\ReflectionParameter $param) use ($class): object {
                $type = $param->getType();

                if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                    throw new RuntimeException(
                        "Cannot resolve parameter [{$param->getName()}] in [{$class}]."
                    );
                }

                return $this->make($type->getName());
            },
            $constructor->getParameters()
        );

        return $reflection->newInstanceArgs($args);
    }
}
