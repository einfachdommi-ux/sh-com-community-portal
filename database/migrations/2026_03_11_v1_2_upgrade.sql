-- SH-COM Portal v1.2 Update
-- Ohne Neuinstallation ausführen

ALTER TABLE news
    ADD COLUMN IF NOT EXISTS featured_image VARCHAR(255) NULL AFTER image_path;

-- Optional: nur falls Spalte in älteren Setups fehlt
ALTER TABLE navigation_items
    ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL AFTER created_at;

ALTER TABLE roles
    ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL AFTER created_at;

ALTER TABLE permissions
    ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL AFTER created_at;

ALTER TABLE changelogs
    ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL AFTER created_at;
