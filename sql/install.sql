-- Newsletter sending log
CREATE TABLE IF NOT EXISTS `PREFIX_postmark_newsletter_log` (
  `id_log` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer` INT(11) UNSIGNED,
  `email` VARCHAR(255) NOT NULL,
  `message_id` VARCHAR(255),
  `message_stream` VARCHAR(50) DEFAULT 'broadcast',
  `subject` VARCHAR(255),
  `sent_at` DATETIME,
  `status` ENUM('queued', 'sent', 'bounced', 'spam', 'delivered') DEFAULT 'queued',
  PRIMARY KEY (`id_log`),
  KEY `message_id` (`message_id`),
  KEY `email` (`email`),
  KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bounce tracking
CREATE TABLE IF NOT EXISTS `PREFIX_postmark_bounces` (
  `id_bounce` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `message_id` VARCHAR(255),
  `bounce_type` VARCHAR(50),
  `bounce_reason` TEXT,
  `bounced_at` DATETIME,
  `unsubscribed` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id_bounce`),
  KEY `email` (`email`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Unsubscribe tokens
CREATE TABLE IF NOT EXISTS `PREFIX_postmark_unsubscribe_tokens` (
  `id_token` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer` INT(11) UNSIGNED,
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(64) NOT NULL,
  `created_at` DATETIME,
  `used_at` DATETIME NULL,
  PRIMARY KEY (`id_token`),
  UNIQUE KEY `token` (`token`),
  KEY `email` (`email`),
  KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Newsletter campaigns
CREATE TABLE IF NOT EXISTS `PREFIX_postmark_campaigns` (
  `id_campaign` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `html_content` LONGTEXT,
  `text_content` LONGTEXT,
  `from_email` VARCHAR(255),
  `from_name` VARCHAR(255),
  `status` ENUM('draft', 'sending', 'sent', 'failed') DEFAULT 'draft',
  `total_recipients` INT(11) DEFAULT 0,
  `total_sent` INT(11) DEFAULT 0,
  `created_at` DATETIME,
  `sent_at` DATETIME NULL,
  PRIMARY KEY (`id_campaign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
