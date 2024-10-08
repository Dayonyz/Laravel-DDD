<?php

namespace App\Domains\Shared\Application\Bus;

use App\Domains\Shared\Application\Exceptions\PayloadConvertException;
use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;

trait ConvertablePayload
{
    /**
     * @throws ReflectionException|BindingResolutionException
     */
    public static function fromArray(array $payload): static
    {
        $class = get_called_class();
        $reflection = new \ReflectionClass($class);

        $constructParams = array_map(function ($param) {
            return ['name' => $param->getName(), 'type' => $param->getType()->getName()];
        }, $reflection->getMethod('__construct')->getParameters());

        $payloadValues = [];
        foreach ($constructParams as $param) {
            $payloadValues[$param['name']] = $payload[$param['name']] ?? null;
            #TODO refactoring with type converting
//            if (isset($payload[$param['name']]) && settype($payload[$param['name']], $param['type'])) {
//                $payloadValues[] = $payload[$param['name']];
//            } else {
//                throw new PayloadConvertException(
//                    "Payload parameter '{$param['name']}' is absent or has incorrect type"
//                );
//            }
        }

        return app()->make($class, $payloadValues);
    }
}