<?php
global $databases, $alterTables, $votesTables, $notificationTables, $viewsTables, $chatRooms;

use CodeIgniter\Database\Exceptions\DatabaseException;

// Create the databases
$databases = [
    "CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        setting TEXT NOT NULL,
        value TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE UNIQUE INDEX IF NOT EXISTS user_id ON settings (user_id);",

    "CREATE TABLE IF NOT EXISTS feedback (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        feedback_type TEXT NOT NULL,
        priority TEXT NOT NULL,
        subject TEXT NOT NULL,
        description TEXT NOT NULL,
        contact_preference TEXT NOT NULL,
        status TEXT NOT NULL,
        user_agent TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS user_id ON feedback (user_id);
    CREATE INDEX IF NOT EXISTS feedback_type ON feedback (feedback_type);
    CREATE INDEX IF NOT EXISTS priority ON feedback (priority);
    CREATE INDEX IF NOT EXISTS status ON feedback (status);",

    "CREATE TABLE IF NOT EXISTS hidden_posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        post_id INTEGER NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE UNIQUE INDEX IF NOT EXISTS user_id ON hidden_posts (user_id);
    CREATE UNIQUE INDEX IF NOT EXISTS post_id ON hidden_posts (post_id);",
    "CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        full_name TEXT,
        bio TEXT,
        gender TEXT,
        statistics TEXT,
        user_type TEXT DEFAULT 'user',
        location TEXT DEFAULT NULL,
        two_factor_setup BOOLEAN DEFAULT 0,
        profile_image TEXT,
        status TEXT DEFAULT 'active',
        is_verified BOOLEAN DEFAULT 0,
        is_active BOOLEAN DEFAULT 1,
        last_login DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    CREATE UNIQUE INDEX IF NOT EXISTS user_id ON users (user_id);",
    "CREATE TABLE IF NOT EXISTS pageviews (
        pageview_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        uuid TEXT,
        user_agent TEXT,
        referer TEXT,
        page TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS user_id ON pageviews (user_id);",
    "CREATE TABLE IF NOT EXISTS bookmarks (
        bookmark_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        post_id INTEGER NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE UNIQUE INDEX IF NOT EXISTS bookmark_id ON bookmarks (bookmark_id);
    CREATE INDEX IF NOT EXISTS user_id ON bookmarks (user_id);
    CREATE INDEX IF NOT EXISTS post_id ON bookmarks (post_id);",
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
    );
    CREATE INDEX IF NOT EXISTS login ON user_token_auth (login);
    CREATE INDEX IF NOT EXISTS password ON user_token_auth (password);",
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
    );
    CREATE INDEX IF NOT EXISTS user_id ON user_devices (user_id);
    CREATE INDEX IF NOT EXISTS device_hash ON user_devices (device_hash);
    CREATE INDEX IF NOT EXISTS device_type ON user_devices (device_type);",

    "CREATE TABLE IF NOT EXISTS user_locations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        latitude REAL NOT NULL,
        longitude REAL NOT NULL,
        accuracy REAL,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );
    CREATE INDEX IF NOT EXISTS user_id ON user_locations (user_id);",

    "CREATE TABLE IF NOT EXISTS media (
        media_id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        record_id INTEGER NOT NULL,
        section TEXT NOT NULL,
        media TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS user_id ON media (user_id);
    CREATE INDEX IF NOT EXISTS record_id ON media (record_id);
    CREATE INDEX IF NOT EXISTS section ON media (section);",

    "CREATE TABLE IF NOT EXISTS posts (
        post_id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        content TEXT,
        media_url TEXT,
        media_type TEXT CHECK(media_type IN ('image', 'video', 'none')) DEFAULT 'none',
        latitude REAL NOT NULL,
        longitude REAL NOT NULL,
        city TEXT,
        pageviews INTEGER DEFAULT 1,
        comments_count INTEGER DEFAULT 0,
        country TEXT,
        post_uuid TEXT,
        views INTEGER DEFAULT 0,
        upvotes INTEGER DEFAULT 0,
        downvotes INTEGER DEFAULT 0,
        is_hidden BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );
    CREATE INDEX IF NOT EXISTS user_id ON posts (user_id);
    CREATE INDEX IF NOT EXISTS media_type ON posts (media_type);
    CREATE INDEX IF NOT EXISTS city ON posts (city);
    CREATE INDEX IF NOT EXISTS country ON posts (country);",

    "CREATE TABLE IF NOT EXISTS hashtags (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    );
    CREATE INDEX IF NOT EXISTS name ON hashtags (name);",
    
    "CREATE TABLE IF NOT EXISTS post_hashtags (
        post_id INTEGER NOT NULL,
        hashtag_id INTEGER NOT NULL,
        PRIMARY KEY (post_id, hashtag_id),
        FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
        FOREIGN KEY (hashtag_id) REFERENCES hashtags(id) ON DELETE CASCADE
    );
    CREATE INDEX IF NOT EXISTS post_id ON post_hashtags (post_id);
    CREATE INDEX IF NOT EXISTS hashtag_id ON post_hashtags (hashtag_id);",

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
    );
    CREATE INDEX IF NOT EXISTS post_id ON post_tags (post_id);
    CREATE INDEX IF NOT EXISTS tag_id ON post_tags (tag_id);",

    "CREATE TABLE IF NOT EXISTS comments (
        comment_id INTEGER PRIMARY KEY,
        post_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        content TEXT NOT NULL,
        upvotes INTEGER DEFAULT 0,
        downvotes INTEGER DEFAULT 0,
        is_hidden BOOLEAN DEFAULT 0,
        city TEXT,
        reference_id INTEGER NOT NULL,
        country TEXT,
        views INTEGER DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );
    CREATE INDEX IF NOT EXISTS post_id ON comments (post_id);
    CREATE INDEX IF NOT EXISTS user_id ON comments (user_id);
    CREATE INDEX IF NOT EXISTS reference_id ON comments (reference_id);",

    "CREATE TABLE IF NOT EXISTS chat_rooms (
        room_id INTEGER PRIMARY KEY AUTOINCREMENT,
        sender_id INTEGER NOT NULL,
        receiver_id INTEGER NOT NULL,
        type TEXT,
        name TEXT,
        room_uuid TEXT,
        description TEXT,
        room_description TEXT,
        receiver_deleted BOOLEAN DEFAULT 0,
        sender_deleted BOOLEAN DEFAULT 0,
        receipients_list TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_message_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS sender_id ON chat_rooms (sender_id);
    CREATE INDEX IF NOT EXISTS receiver_id ON chat_rooms (receiver_id);
    CREATE INDEX IF NOT EXISTS type ON chat_rooms (type);
    CREATE INDEX IF NOT EXISTS receiver_deleted ON chat_rooms (receiver_deleted);
    CREATE INDEX IF NOT EXISTS sender_deleted ON chat_rooms (sender_deleted);",

    "CREATE TABLE IF NOT EXISTS contacts (
        contact_id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        message TEXT NOT NULL,
        subject TEXT NOT NULL,
        user_id INTEGER NOT NULL,
        token TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS user_id ON contacts (user_id);
    CREATE INDEX IF NOT EXISTS token ON contacts (token);",

    "CREATE TABLE IF NOT EXISTS chat_messages (
        message_id INTEGER PRIMARY KEY,
        room_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        content TEXT,
        media_url TEXT,
        unique_id TEXT,
        receiver_seen BOOLEAN DEFAULT 0,
        sender_deleted BOOLEAN DEFAULT 0,
        receiver_deleted BOOLEAN DEFAULT 0,
        media_type TEXT CHECK(media_type IN ('text', 'image', 'video')) DEFAULT 'text',
        is_encrypted BOOLEAN DEFAULT 1,
        self_destruct_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (room_id) REFERENCES chat_rooms(room_id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );
    CREATE UNIQUE INDEX IF NOT EXISTS message_id ON chat_messages (message_id);
    CREATE INDEX IF NOT EXISTS room_id ON chat_messages (room_id);
    CREATE INDEX IF NOT EXISTS user_id ON chat_messages (user_id);
    CREATE INDEX IF NOT EXISTS media_type ON chat_messages (media_type);",

    "CREATE TABLE IF NOT EXISTS reports (
        report_id INTEGER PRIMARY KEY,
        reporter_id INTEGER NOT NULL,
        reported_type TEXT CHECK(reported_type IN ('post', 'comment', 'message', 'user')) NOT NULL,
        reported_id INTEGER NOT NULL,
        final_decision TEXT DEFAULT 'pending',
        reason TEXT CHECK(reason IN ('spam', 'harassment', 'inappropriate', 'misinformation', 'violence', 'other')) NOT NULL,
        status TEXT CHECK(status IN ('pending', 'reviewed', 'resolved')) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (reporter_id) REFERENCES users(user_id) ON DELETE CASCADE
    );
    CREATE UNIQUE INDEX IF NOT EXISTS report_id ON reports (report_id);
    CREATE INDEX IF NOT EXISTS reporter_id ON reports (reporter_id);
    CREATE INDEX IF NOT EXISTS reported_type ON reports (reported_type);
    CREATE INDEX IF NOT EXISTS reported_id ON reports (reported_id);
    CREATE INDEX IF NOT EXISTS status ON reports (status);",

    "CREATE TABLE IF NOT EXISTS report_votes (
        vote_id INTEGER PRIMARY KEY AUTOINCREMENT,
        report_id INTEGER NOT NULL,
        moderator_id INTEGER NOT NULL,
        vote_type VARCHAR(10) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS report_id ON report_votes (report_id);
    CREATE INDEX IF NOT EXISTS moderator_id ON report_votes (moderator_id);
    CREATE INDEX IF NOT EXISTS vote_type ON report_votes (vote_type);",

    "CREATE TABLE IF NOT EXISTS analytics (
        id INTEGER PRIMARY KEY,
        event_type TEXT CHECK(event_type IN ('post_created', 'comment_created', 'chat_started', 'user_joined', 'user_left')) NOT NULL,
        user_id INTEGER,
        latitude REAL,
        longitude REAL,
        metadata TEXT, -- SQLite has no native JSON type
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
    );
    CREATE INDEX IF NOT EXISTS event_type ON analytics (event_type);
    CREATE INDEX IF NOT EXISTS user_id ON analytics (user_id);",

    "CREATE TABLE IF NOT EXISTS rate_limits (
        id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        action_type TEXT CHECK(action_type IN ('post', 'comment', 'chat', 'vote')) NOT NULL,
        count INTEGER DEFAULT 1,
        window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );
    CREATE INDEX IF NOT EXISTS user_id ON rate_limits (user_id);
    CREATE INDEX IF NOT EXISTS action_type ON rate_limits (action_type);",

];

// alter tables
$alterTables = [
    // "ALTER TABLE posts ADD COLUMN post_uuid TEXT",
];

$votesTables = [
    "CREATE TABLE IF NOT EXISTS votes (
        vote_id INTEGER PRIMARY KEY AUTOINCREMENT,
        record_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        section TEXT CHECK(section IN ('posts', 'comments')) NOT NULL,
        direction TEXT CHECK(direction IN ('up', 'down')) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS record_id ON votes (record_id);
    CREATE INDEX IF NOT EXISTS user_id ON votes (user_id);
    CREATE INDEX IF NOT EXISTS section ON votes (section);
    CREATE INDEX IF NOT EXISTS direction ON votes (direction);",
];

$viewsTables = [
    "CREATE TABLE IF NOT EXISTS views (
        view_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        section TEXT CHECK(section IN ('posts', 'comments')) NOT NULL,
        record_id INTEGER NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE UNIQUE INDEX IF NOT EXISTS view_id ON views (view_id);
    CREATE INDEX IF NOT EXISTS user_id ON views (user_id);
    CREATE INDEX IF NOT EXISTS section ON views (section);
    CREATE INDEX IF NOT EXISTS record_id ON views (record_id);",
];

$chatRooms = [
    "CREATE TABLE IF NOT EXISTS user_chat_rooms (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        room_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        type TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS user_id ON user_chat_rooms (user_id);
    CREATE INDEX IF NOT EXISTS type ON user_chat_rooms (type);",
];

$notificationTables = [
    "CREATE TABLE IF NOT EXISTS notifications (
        notification_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        type TEXT CHECK(type IN ('chat', 'comment', 'vote', 'system', 'features', 'updates')) NOT NULL,
        section TEXT,
        reference_id INTEGER,
        content TEXT NOT NULL,
        is_read BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE UNIQUE INDEX IF NOT EXISTS notification_id ON notifications (notification_id);
    CREATE INDEX IF NOT EXISTS user_id ON notifications (user_id);
    CREATE INDEX IF NOT EXISTS type ON notifications (type);
    CREATE INDEX IF NOT EXISTS reference_id ON notifications (reference_id);
    CREATE INDEX IF NOT EXISTS is_read ON notifications (is_read);",
];

function createDatabaseStructure() {
    global $databases, $alterTables, $votesTables, $notificationTables, $viewsTables, $chatRooms;
    foreach(['tests' => $databases, 'votes' => $votesTables, 'notification' => $notificationTables, 'views' => $viewsTables, 'chats' => $chatRooms] as $idb => $tables) {
        $db = \Config\Database::connect($idb);
        foreach(array_merge($tables, $alterTables) as $query) {
            try {
                $db->query($query);
            } catch(DatabaseException $e) { }
        }
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