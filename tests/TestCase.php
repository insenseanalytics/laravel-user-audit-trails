<?php

namespace Insense\LaravelUserAuditTrails\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Insense\LaravelUserAuditTrails\UserTrailsServiceProvider;
use Insense\LaravelUserAuditTrails\HasUserTrails;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
        $this->migrateTables();
    }

    protected function getPackageProviders($app)
    {
        return [UserTrailsServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase()
    {
        $database = new DB;

        $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $database->bootEloquent();
        $database->setAsGlobal();
    }

    protected function migrateTables()
    {
        DB::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        DB::schema()->create('posts', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->usertrails();
        });

        DB::schema()->create('comments', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->usertrails('createdBy', 'updatedBy');
        });

        DB::schema()->create('pages', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->usertrails('created_by', null);
        });
    }

    protected function dropUserTrailColumns()
    {
        DB::schema()->table('posts', function ($table) {
            $table->dropUsertrails();
        });

        DB::schema()->table('comments', function ($table) {
            $table->dropUsertrails('createdBy', 'updatedBy');
        });

        DB::schema()->table('pages', function ($table) {
            $table->dropUsertrails('created_by', null);
        });
    }

    protected function makePost()
    {
        $post = new Post;
        $post->title = 'Some title';
        $post->save();
        return $post;
    }

    protected function makeComment()
    {
        $comment = new Comment;
        $comment->title = 'Some title';
        $comment->save();
        return $comment;
    }

    protected function makePage()
    {
        $page = new Page;
        $page->title = 'Some title';
        $page->save();
        return $page;
    }

    protected function makeUser()
    {
        $user = new User;
        $user->first_name = 'Paras';
        $user->last_name = 'Malhotra';
        $user->email = 'paras@insenseanalytics.com';
        $user->save();
        return $user;
    }

    protected function makeSecondUser()
    {
        $user = new User;
        $user->first_name = 'Paras';
        $user->last_name = 'Malhotra';
        $user->email = 'paras@test.com';
        $user->save();
        return $user;
    }
}

class BaseModel extends Model
{
    use HasUserTrails;
}

class Post extends Model
{
    use HasUserTrails;
}

class Comment extends BaseModel
{
    public static $CREATED_BY = 'createdBy';
    public static $UPDATED_BY = 'updatedBy';
}

class Page extends BaseModel
{
    public static $UPDATED_BY = null;
}

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;
}
