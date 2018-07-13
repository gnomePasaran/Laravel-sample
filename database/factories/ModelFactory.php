<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vote;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Post::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'content' => $faker->name,
        'published' => true,
        'published_at' => Carbon\Carbon::now(),
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});

$factory->define(Answer::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->name,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'post_id' => function () {
            return factory(Post::class)->create()->id;
        },
    ];
});

$factory->define(Comment::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->name,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'commentable_id' => function () {
            return factory(Post::class)->create()->id;
        },
        'commentable_type' => function () {
            return factory(Post::class)->create()->type;
        },
    ];
});

$factory->define(Subscription::class, function () {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'post_id' => function () {
            return factory(Post::class)->create()->id;
        },
    ];
});

$factory->define(Vote::class, function () {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'votable_id' => function () {
            return factory(Post::class)->create()->id;
        },
        'votable_type' => function () {
            return Post::class;
        },
        'score' => rand(-1, 1),
    ];
});
