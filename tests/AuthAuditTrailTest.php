<?php

namespace Insense\LaravelUserAuditTrails\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

class AuthAuditTrailTest extends TestCase
{
    /** @test */
    public function it_updates_created_audit_user_trails()
    {
        $user = $this->makeUser();
        $this->actingAs($user);
        $post = $this->makePost();

        $this->assertSame($user->id, $post->created_by);
        $this->assertSame($user->id, $post->updated_by);
    }

    /** @test */
    public function it_updates_custom_created_audit_user_trails()
    {
        $user = $this->makeUser();
        $this->actingAs($user);
        $comment = $this->makeComment();

        $this->assertSame($user->id, $comment->createdBy);
        $this->assertSame($user->id, $comment->updatedBy);
    }

    /** @test */
    public function it_updates_updated_audit_user_trails()
    {
        $user = $this->makeUser();
        $post = $this->makePost();
        $this->actingAs($user);
        $post->title = 'New Title';
        $post->save();

        $this->assertTrue(is_null($post->created_by));
        $this->assertSame($user->id, $post->updated_by);
    }

    /** @test */
    public function it_updates_custom_updated_audit_user_trails()
    {
        $user = $this->makeUser();
        $comment = $this->makeComment();
        $this->actingAs($user);
        $comment->title = 'New Title';
        $comment->save();

        $this->assertTrue(is_null($comment->created_by));
        $this->assertSame($user->id, $comment->updatedBy);
    }

    /** @test */
    public function it_respects_manual_audit_user_trails()
    {
        $user = $this->makeUser();
        $this->actingAs($user);
        $post = $this->makePost();
        $post->title = 'New Title';
        $post->updated_by = 5;
        $post->save();

        $this->assertSame(5, $post->updated_by);
    }

    /** @test */
    public function it_does_not_update_createdby_on_updation()
    {
        $user = $this->makeUser();
        $this->actingAs($user);
        $post = $this->makePost();

        $secondUser = $this->makeSecondUser();
        $this->actingAs($secondUser);
        $post->title = 'New Title';
        $post->save();

        $this->assertSame($user->id, $post->created_by);
        $this->assertSame($secondUser->id, $post->updated_by);
    }

    /** @test */
    public function it_updates_user_audit_trail_on_touch()
    {
        $user = $this->makeUser();
        $this->actingAs($user);
        $post = $this->makePost();

        $secondUser = $this->makeSecondUser();
        $this->actingAs($secondUser);
        $post->touchUserTrails();

        $this->assertSame($user->id, $post->created_by);
        $this->assertSame($secondUser->id, $post->updated_by);
    }
}
