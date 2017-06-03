<?php

use Illuminate\Database\Seeder;
use App\Models\Post;

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
        'published_at' => DB::raw('CURRENT_TIMESTAMP'),
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
