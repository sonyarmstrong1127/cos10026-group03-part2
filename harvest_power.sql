-- harvest_power.sql
-- COS10026 Applied Web Project Part 2 – Group 03 (Harvest Power)
-- Import in phpMyAdmin: Import -> choose this file -> Go.
-- Creates the database, the four tables and starting data.

CREATE DATABASE IF NOT EXISTS harvest_power
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE harvest_power;

-- ---------------------------------------------------------------
-- jobs: one row per job, fields match the Part 1 jobs page.
-- List fields (responsibilities, essential, preferable) store one
-- item per line; jobs.php can split them with explode("\n", ...).
-- ---------------------------------------------------------------
DROP TABLE IF EXISTS jobs;
CREATE TABLE jobs (
  job_ref           CHAR(5)      NOT NULL,
  title             VARCHAR(100) NOT NULL,
  short_description TEXT         NOT NULL,
  salary            VARCHAR(60)  NOT NULL,
  reports_to        VARCHAR(100) NOT NULL,
  responsibilities  TEXT         NOT NULL,
  responsibilities_ordered TINYINT(1) NOT NULL DEFAULT 0,  -- 1 = show as <ol>, 0 = <ul>
  essential         TEXT         NOT NULL,
  preferable        TEXT         NOT NULL,
  PRIMARY KEY (job_ref)
) ENGINE=InnoDB;

INSERT INTO jobs VALUES
('RE109', 'Renewable Energy Systems Engineer',
 'We are looking for a skilled engineer to build, optimize, and evaluate commercial solar and wind infrastructure platforms while maintaining clean system data workflows.',
 '$95,000 - $112,000 AUD per annum',
 'Principal Projects Director',
 'Design and test operational models for commercial clean energy integration.\nCollaborate with web developers to present real-time performance analytics on community portals.\nReview public safety protocols and system security updates systematically.',
 1,
 'Bachelor’s degree in Engineering or Renewable Technologies, and 2+ years of grid-modeling experience.',
 'Familiarity with smart-grid monitoring tools and collaborative project management spaces like Jira.'),
('CO482', 'Digital Engagement Content Officer',
 'Drive public engagement initiatives by maintaining our suite of client-facing platforms, writing clean environmental impact copy, and managing accessible campaign data structures.',
 '$72,000 - $81,000 AUD per annum',
 'Communications Strategy Manager',
 'Author accessible web copy detailing our latest project landmarks and carbon-neutral targets.\nLiaise directly with front-end technical groups to maintain structural page elements without broken links.\nCoordinate public community webinars focused on neighborhood green solutions.',
 0,
 'Proven baseline background in corporate communications, clear writing mechanics, and fundamental awareness of web layout structures.',
 'Prior work experience inside a sustainable energy environment, or working knowledge of basic WCAG web readability strategies.');

-- ---------------------------------------------------------------
-- eoi: one row per application. One field for every input on the
-- Part 1 apply form, plus status (New / Current / Final, default New).
-- process_eoi.php must also create this table if it does not exist.
-- ---------------------------------------------------------------
DROP TABLE IF EXISTS eoi;
CREATE TABLE eoi (
  EOInumber    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_ref      CHAR(5)      NOT NULL,
  first_name   VARCHAR(20)  NOT NULL,
  last_name    VARCHAR(20)  NOT NULL,
  dob          DATE         NOT NULL,
  gender       ENUM('female','male','other') NOT NULL,
  street       VARCHAR(40)  NOT NULL,
  suburb       VARCHAR(40)  NOT NULL,
  state        ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  postcode     CHAR(4)      NOT NULL,
  email        VARCHAR(100) NOT NULL,
  phone        VARCHAR(12)  NOT NULL,
  skill_html   TINYINT(1)   NOT NULL DEFAULT 0,
  skill_css    TINYINT(1)   NOT NULL DEFAULT 0,
  skill_solar  TINYINT(1)   NOT NULL DEFAULT 0,
  skill_data   TINYINT(1)   NOT NULL DEFAULT 0,
  skill_other  TINYINT(1)   NOT NULL DEFAULT 0,
  other_skills TEXT         NULL,
  status       ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
  created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (EOInumber)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- users: HR managers who may open manage.php.
-- Passwords are stored as password_hash() values, never as plain text.
-- The account below is admin / admin (required for the marker).
-- ---------------------------------------------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  user_id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username      VARCHAR(30)  NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  PRIMARY KEY (user_id),
  UNIQUE KEY (username)
) ENGINE=InnoDB;

INSERT INTO users (username, password_hash) VALUES
('admin', '$2y$10$WLVUS7VPlVrrMWDWiWqHeukq3KdQ2jUB.luPuKraX58GQJKYMQOJ6');

-- ---------------------------------------------------------------
-- about: team members and what each one did in Part 1 and Part 2.
-- Update part2_contribution before submitting.
-- ---------------------------------------------------------------
DROP TABLE IF EXISTS about;
CREATE TABLE about (
  member_id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  member_name        VARCHAR(60)  NOT NULL,
  student_id         VARCHAR(12)  NOT NULL,
  part1_contribution TEXT         NOT NULL,
  part2_contribution TEXT         NOT NULL,
  PRIMARY KEY (member_id)
) ENGINE=InnoDB;

INSERT INTO about (member_name, student_id, part1_contribution, part2_contribution) VALUES
('Phan Quang Huy', '106219394', 'Developed the Home and Apply pages; set up GitHub, Jira and Discord.', 'To be updated'),
('Trần Tấn Nghĩa', '106394660', 'Developed the Jobs page.', 'To be updated'),
('Lê Phạm Hoàng Hải', '105997220', 'Developed the About Us page.', 'To be updated');
