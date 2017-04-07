<?php

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;

class ArrayHelper
{
    public static function getArrayFromObject($object)
    {
        $reflectionClass = new ReflectionClass(get_class($object));

        $array = [];
        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {

            if ($method->isConstructor() || $method->getNumberOfRequiredParameters() > 0) {
                continue;
            }

            $invokeResult = $method->invoke($object);

            if (is_array($invokeResult) && sizeof($invokeResult) > 0 && is_object($invokeResult[0])) {
                $array[ArrayHelper::getPropertyName($method->getName())] = ArrayHelper::getArrayFromArrayOfObjects($invokeResult);
            } else if (is_object($invokeResult) && $invokeResult instanceof Collection) {
                $array[ArrayHelper::getPropertyName($method->getName())] = ArrayHelper::getArrayFromArrayOfObjects($invokeResult->toArray());
            } else if (is_object($invokeResult)) {
                $array[ArrayHelper::getPropertyName($method->getName())] = ArrayHelper::getArrayFromObject($invokeResult);
            } else {
                $array[ArrayHelper::getPropertyName($method->getName())] = $invokeResult;
            }
        }

        return $array;
    }

    public static function getArrayFromArrayOfObjects($objects)
    {
        $array = [];

        foreach ($objects as $object) {
            array_push($array, ArrayHelper::getArrayFromObject($object));
        }

        return $array;
    }

    public static function getFloatArrayFromString($value, $delimiter = ",")
    {
        $values = explode($delimiter, $value);

        return array_map(function ($value) {
            return floatval($value);
        }, $values);
    }

    private static function getPropertyName(string $method_name): string
    {
        $modifiers = ['App\Helpers\ArrayHelper::removeGet', 'App\Helpers\ArrayHelper::lowerCaseFirstLetter', 'App\Helpers\ArrayHelper::replaceCapitalisedWithUnderscore'];
        $newMethodName = $method_name;

        foreach ($modifiers as $modifier) {
            $newMethodName = call_user_func($modifier, $newMethodName);
        }

        return $newMethodName;
    }

    private static function removeGet($method_name)
    {
        if (starts_with($method_name, 'get')) {
            return substr($method_name, 3);
        }

        return $method_name;
    }

    private static function lowerCaseFirstLetter($method_name)
    {
        $newMethodName = $method_name;
        $newMethodName[0] = strtolower($newMethodName[0]);

        return $newMethodName;
    }

    private static function replaceCapitalisedWithUnderscore($method_name)
    {
        $newMethodName = $method_name;
        $index = 0;
        while (strlen($newMethodName) > $index) {
            if (ctype_upper($newMethodName[$index])) {
                $newMethodName[$index] = strtolower($newMethodName[$index]);
                $newMethodName = ArrayHelper::insertAtPosition($newMethodName, "_", $index - 1);
                $index = 0;
            }
            $index++;
        }

        return $newMethodName;
    }

    private static function insertAtPosition(string $string, string $insert, int $position)
    {
        return substr($string, 0, $position + 1) . $insert . substr($string, $position + 1, strlen($string) - $position - 1);
    }
}
