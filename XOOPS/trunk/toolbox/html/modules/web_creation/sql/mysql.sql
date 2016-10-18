CREATE TABLE `{prefix}_{dirname}_display_cache` (
	`display_key`     char(32) NOT NULL,
	`contents`        text,
	`creation_time`   int(11) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`display_key`)
);