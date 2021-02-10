<?php

namespace App\Model;

interface Entity
{
    public function sameAs(Entity $other): bool;
}
