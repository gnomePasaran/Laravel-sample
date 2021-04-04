<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Answer;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PostsSeeder::class);
        $this->call(AnswersSeeder::class);
    }
}

class PostsSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'name',
            'email' => 'a@a.com',
            'password' => '123456',
            'remember_token' => str_random(10),
        ]);

        DB::table('posts')->delete();
        Post::create([
            'user_id' => $user->id,
            'title' => 'First-post',
            'slug' => 'first-post',
            'excerpt' => '<b>First Post body</b>',
            'content' => '<b>Content First Post body</b>',
            'published' => true,
            'published_at' => DB::raw('CURRENT_TIMESTAMP'),
        ]);

        Post::create([
            'user_id' => $user->id,
            'title' => 'Second-post',
            'slug' => 'second-post',
            'excerpt' => '<b>Second Post body</b>',
            'content' => '<b>Second First Post body</b>',
            'published' => false,
      ]);

        Post::create([
            'user_id' => $user->id,
            'title' => 'Third-post',
            'slug' => 'third-post',
            'excerpt' => '<b>Third Post body</b>',
            'content' => '<b>Third First Post body</b>',
            'published' => true,
            'published_at' => DB::raw('CURRENT_TIMESTAMP'),
        ]);
    }
}

class AnswersSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'name',
            'email' => 'a2@a.com',
            'password' => '123456',
            'remember_token' => str_random(10),
        ]);

        Answer::create([
            'user_id' => $user->id,
            'post_id' => Post::find(1)->id,
            'content' => 'Content 1'
        ]);

        Answer::create([
            'user_id' => $user->id,
            'post_id' => Post::find(2)->id,
            'content' => 'Content 2'
        ]);

        Answer::create([
            'user_id' => $user->id,
            'post_id' => Post::find(3)->id,
            'content' => 'Content 3'
        ]);
    }
}
