<?php
global $databases, $alterTables;

use CodeIgniter\Database\Exceptions\DatabaseException;

// Create the databases
$databases = [
    "CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        full_name TEXT,
        bio TEXT,
        two_factor_setup BOOLEAN DEFAULT 0,
        profile_image TEXT,
        is_verified BOOLEAN DEFAULT 0,
        is_active BOOLEAN DEFAULT 1,
        last_login TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );",
    "CREATE TABLE IF NOT EXISTS user_token_auth (
        token_id INTEGER PRIMARY KEY AUTOINCREMENT,
        login TEXT,
        description TEXT,
        password TEXT UNIQUE,
        hash_algo TEXT,
        system_token INTEGER NOT NULL DEFAULT 0,
        last_used DATETIME DEFAULT NULL,
        date_created DATETIME NOT NULL,
        date_expired DATETIME DEFAULT NULL
    );",
    "CREATE TABLE IF NOT EXISTS user_devices (
        device_id TEXT PRIMARY KEY,
        user_id INTEGER NOT NULL,
        device_hash TEXT NOT NULL,
        device_name TEXT,
        device_type TEXT,
        last_active TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS user_locations (
        id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        latitude REAL NOT NULL,
        longitude REAL NOT NULL,
        accuracy REAL,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS media (
        media_id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        file_name TEXT NOT NULL,
        file_path TEXT NOT NULL,
        file_type TEXT CHECK(file_type IN ('image', 'video')) NOT NULL,
        mime_type TEXT NOT NULL,
        file_size INTEGER NOT NULL,
        width INTEGER,
        height INTEGER,
        duration INTEGER,
        thumbnail_path TEXT,
        is_processed BOOLEAN DEFAULT 0,
        processing_status TEXT CHECK(processing_status IN ('pending', 'processing', 'completed', 'failed')) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS posts (
        post_id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        content TEXT,
        media_url TEXT,
        media_type TEXT CHECK(media_type IN ('image', 'video', 'none')) DEFAULT 'none',
        latitude REAL NOT NULL,
        longitude REAL NOT NULL,
        city TEXT,
        country TEXT,
        upvotes INTEGER DEFAULT 0,
        downvotes INTEGER DEFAULT 0,
        is_hidden BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS tags (
        tag_id INTEGER PRIMARY KEY,
        name TEXT NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );",

    "CREATE TABLE IF NOT EXISTS post_tags (
        post_id INTEGER NOT NULL,
        tag_id INTEGER NOT NULL,
        PRIMARY KEY (post_id, tag_id),
        FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
        FOREIGN KEY (tag_id) REFERENCES tags(tag_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS comments (
        comment_id INTEGER PRIMARY KEY,
        post_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        content TEXT NOT NULL,
        upvotes INTEGER DEFAULT 0,
        downvotes INTEGER DEFAULT 0,
        is_hidden BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS chat_rooms (
        room_id INTEGER PRIMARY KEY,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_message_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_active BOOLEAN DEFAULT 1
    );",

    "CREATE TABLE IF NOT EXISTS chat_participants (
        room_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        is_blocked BOOLEAN DEFAULT 0,
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (room_id, user_id),
        FOREIGN KEY (room_id) REFERENCES chat_rooms(room_id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS chat_messages (
        message_id INTEGER PRIMARY KEY,
        room_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        content TEXT,
        media_url TEXT,
        media_type TEXT CHECK(media_type IN ('text', 'image', 'video')) DEFAULT 'text',
        is_encrypted BOOLEAN DEFAULT 1,
        self_destruct_at TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (room_id) REFERENCES chat_rooms(room_id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS message_status (
        message_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        status TEXT CHECK(status IN ('sent', 'delivered', 'read')) DEFAULT 'sent',
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (message_id, user_id),
        FOREIGN KEY (message_id) REFERENCES chat_messages(message_id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS reports (
        report_id INTEGER PRIMARY KEY,
        reporter_id INTEGER NOT NULL,
        reported_type TEXT CHECK(reported_type IN ('post', 'comment', 'message', 'user')) NOT NULL,
        reported_id INTEGER NOT NULL,
        reason TEXT CHECK(reason IN ('spam', 'abuse', 'inappropriate', 'other')) NOT NULL,
        description TEXT,
        status TEXT CHECK(status IN ('pending', 'reviewed', 'resolved')) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (reporter_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS notifications (
        notification_id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        type TEXT CHECK(type IN ('chat', 'comment', 'vote', 'system')) NOT NULL,
        reference_id INTEGER,
        content TEXT NOT NULL,
        is_read BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

    "CREATE TABLE IF NOT EXISTS analytics (
        id INTEGER PRIMARY KEY,
        event_type TEXT CHECK(event_type IN ('post_created', 'comment_created', 'chat_started', 'user_joined', 'user_left')) NOT NULL,
        user_id INTEGER,
        latitude REAL,
        longitude REAL,
        metadata TEXT, -- SQLite has no native JSON type
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
    );",

    "CREATE TABLE IF NOT EXISTS rate_limits (
        id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        action_type TEXT CHECK(action_type IN ('post', 'comment', 'chat', 'vote')) NOT NULL,
        count INTEGER DEFAULT 1,
        window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );",

];

// alter tables
$alterTables = [
    // "ALTER TABLE users ADD COLUMN two_factor_setup BOOLEAN DEFAULT 0;",
];

function createDatabaseStructure() {
    global $databases, $alterTables;
    $db = \Config\Database::connect();
    foreach(array_merge($databases, $alterTables) as $query) {
        try {
            $db->query($query);
        } catch(DatabaseException $e) { }
    }
}

/**
 * Set the database settings
 * 
 * @param object $dbHandler
 * 
 * @return void
 */
function setDatabaseSettings($dbHandler) {
    $dbHandler->query("PRAGMA journal_mode = WAL");
    $dbHandler->query("PRAGMA synchronous = NORMAL");
    $dbHandler->query("PRAGMA locking_mode = NORMAL");
    $dbHandler->query("PRAGMA busy_timeout = 5000");
    $dbHandler->query("PRAGMA cache_size = -16000");
}
?>