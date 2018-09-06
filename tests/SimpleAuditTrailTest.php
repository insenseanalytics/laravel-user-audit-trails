<?php

namespace Insense\LaravelUserAuditTrails\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

class SimpleAuditTrailTest extends TestCase
{
    /** @test */
    public function it_provides_usertrails_macro()
    {
        $this->assertTrue(Blueprint::hasMacro('usertrails'));
        $this->assertTrue(Blueprint::hasMacro('dropUsertrails'));
    }

    /** @test */
    public function it_creates_the_audit_user_trail_columns()
    {
        $this->assertTrue(DB::schema()->hasColumn('posts', 'created_by'));
        $this->assertTrue(DB::schema()->hasColumn('posts', 'updated_by'));
    }

    /** @test */
    public function it_creates_custom_audit_user_trail_columns()
    {
        $this->assertTrue(DB::schema()->hasColumn('comments', 'createdBy'));
        $this->assertTrue(DB::schema()->hasColumn('comments', 'updatedBy'));
    }

    /** @test */
    public function it_omits_null_audit_user_trail_columns()
    {
        $this->assertTrue(DB::schema()->hasColumn('pages', 'created_by'));
        $this->assertFalse(DB::schema()->hasColumn('pages', 'updated_by'));
    }

    /** @test */
    public function it_drops_the_audit_user_trail_columns()
    {
        $this->dropUserTrailColumns();
        $this->assertFalse(DB::schema()->hasColumn('posts', 'created_by'));
        $this->assertFalse(DB::schema()->hasColumn('posts', 'updated_by'));
    }

    /** @test */
    public function it_drops_custom_audit_user_trail_columns()
    {
        $this->dropUserTrailColumns();
        $this->assertFalse(DB::schema()->hasColumn('comments', 'createdBy'));
        $this->assertFalse(DB::schema()->hasColumn('comments', 'updatedBy'));
    }

    /** @test */
    public function it_drops_null_audit_user_trail_columns()
    {
        $this->dropUserTrailColumns();
        $this->assertFalse(DB::schema()->hasColumn('pages', 'created_by'));
    }

    /** @test */
    public function it_contains_null_audit_fields_if_not_authenticated()
    {
        $post = $this->makePost();

        $this->assertTrue(is_null($post->created_by));
        $this->assertTrue(is_null($post->updated_by));
    }
    
       
    /** @test */
    public function it_provides_deletetrails_macro()
    {
        $this->assertTrue(Blueprint::hasMacro('deletetrails'));
        $this->assertTrue(Blueprint::hasMacro('dropDeletetrails'));
    }
    
    /** @test */
    public function it_checks_the_audit_user_delete_trail_columns()
    {
        $this->assertTrue(DB::schema()->hasColumn('posts_dt', 'deleted_by'));
        $this->assertTrue(DB::schema()->hasColumn('comments_dt', 'deletedBy'));
        $this->assertTrue(DB::schema()->hasColumn('pages_dt', 'deletedByUserId'));
    }
    
    /** @test */
    public function it_drops_the_audit_delete_trail_columns()
    {
        $this->dropDeleteTrailColumns();
        $this->assertFalse(DB::schema()->hasColumn('posts_dt', 'deleted_by'));
    }
    
    /** @test */
    public function it_drops_the_audit_delete_trail_custom_columns()
    {
        $this->dropDeleteTrailColumns();
        $this->assertFalse(DB::schema()->hasColumn('comments_dt', 'deletedBy'));
    }
    
    /** @test */
    public function it_omits_non_soft_deleted_delete_trail_columns() {
        $this->dropDeleteTrailColumns();
        $this->assertFalse(DB::schema()->hasColumn('pages_dt', 'deletedByUserId'));  
    }
}
