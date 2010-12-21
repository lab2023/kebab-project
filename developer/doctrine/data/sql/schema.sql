CREATE TABLE role (rolename VARCHAR(255), inheritrole VARCHAR(255), INDEX inheritrole_idx (inheritrole), PRIMARY KEY(rolename)) DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ENGINE = INNODB;
CREATE TABLE user (username VARCHAR(16), email VARCHAR(255), password VARCHAR(255), PRIMARY KEY(username)) DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ENGINE = INNODB;
CREATE TABLE user_role (username VARCHAR(16), rolename VARCHAR(255), PRIMARY KEY(username, rolename)) DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ENGINE = INNODB;
ALTER TABLE role ADD CONSTRAINT role_inheritrole_role_rolename FOREIGN KEY (inheritrole) REFERENCES role(rolename);
ALTER TABLE user_role ADD CONSTRAINT user_role_username_user_username FOREIGN KEY (username) REFERENCES user(username);
ALTER TABLE user_role ADD CONSTRAINT user_role_rolename_role_rolename FOREIGN KEY (rolename) REFERENCES role(rolename);
