<?php
/**
 * PredictCup Achievement Model
 */

class Achievement {
    private $db;
    private $achievementTable = 'achievements';
    private $userAchievementTable = 'user_achievements';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllAchievements() {
        return $this->db->resultSet("SELECT * FROM {$this->achievementTable} ORDER BY points_required DESC");
    }
    
    public function getAchievementById($id) {
        return $this->db->single("SELECT * FROM {$this->achievementTable} WHERE id = ?", [$id]);
    }
    
    public function getUserAchievements($userId) {
        return $this->db->resultSet("
            SELECT ua.*, a.name, a.description, a.badge_type
            FROM {$this->userAchievementTable} ua
            JOIN {$this->achievementTable} a ON ua.achievement_id = a.id
            WHERE ua.user_id = ?
            ORDER BY ua.earned_at DESC
        ", [(int)$userId]);
    }
    
    public function hasAchievement($userId, $badgeType) {
        $achievement = $this->db->single(
            "SELECT * FROM {$this->achievementTable} WHERE badge_type = ?", 
            [$badgeType]
        );
        
        if (!$achievement) return false;
        
        $userAchievement = $this->db->single(
            "SELECT * FROM {$this->userAchievementTable} WHERE user_id = ? AND achievement_id = ?", 
            [(int)$userId, (int)$achievement['id']]
        );
        
        return (bool)$userAchievement;
    }
    
    public function earnAchievement($userId, $badgeType) {
        $achievement = $this->db->single(
            "SELECT * FROM {$this->achievementTable} WHERE badge_type = ?", 
            [$badgeType]
        );
        
        if (!$achievement) return false;
        
        if ($this->hasAchievement($userId, $badgeType)) return false;
        
        if ($this->db->insert($this->userAchievementTable, [
            'user_id' => (int)$userId,
            'achievement_id' => (int)$achievement['id'],
            'earned_at' => date('Y-m-d H:i:s')
        ])) {
            createNotification((int)$userId, 'achievement', "You've earned the '{$achievement['name']}' badge!");
            return true;
        }
        
        return false;
    }
    
    public function checkAchievements($userId) {
        $stats = $this->db->single("
            SELECT COUNT(*) as total_predictions,
                   SUM(CASE WHEN points > 0 THEN 1 ELSE 0 END) as correct_predictions,
                   SUM(points) as total_points
            FROM predictions 
            WHERE user_id = ?
        ", [(int)$userId]);
        
        if ($stats['total_predictions'] >= 1 && !$this->hasAchievement($userId, 'first_pred')) {
            $this->earnAchievement($userId, 'first_pred');
        }
        
        if ($stats['correct_predictions'] >= 10 && !$this->hasAchievement($userId, 'ten_correct')) {
            $this->earnAchievement($userId, 'ten_correct');
        }
        
        if ($stats['correct_predictions'] >= 25 && !$this->hasAchievement($userId, 'twenty_five_correct')) {
            $this->earnAchievement($userId, 'twenty_five_correct');
        }
        
        if ($stats['total_points'] >= 500 && !$this->hasAchievement($userId, 'master')) {
            $this->earnAchievement($userId, 'master');
        }
        
        $exactScoreCount = $this->db->single("
            SELECT COUNT(*) as count FROM predictions WHERE user_id = ? AND is_exact_score = 1
        ", [(int)$userId]);
        
        if ($exactScoreCount['count'] >= 50 && !$this->hasAchievement($userId, 'guru')) {
            $this->earnAchievement($userId, 'guru');
        }
    }
}
