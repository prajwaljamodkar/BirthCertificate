-- Birth Certificate Approval Workflow System
-- PostgreSQL Database Schema
-- Run this file against your PostgreSQL database to set up the schema.

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id          SERIAL PRIMARY KEY,
    username    VARCHAR(100) UNIQUE NOT NULL,
    password    VARCHAR(255) NOT NULL,
    full_name   VARCHAR(200),
    role        VARCHAR(20) NOT NULL DEFAULT 'user'
                    CHECK (role IN ('user', 'authority1', 'authority2')),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create applications table
CREATE TABLE IF NOT EXISTS applications (
    id              SERIAL PRIMARY KEY,
    user_id         INT REFERENCES users(id) ON DELETE CASCADE,
    fname           VARCHAR(100),
    mname           VARCHAR(100),
    lname           VARCHAR(100),
    birthdate       DATE,
    bplace          VARCHAR(200),
    gender          VARCHAR(10) CHECK (gender IN ('Male', 'Female', 'Other')),
    father_name     VARCHAR(200),
    mother_name     VARCHAR(200),
    religion        VARCHAR(100),
    category        VARCHAR(50),
    status          VARCHAR(30) NOT NULL DEFAULT 'pending'
                        CHECK (status IN (
                            'pending',
                            'approved_auth1',
                            'rejected_auth1',
                            'approved_auth2',
                            'rejected_auth2',
                            'verified'
                        )),
    auth1_remarks   TEXT,
    auth2_remarks   TEXT,
    applied_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    auth1_action_at TIMESTAMP NULL,
    auth2_action_at TIMESTAMP NULL
);

-- Seed default authority accounts (passwords hashed with PHP password_hash / PASSWORD_BCRYPT)
-- Authority 1 password: auth1pass
-- Authority 2 password: auth2pass
-- These hashes were generated with password_hash('auth1pass', PASSWORD_BCRYPT) etc.
-- To regenerate: php -r "echo password_hash('auth1pass', PASSWORD_BCRYPT);"

-- Passwords hashed with password_hash(..., PASSWORD_BCRYPT):
--   authority1 -> auth1pass
--   authority2 -> auth2pass
INSERT INTO users (username, password, full_name, role) VALUES
    ('authority1', '$2y$10$wF6GpniWG.WtHlhxIPt0teX5udreAgcBK6vmmOtC/nIw9MGmJPCWC', 'Authority One', 'authority1'),
    ('authority2', '$2y$10$B6hHKOxYD9ZNfIEh5H8Xt.f1VbKIk4f.LgzTk9spF8H3TjsahgfMa', 'Authority Two', 'authority2')
ON CONFLICT (username) DO NOTHING;
