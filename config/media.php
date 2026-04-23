<?php

return [
    'base_path' => 'uploads',
    'users_base_path' => 'uploads/users',

    'media_size' => [
        'other' => 1024 * 4,
        'creator_identity' => 1024 * 5,
        'creator_selfie' => 1024 * 5,
        'short_video' => 1024 * 20,
        'avatar' => 1024 * 4,
        'article_image' => 1024 * 4,
        'course_thumbnail' => 1024 * 4,
        'lesson_video' => 1024 * 20,
    ],

    'media_format' => [
        'other' => 'jpg,jpeg,png',
        'creator_identity' => 'jpg,jpeg,png,pdf',
        'creator_selfie' => 'jpg,jpeg,png',
        'short_video' => 'mp4,avi',
        'avatar' => 'jpg,jpeg,png',
        'article_image' => 'jpg,jpeg,png',
        'course_thumbnail' => 'jpg,jpeg,png',
        'lesson_video' => 'mp4,avi',
    ],
];
