<?php

namespace App\Client\DoctrineType;

use App\Client\Model\AllowedActions;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

final class AllowedActionsType extends Type
{
    // This trait provides default closureToPHP used during data hydration
    use ClosureToPHP;

    public function convertToPHPValue($value): ?AllowedActions
    {
        if (null === $value) {
            return null;
        }

        if (!\is_array($value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Could not convert %s to a date value',
                    is_scalar($value) ? '"' . $value . '"' : gettype($value)
                )
            );
        }

        return AllowedActions::createFromArray($value);
    }

    public function convertToDatabaseValue($value): ?array
    {
        if (!$value instanceof AllowedActions) {
            throw MongoDBException::invalidValueForType(
                'AllowedActions',
                [AllowedActions::class, 'null'],
                $value
            );
        }

        return $value->actions();
    }
}
