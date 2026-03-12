-- SH-COM Portal v1.3 profile upgrade
-- Run only if the columns do not exist yet.
ALTER TABLE users ADD COLUMN bio TEXT NULL AFTER avatar;
ALTER TABLE users ADD COLUMN discord VARCHAR(150) NULL AFTER bio;
