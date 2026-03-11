SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NULL,
    avatar VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    email_verification_token VARCHAR(255) NULL,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_roles (
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, role_id),
    CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
    module VARCHAR(100) NOT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100) NULL,
    entity_id BIGINT UNSIGNED NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(64) NULL,
    user_agent TEXT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS mail_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    related_user_id BIGINT UNSIGNED NULL,
    mail_type VARCHAR(100) NOT NULL,
    recipient_email VARCHAR(190) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL,
    provider_response TEXT NULL,
    error_message TEXT NULL,
    sent_at DATETIME NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS navigation_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NULL,
    area ENUM('frontend','backend') NOT NULL,
    label VARCHAR(100) NOT NULL,
    route VARCHAR(255) NOT NULL,
    icon VARCHAR(100) NULL,
    permission_slug VARCHAR(150) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    visibility VARCHAR(50) NOT NULL DEFAULT 'public',
    status VARCHAR(50) NOT NULL DEFAULT 'draft',
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS news (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    teaser TEXT NULL,
    content LONGTEXT NOT NULL,
    image_path VARCHAR(255) NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'draft',
    published_at DATETIME NULL,
    author_id BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS changelogs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    version VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    change_type VARCHAR(50) NOT NULL,
    content LONGTEXT NOT NULL,
    visibility VARCHAR(50) NOT NULL DEFAULT 'public',
    released_at DATETIME NOT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS team_members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    display_name VARCHAR(150) NOT NULL,
    team_role VARCHAR(150) NOT NULL,
    bio TEXT NULL,
    image_path VARCHAR(255) NULL,
    social_links JSON NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO roles (id, name, slug, description, created_at, updated_at) VALUES
(1,'Projektleitung','projektleitung','Vollzugriff',NOW(),NOW()),
(2,'Community-Manager','community-manager','Community Verwaltung',NOW(),NOW()),
(3,'Community-Moderator','community-moderator','Moderation',NOW(),NOW()),
(4,'Social-Media Moderator','social-media-moderator','Social und News',NOW(),NOW()),
(5,'Community Member','community-member','Registrierter Nutzer',NOW(),NOW()),
(6,'Gast','gast','Öffentlicher Besucher',NOW(),NOW());

INSERT IGNORE INTO permissions (id, name, slug, description, created_at, updated_at) VALUES
(1,'Dashboard anzeigen','dashboard.view','',NOW(),NOW()),
(2,'User anzeigen','users.view','',NOW(),NOW()),
(3,'User erstellen','users.create','',NOW(),NOW()),
(4,'Rollen verwalten','roles.manage','',NOW(),NOW()),
(5,'Permissions verwalten','permissions.manage','',NOW(),NOW()),
(6,'Seiten verwalten','pages.manage','',NOW(),NOW()),
(7,'Navigation verwalten','navigation.manage','',NOW(),NOW()),
(8,'News verwalten','news.manage','',NOW(),NOW()),
(9,'Changelog verwalten','changelog.manage','',NOW(),NOW()),
(10,'Auditlog anzeigen','auditlog.view','',NOW(),NOW()),
(11,'Maillog anzeigen','maillog.view','',NOW(),NOW()),
(12,'DB Tools nutzen','dbtools.select','',NOW(),NOW()),
(13,'DB Tools ändern','dbtools.write','',NOW(),NOW()),
(14,'Team verwalten','team.manage','',NOW(),NOW());

INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),
(2,1),(2,2),(2,3),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,14),
(3,1),(3,8),(3,9),
(4,1),(4,8),(4,9),
(5,1);

INSERT IGNORE INTO navigation_items (id, parent_id, area, label, route, icon, permission_slug, sort_order, is_active, created_at, updated_at) VALUES
(1,NULL,'frontend','Home','/','home',NULL,1,1,NOW(),NOW()),
(2,NULL,'frontend','News','/news','newspaper',NULL,2,1,NOW(),NOW()),
(3,NULL,'frontend','Changelog','/changelog','clock-history',NULL,3,1,NOW(),NOW()),
(4,NULL,'frontend','Team','/team','people',NULL,4,1,NOW(),NOW()),
(5,NULL,'frontend','Members','/members','person-badge',NULL,5,1,NOW(),NOW()),
(10,NULL,'backend','Dashboard','/admin','grid','dashboard.view',1,1,NOW(),NOW()),
(11,NULL,'backend','Users','/admin/users','people','dashboard.view',2,1,NOW(),NOW()),
(12,NULL,'backend','Roles','/admin/roles','shield','dashboard.view',3,1,NOW(),NOW()),
(13,NULL,'backend','Permissions','/admin/permissions','key','dashboard.view',4,1,NOW(),NOW()),
(14,NULL,'backend','Navigation','/admin/navigation','menu-button','dashboard.view',5,1,NOW(),NOW()),
(15,NULL,'backend','Pages','/admin/pages','file-text','dashboard.view',6,1,NOW(),NOW()),
(16,NULL,'backend','News','/admin/news','newspaper','dashboard.view',7,1,NOW(),NOW()),
(17,NULL,'backend','Changelog','/admin/changelogs','list-task','dashboard.view',8,1,NOW(),NOW()),
(18,NULL,'backend','Team','/admin/team','people','dashboard.view',9,1,NOW(),NOW()),
(19,NULL,'backend','Audit Log','/admin/audit-logs','journal-text','dashboard.view',10,1,NOW(),NOW()),
(20,NULL,'backend','Mail Log','/admin/mail-logs','envelope','dashboard.view',11,1,NOW(),NOW()),
(21,NULL,'backend','DB Tools','/admin/db-tools','database','dashboard.view',12,1,NOW(),NOW());

INSERT IGNORE INTO pages (id,title,slug,content,meta_title,meta_description,visibility,status,created_by,updated_by,created_at,updated_at) VALUES
(1,'Über uns','ueber-uns','Willkommen bei SH-COM. Diese Beispielseite kann im Admin bearbeitet werden.','Über uns','Beispielseite','public','published',NULL,NULL,NOW(),NOW());

INSERT IGNORE INTO news (id,title,slug,teaser,content,status,published_at,author_id,created_at,updated_at) VALUES
(1,'Willkommen bei SH-COM','willkommen-bei-sh-com','Start des neuen Portals','Das Portal ist erfolgreich installiert und einsatzbereit.','published',NOW(),NULL,NOW(),NOW());

INSERT IGNORE INTO changelogs (id,version,title,change_type,content,visibility,released_at,created_by,created_at,updated_at) VALUES
(1,'1.0.0','Initial Release','Added','Grundsystem mit Front Controller, Adminpanel, RBAC, News, Changelog und Setup-Installer.','public',NOW(),NULL,NOW(),NOW());

INSERT IGNORE INTO team_members (id,user_id,display_name,team_role,bio,image_path,social_links,sort_order,is_active,created_at,updated_at) VALUES
(1,NULL,'Projektleitung','Projektleitung','Leitet das Projekt und die Portalstruktur.',NULL,'{}',1,1,NOW(),NOW()),
(2,NULL,'Community Team','Community-Manager','Koordiniert Community und Inhalte.',NULL,'{}',2,1,NOW(),NOW());
