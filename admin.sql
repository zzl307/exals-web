CREATE TABLE admin_user (
  id          int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name        varchar(160)  NOT NULL DEFAULT '',
  email       varchar(160)  NOT NULL DEFAULT '',
  password    varchar(160)  NOT NULL DEFAULT '',
  remember_token varchar(100) DEFAULT NULL,
  created_at  timestamp NULL DEFAULT NULL,
  updated_at  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (name),
  UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE admin_role (
  id          int unsigned NOT NULL AUTO_INCREMENT,
  name        varchar(32) NOT NULL DEFAULT '' UNIQUE,
  description varchar(128) NOT NULL DEFAULT '',
  created_at  timestamp NULL DEFAULT NULL,
  updated_at  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE admin_permission (
  id          int unsigned NOT NULL AUTO_INCREMENT,
  keyword     varchar(32) NOT NULL DEFAULT '' UNIQUE,
  name        varchar(32) NOT NULL DEFAULT '' UNIQUE,
  description varchar(128) NOT NULL DEFAULT '',
  created_at  timestamp NULL DEFAULT NULL,
  updated_at  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE admin_user_role (
  id          int unsigned NOT NULL AUTO_INCREMENT,
  role_id     int NOT NULL,
  user_id     int NOT NULL,
  created_at  timestamp NULL DEFAULT NULL,
  updated_at  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE admin_role_permission (
  id          int unsigned NOT NULL AUTO_INCREMENT,
  role_id     int NOT NULL,
  permission_id int NOT NULL,
  created_at  timestamp NULL DEFAULT NULL,
  updated_at  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
