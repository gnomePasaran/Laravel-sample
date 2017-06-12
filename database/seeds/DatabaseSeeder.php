<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
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
      DB::table('posts')->delete();
      Post::create([
        'title' => 'First-post',
        'slug' => 'first-post',
        'excerpt' => '<b>First Post body</b>',
        'content' => '<b>Content First Post body</b>',
        'published' => true,
        'published_at' => DB::raw('CURRENT_TIMESTAMP'),
      ]);

      Post::create([
        'title' => 'Second-post',
        'slug' => 'second-post',
        'excerpt' => '<b>Second Post body</b>',
        'content' => '<b>Second First Post body</b>',
        'published' => false,
      ]);

      Post::create([
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
        Answer::create([
            'post_id' => Post::find(1)->id,
            'content' => 'Content 1'
        ]);

        Answer::create([
            'post_id' => Post::find(2)->id,
            'content' => 'Content 2'
        ]);

        Answer::create([
            'post_id' => Post::find(3)->id,
            'content' => 'Content 3'
        ]);
    }
}
