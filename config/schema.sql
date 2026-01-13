CREATE TABLE `conversations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `devlogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `author_id` int(10) UNSIGNED DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`content`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `magic_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(500) NOT NULL,
  `type` enum('image','video','gif') NOT NULL DEFAULT 'image',
  `sha` varchar(40) DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `member_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`content`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `conversation_id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `newsletters` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `slug`, `label`) VALUES
(1, 'manage_projects', 'Manage Projects'),
(2, 'manage_posts', 'Manage Studio Posts'),
(3, 'manage_tech', 'Manage Tech Stack'),
(4, 'manage_messages', 'Manage Messages'),
(5, 'manage_users', 'Manage Users'),
(6, 'manage_newsletter', 'Manage Newsletter');

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('planned','in_progress','completed','on_hold','abandoned') NOT NULL DEFAULT 'planned',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `project_authors` (
  `project_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `project_gallery` (
  `project_id` int(10) UNSIGNED NOT NULL,
  `media_id` int(10) UNSIGNED NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `project_tech_stack` (
  `project_id` int(10) UNSIGNED NOT NULL,
  `tech_stack_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `resource_downloads` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(100) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(10) UNSIGNED DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `resource_embeds` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `html` text NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `resource_itch` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `url` varchar(500) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `resource_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `resource_steam` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `app_id` varchar(20) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `resource_youtube` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `video_id` varchar(20) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `studio_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `studio_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`content`)),
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `subscribers` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `subscribed_at` timestamp NULL DEFAULT current_timestamp(),
  `unsubscribed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `team_profiles` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_title` varchar(100) DEFAULT NULL,
  `short_bio` varchar(255) DEFAULT NULL,
  `about_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`about_content`)),
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_links`)),
  `color_palette` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`color_palette`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tech_stack` (
  `id` int(10) UNSIGNED NOT NULL,
  `tier_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tech_stack_tiers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('user','team_member') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_permissions` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `devlogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_project_slug` (`project_id`,`slug`),
  ADD KEY `author_id` (`author_id`);

ALTER TABLE `magic_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_email` (`email`);

ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `idx_sha` (`sha`);

ALTER TABLE `member_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_author_slug` (`author_id`,`slug`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `sender_id` (`sender_id`);

ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`is_featured`);

ALTER TABLE `project_authors`
  ADD PRIMARY KEY (`project_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `project_gallery`
  ADD PRIMARY KEY (`project_id`,`media_id`),
  ADD KEY `media_id` (`media_id`);

ALTER TABLE `project_tech_stack`
  ADD PRIMARY KEY (`project_id`,`tech_stack_id`),
  ADD KEY `tech_stack_id` (`tech_stack_id`);

ALTER TABLE `resource_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `resource_embeds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `resource_itch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `resource_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `resource_steam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `resource_youtube`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_token` (`token`);

ALTER TABLE `studio_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

ALTER TABLE `studio_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `team_profiles`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `tech_stack`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `tier_id` (`tier_id`);

ALTER TABLE `tech_stack_tiers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);


ALTER TABLE `conversations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `devlogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `magic_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `member_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `newsletters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `resource_downloads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `resource_embeds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `resource_itch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `resource_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `resource_steam`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `resource_youtube`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `sessions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `studio_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `studio_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `subscribers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `tech_stack`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `tech_stack_tiers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `conversations`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `devlogs`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `media`
  ADD CONSTRAINT `1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `member_posts`
  ADD CONSTRAINT `1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `messages`
  ADD CONSTRAINT `1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_authors`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_gallery`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_tech_stack`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`tech_stack_id`) REFERENCES `tech_stack` (`id`) ON DELETE CASCADE;

ALTER TABLE `resource_downloads`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `resource_embeds`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `resource_itch`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `resource_links`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `resource_steam`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `resource_youtube`
  ADD CONSTRAINT `1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `sessions`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `studio_posts`
  ADD CONSTRAINT `1` FOREIGN KEY (`category_id`) REFERENCES `studio_categories` (`id`) ON DELETE SET NULL;

ALTER TABLE `subscribers`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `team_profiles`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `tech_stack`
  ADD CONSTRAINT `1` FOREIGN KEY (`tier_id`) REFERENCES `tech_stack_tiers` (`id`);

ALTER TABLE `user_permissions`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;