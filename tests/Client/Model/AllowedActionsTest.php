<?php

namespace App\Tests\Client\Model;

use App\Client\Model\AllowedActions;
use PHPUnit\Framework\TestCase;

class AllowedActionsTest extends TestCase
{
    public function testSameValueWhenDifferentOrder()
    {
        $one = new AllowedActions(['ACTION_CREATE', 'ACTION_DELETE']);
        $two = new AllowedActions(['ACTION_DELETE', 'ACTION_CREATE']);

        $this->assertTrue($one->sameValueAs($two));
    }

    public function testNotSameValueWhenDifferentLength()
    {
        $one = new AllowedActions(['ACTION_CREATE', 'ACTION_DELETE']);
        $two = new AllowedActions(['ACTION_DELETE', 'ACTION_CREATE', 'ACTION_SHOW']);

        $this->assertFalse($one->sameValueAs($two));
    }

    public function testNotSameValueWhenOneIsEmpty()
    {
        $one = new AllowedActions([]);
        $two = new AllowedActions(['ACTION_DELETE', 'ACTION_CREATE', 'ACTION_DELETE']);

        $this->assertFalse($one->sameValueAs($two));
    }

    public function testSameValueWhenBothEmpty()
    {
        $one = new AllowedActions([]);
        $two = new AllowedActions([]);

        $this->assertTrue($one->sameValueAs($two));
    }

    public function testSameValueAddedOnce()
    {
        $this->assertSame(['ACTION_SHOW'], (new AllowedActions(['ACTION_SHOW', 'ACTION_SHOW']))->actions());
    }

    public function testCanDisallowNonExistent()
    {
        $actions = new AllowedActions([]);

        $this->assertNull($actions->disallow('ACTION_SHOW'));
    }

    public function testDisallow()
    {
        $actions = new AllowedActions(['ACTION_SHOW', 'ACTION_CREATE']);

        $actions->disallow('ACTION_SHOW');

        $this->assertNotContains('ACTION_SHOW', $actions->actions());
    }

    public function testIsAllowedCaseInsensitive()
    {
        $actions = new AllowedActions(['ACTION_SHOW', 'ACTION_CREATE']);

        $this->assertTrue($actions->isAllowed('action_create'));
    }

    public function testDisallowCaseInsensitive()
    {
        $actions = new AllowedActions(['ACTION_SHOW', 'ACTION_CREATE']);

        $actions->disallow('action_show');

        $this->assertNotContains('ACTION_SHOW', $actions->actions());
    }
}
