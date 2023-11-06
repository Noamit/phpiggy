<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\ContainerException;
use ReflectionClass, ReflectionNamedType;

class Container
{
    private array $definitions = [];

    public function addDefinitions(array $newDefinitions)
    {
        $this->definitions = [...$this->definitions, ...$newDefinitions];
    }

    public function resolve(string $classname)
    {
        $reflectionClass = new ReflectionClass($classname);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$classname} is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $classname;
        }

        $params = $constructor->getParameters();

        if (count($params) === 0) {
            return new $classname;
        }

        $dependencies = [];
        foreach ($params as $param) {
            $name = $param->getName();
            $type = $param->getType();
            //class must have type hint for reflection 
            if (!$type) {
                throw new ContainerException(
                    "Failed to resolve class {$classname} because param {$name} is missing a type hint"
                );
            }

            //$type->isBuiltin() == true -> means that the type is not a class
            //the type should be ReflectionNamedType and not built-in
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerException(
                    "Failed to resolve class {$classname} because invalid param {$name}"
                );
            }
            $dependencies[] = $this->get($type->getName());
        };

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get(string $id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ContainerException("Class {$id} does not exist in container");
        }
        $factory = $this->definitions[$id];
        $dependency = $factory(); // $factory($this);
        return $dependency;
    }
}
