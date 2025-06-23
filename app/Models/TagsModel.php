<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class TagsModel extends Model {

    public $payload = [];
    protected $table;
    protected $primaryKey = "tag_id";

    public function __construct() {
        parent::__construct();
        
        $this->table = DbTables::$userTable;
        foreach(DbTables::initTables() as $key) {
            if (property_exists($this, $key)) {
                $this->{$key} = DbTables::${$key};
            }
        }
    }

    /**
     * Get posts list by hashtag
     * 
     * @param string $hashtagId
     * 
     * @return array
     */
    public function getPostsListByHashtag($hashtag, $column = 'name') {
        try {
            $query = $this->db->table('post_hashtags')
                    ->select('posts.*, u.full_name, u.username as username, u.profile_image')
                    ->join('hashtags', 'hashtags.id = post_hashtags.hashtag_id')
                    ->join('posts', 'posts.post_id = post_hashtags.post_id')
                    ->join('users u', 'posts.user_id = u.user_id')
                    ->where("hashtags.{$column}", $hashtag)
                    ->get();
            return $query->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get hashtags
     * 
     * @param array $hashtags
     * 
     * @return array
     */
    public function getHashtags() {
        try {
            return $this->db->table('hashtags')->get()->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get hashtag
     * 
     * @param string $hashtag
     * 
     * @return array
     */
    public function getHashtag($hashtag) {
        try {
            return $this->db->table('hashtags')->where('name', $hashtag)->get()->getRowArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get similar hashtags
     * 
     * @param string $hashtag
     * 
     * @return array
     */
    public function getSimilarHashtags($hashtag) {
        try {
            return $this->db->table('hashtags')->like('name', $hashtag)->get()->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get post hashtags
     * 
     * @param string $postId
     * 
     * @return array
     */
    public function getPostHashtags($postId) {
        try {
            return $this->db->table('post_hashtags')->where('post_id', $postId)->get()->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get posts by hashtag
     * 
     * @param string $hashtagId
     * 
     * @return array
     */
    public function getPostsByHashtagId($hashtagId) {
        try {
            return $this->db->table('post_hashtags')->where('hashtag_id', $hashtagId)->get()->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get popular hashtags
     * 
     * @return array
     */
    public function getPopularHashtags($limit = 100) {
        try {

            // $list = $this->db->table('post_hashtags')->get()->getResultArray();
            // $list = $this->db->table('hashtags')->get()->getResultArray();

            // print_r($list);
            // exit;

            return $this->db->table('post_hashtags')
                            ->select('hashtags.name, COUNT(*) as usage_count, hashtags.id as tag_id')
                            ->join('hashtags', 'hashtags.id = post_hashtags.hashtag_id', 'left')
                            ->groupBy('hashtags.name')
                            ->orderBy('usage_count', 'desc')
                            ->limit($limit)
                            ->get()->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get hashtags by list
     * 
     * @param array $hashtags
     * 
     * @return array
     */
    public function getHashtagsByList($hashtags = []) {
        try {
            $query = $this->db->table('hashtags')->whereIn('name', $hashtags)->get();
            return $query->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Insert hashtags
     * 
     * @param string $postId
     * @param array $hashtags
     * 
     * @return array
     */
    public function createhashtags($hashtags, $isLocal = false) {
        try {
            $hashIds = [];
            $keyword = !$isLocal ? "IGNORE" : "";
            foreach($hashtags as $hashtag) {
                $sql = "INSERT {$keyword} INTO hashtags (name) VALUES (?)";
                $this->db->query($sql, [trim(strtolower($hashtag))]);
                $hashIds[$hashtag] = $this->db->insertID();
            }
            return $hashIds;
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Insert post hashtags
     * 
     * @param string $postId
     * @param array $hashtags
     * 
     * @return array
     */
    public function createposthashtags($postId, $hashtags) {
        try {
            foreach($hashtags as $hashtag) {
                $sql = "INSERT INTO post_hashtags (post_id, hashtag_id) VALUES (?, ?)";
                $this->db->query($sql, [$postId, trim(strtolower($hashtag))]);
            }
        } catch (DatabaseException $e) {
            return [];
        }
    }

}