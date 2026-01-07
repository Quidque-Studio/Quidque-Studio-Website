<?php

use Api\Controllers\HomeController;
use Api\Controllers\AuthController;
use Api\Controllers\AdminController;
use Api\Controllers\SettingsController;
use Api\Controllers\MessageController;
use Api\Controllers\MemberController;
use Api\Controllers\Admin\ProjectController;
use Api\Controllers\Admin\DevlogController;
use Api\Controllers\Admin\MediaController;
use Api\Controllers\Admin\StudioPostController;
use Api\Controllers\Admin\TechStackController;
use Api\Controllers\Admin\MessageController as AdminMessageController;
use Api\Controllers\Admin\UserController;
use Api\Controllers\Admin\NewsletterController as AdminNewsletterController;
use Api\Controllers\NewsletterController;

$router->get('/', [HomeController::class, 'index']);

// Auth
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->post('/auth/login', [AuthController::class, 'sendMagicLink']);
$router->get('/auth/verify', [AuthController::class, 'verify']);
$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->post('/auth/register', [AuthController::class, 'register']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

// Settings
$router->get('/settings', [SettingsController::class, 'index']);
$router->post('/settings', [SettingsController::class, 'update']);
$router->post('/settings/avatar', [SettingsController::class, 'updateAvatar']);
$router->post('/settings/profile', [SettingsController::class, 'updateProfile']);

// User messages
$router->get('/messages', [MessageController::class, 'index']);
$router->get('/messages/new', [MessageController::class, 'create']);
$router->post('/messages', [MessageController::class, 'store']);
$router->get('/messages/{id}', [MessageController::class, 'show']);
$router->post('/messages/{id}/reply', [MessageController::class, 'reply']);
$router->post('/messages/{id}/delete', [MessageController::class, 'delete']);

// Public newsletter
$router->post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
$router->get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);


// Team member pages
$router->get('/team/{id}', [MemberController::class, 'show']);
$router->post('/team/{id}/about', [MemberController::class, 'updateAbout']);
$router->get('/team/{id}/posts', [MemberController::class, 'posts']);
$router->get('/team/{id}/posts/new', [MemberController::class, 'createPost']);
$router->post('/team/{id}/posts', [MemberController::class, 'storePost']);
$router->get('/team/{id}/posts/{slug}', [MemberController::class, 'showPost']);
$router->get('/team/{id}/posts/{postId}/edit', [MemberController::class, 'editPost']);
$router->post('/team/{id}/posts/{postId}', [MemberController::class, 'updatePost']);
$router->post('/team/{id}/posts/{postId}/delete', [MemberController::class, 'deletePost']);

// Admin
$router->get('/admin', [AdminController::class, 'dashboard']);

$router->get('/admin/projects', [ProjectController::class, 'index']);
$router->get('/admin/projects/create', [ProjectController::class, 'create']);
$router->post('/admin/projects', [ProjectController::class, 'store']);
$router->get('/admin/projects/{id}/edit', [ProjectController::class, 'edit']);
$router->post('/admin/projects/{id}', [ProjectController::class, 'update']);
$router->post('/admin/projects/{id}/delete', [ProjectController::class, 'delete']);

$router->get('/admin/projects/{projectId}/devlogs', [DevlogController::class, 'index']);
$router->get('/admin/projects/{projectId}/devlogs/create', [DevlogController::class, 'create']);
$router->post('/admin/projects/{projectId}/devlogs', [DevlogController::class, 'store']);
$router->get('/admin/projects/{projectId}/devlogs/{id}/edit', [DevlogController::class, 'edit']);
$router->post('/admin/projects/{projectId}/devlogs/{id}', [DevlogController::class, 'update']);
$router->post('/admin/projects/{projectId}/devlogs/{id}/delete', [DevlogController::class, 'delete']);

$router->get('/admin/studio-posts', [StudioPostController::class, 'index']);
$router->get('/admin/studio-posts/create', [StudioPostController::class, 'create']);
$router->post('/admin/studio-posts', [StudioPostController::class, 'store']);
$router->get('/admin/studio-posts/categories', [StudioPostController::class, 'categories']);
$router->post('/admin/studio-posts/categories', [StudioPostController::class, 'storeCategory']);
$router->post('/admin/studio-posts/categories/{id}', [StudioPostController::class, 'updateCategory']);
$router->post('/admin/studio-posts/categories/{id}/delete', [StudioPostController::class, 'deleteCategory']);
$router->get('/admin/studio-posts/{id}/edit', [StudioPostController::class, 'edit']);
$router->post('/admin/studio-posts/{id}', [StudioPostController::class, 'update']);
$router->post('/admin/studio-posts/{id}/delete', [StudioPostController::class, 'delete']);

$router->get('/admin/tech-stack', [TechStackController::class, 'index']);
$router->post('/admin/tech-stack/tiers', [TechStackController::class, 'storeTier']);
$router->post('/admin/tech-stack/tiers/order', [TechStackController::class, 'updateTierOrder']);
$router->post('/admin/tech-stack/tiers/{id}', [TechStackController::class, 'updateTier']);
$router->post('/admin/tech-stack/tiers/{id}/delete', [TechStackController::class, 'deleteTier']);
$router->post('/admin/tech-stack', [TechStackController::class, 'storeTech']);
$router->post('/admin/tech-stack/{id}', [TechStackController::class, 'updateTech']);
$router->post('/admin/tech-stack/{id}/delete', [TechStackController::class, 'deleteTech']);

$router->get('/admin/messages', [AdminMessageController::class, 'index']);
$router->get('/admin/messages/{id}', [AdminMessageController::class, 'show']);
$router->post('/admin/messages/{id}/reply', [AdminMessageController::class, 'reply']);

$router->post('/admin/media/upload', [MediaController::class, 'upload']);
$router->post('/admin/media/upload-download', [MediaController::class, 'uploadDownload']);
$router->post('/admin/media/{id}/delete', [MediaController::class, 'delete']);

$router->get('/admin/users', [UserController::class, 'index']);
$router->get('/admin/users/{id}/edit', [UserController::class, 'edit']);
$router->post('/admin/users/{id}', [UserController::class, 'update']);
$router->post('/admin/users/{id}/delete', [UserController::class, 'delete']);

$router->get('/admin/newsletter', [AdminNewsletterController::class, 'index']);
$router->get('/admin/newsletter/subscribers', [AdminNewsletterController::class, 'subscribers']);
$router->get('/admin/newsletter/create', [AdminNewsletterController::class, 'create']);
$router->post('/admin/newsletter', [AdminNewsletterController::class, 'store']);
$router->get('/admin/newsletter/{id}/edit', [AdminNewsletterController::class, 'edit']);
$router->post('/admin/newsletter/{id}', [AdminNewsletterController::class, 'update']);
$router->post('/admin/newsletter/{id}/send', [AdminNewsletterController::class, 'send']);
$router->post('/admin/newsletter/{id}/delete', [AdminNewsletterController::class, 'delete']);