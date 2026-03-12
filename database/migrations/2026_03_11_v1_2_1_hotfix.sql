-- SH-COM Portal v1.2.1 HotFix

ALTER TABLE news
    ADD COLUMN IF NOT EXISTS featured_image VARCHAR(255) NULL AFTER content;

ALTER TABLE changelogs
    ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL AFTER created_at;

UPDATE changelogs
SET updated_at = COALESCE(updated_at, created_at, NOW());

UPDATE changelogs
SET released_at = COALESCE(released_at, created_at, NOW())
WHERE released_at IS NULL OR released_at = '0000-00-00 00:00:00';
