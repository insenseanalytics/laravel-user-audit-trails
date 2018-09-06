<?php

namespace Insense\LaravelUserAuditTrails\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

class AuthDeleteAuditTrailTest extends TestCase
{
    /** @test */
    public function it_updates_created_deleted_audit_user_trails()
    {
        $user = $this->makeThirdUser();
        $this->actingAs($user);
        $post = $this->makePageDt();

        $this->assertSame($user->id, $post->created_by);
        $this->assertSame($user->id, $post->updated_by);

        $post->delete();
        $this->assertSame($user->id, $post->deleted_by);

    }

    /** @test */
    public function it_updates_custom_created_deleted_audit_delete_trails()
    {
        $user = $this->makeThirdUser();
        $this->actingAs($user);
        $comment = $this->makeCommentDt();

        $this->assertSame($user->id, $comment->createdBy);
        $this->assertSame($user->id, $comment->updatedBy);
        
        $commentDT = $this->deleteCommentDT();
        $this->assertSame($user->id, $commentDT->deletedBy);
    }

    /** @test */
    public function it_updates_deleted_audit_user_info_to_latest_user_trails()
    {
        $user = $this->makeThirdUser();
        $post = $this->makePostDt();
        $this->actingAs($user);
        $post->delete();
        $this->assertSame($user->id, $post->deleted_by);
        
        $user2 = $this->makeSecondUser();
        $this->restorePostDT($post->id);
        $post->delete();
        
        $this->assertSame($user2->id, $post->deleted_by);
        
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

//    /** @test */
//    public function it_does_not_update_createdby_on_updation()
//    {
//        $user = $this->makeUser();
//        $this->actingAs($user);
//        $post = $this->makePost();
//
//        $secondUser = $this->makeSecondUser();
//        $this->actingAs($secondUser);
//        $post->title = 'New Title';
//        $post->save();
//
//        $this->assertSame($user->id, $post->created_by);
//        $this->assertSame($secondUser->id, $post->updated_by);
//    }
//
//    /** @test */
//    public function it_updates_user_audit_trail_on_touch()
//    {
//        $user = $this->makeUser();
//        $this->actingAs($user);
//        $post = $this->makePost();
//
//        $secondUser = $this->makeSecondUser();
//        $this->actingAs($secondUser);
//        $post->touchUserTrails();
//
//        $this->assertSame($user->id, $post->created_by);
//        $this->assertSame($secondUser->id, $post->updated_by);
//    }
}
