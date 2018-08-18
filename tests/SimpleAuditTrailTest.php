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
}
