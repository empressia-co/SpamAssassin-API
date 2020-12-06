<?php

namespace App\Model;

interface ValueObject
{
    public function sameValueAs(ValueObject $other): bool;
}
