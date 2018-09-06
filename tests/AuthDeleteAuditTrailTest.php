<?php

namespace Insense\LaravelUserAuditTrails\Tests;

class AuthDeleteAuditTrailTest extends TestCase
{
    /** @test */
    public function it_updates_updated_deleted_audit_user_trails()
    {
        $firstUser = $this->makeThirdUser();
        $this->actingAs($firstUser);
        $post = $this->makePostDt();

        $secondUser = $this->makeSecondUser();
        $this->actingAs($secondUser);

        $post->delete();
        $this->assertSame($firstUser->id, $post->created_by);
        $this->assertSame($firstUser->id, $post->updated_by);
        $this->assertSame($secondUser->id, $post->deleted_by);

    }

    /** @test */
    public function it_updates_custom_created_updated_deleted_audit_delete_trails()
    {
        $user = $this->makeThirdUser();
        $this->actingAs($user);
        $comment = $this->makeCommentDt();

        $this->assertSame($user->id, $comment->createdBy);
        $this->assertSame($user->id, $comment->updatedBy);
        
        $comment->delete();
        $this->assertSame($user->id, $comment->deletedBy);
    }

    /** @test */
    public function it_updates_deleted_audit_user_info_to_latest_user_trails()
    {
        $firsrtUser = $this->makeThirdUser();
        $post = $this->makePostDt();
        $this->actingAs($firsrtUser);
        $post->delete();
        $this->assertSame($firsrtUser->id, $post->deleted_by);
        
        $secondUser = $this->makeSecondUser();
        $this->actingAs($secondUser);
        
        $post->restore();
        $post->delete();
        
        $this->assertSame($secondUser->id, $post->deleted_by);
        
    }


    /** @test */
    public function it_respects_manual_audit_delete_trails()
    {
        $user = $this->makeUser();
        $this->actingAs($user);
        $post = $this->makePostDt();
        $post->title = 'New Title';
        $post->updated_by = 5;
        $post->deleted_by = 7;
        $post->save();

        $this->assertSame(5, $post->updated_by);
        $this->assertSame(7, $post->deleted_by);
    }
    
    /** @test */
    public function it_does_not_applied_non_using_deleted_trails()
    {
        $user = $this->makeThirdUser();
        $this->actingAs($user);
        $page = $this->makePageDt();

        $this->assertSame($user->id, $page->created_by);
        $this->assertTrue(is_null($page->updated_by));
        $this->assertTrue(is_null($page->deleted_by));
    }
}
