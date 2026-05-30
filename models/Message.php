<?php
require_once __DIR__ . '/../config.php';

class Message
{
    private $table = 'messages';
    

    public function create($data)
    {
        $userId = $data['user_id'] ?? null;
        
        if (!$userId && !empty($data['user_email'])) {
            $user = OneFetch('SELECT id FROM users WHERE email = ?', [$data['user_email']]);
            if ($user) {
                $userId = $user['id'];
            } else {
                $userData = [
                    'firstName' => $data['user_name'] ?? 'Гость',
                    'secondName' => '',
                    'email' => $data['user_email'],
                    'phone' => $data['user_phone'] ?? '',
                    'password' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                    'role_id' => 9,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $userId = insertRow('users', $userData);
            }
        }
        
        $messageData = [
            'user_id' => $userId,
            'message' => $data['message'] ?? '',
            'status' => 'new',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return insertRow($this->table, $messageData);
    }
    

    public function getAll($orderBy = 'DESC')
    {
        return AllFetch(
            "SELECT m.*, 
                    u.firstName, u.secondName, u.email, u.phone
             FROM {$this->table} m
             LEFT JOIN users u ON u.id = m.user_id
             ORDER BY m.created_at $orderBy"
        );
    }

    public function getNew()
    {
        return AllFetch(
            "SELECT m.*, 
                    u.firstName, u.secondName, u.email, u.phone
             FROM {$this->table} m
             LEFT JOIN users u ON u.id = m.user_id
             WHERE m.status = 'new' 
             ORDER BY m.created_at DESC"
        );
    }
    
    public function getUserMessages($userId)
    {
        return AllFetch(
            "SELECT m.* 
             FROM {$this->table} m
             WHERE m.user_id = ? 
             ORDER BY m.created_at DESC",
            [$userId]
        );
    }
    
    public function findById($id)
    {
        return OneFetch(
            "SELECT m.*, 
                    u.firstName, u.secondName, u.email, u.phone
             FROM {$this->table} m
             LEFT JOIN users u ON u.id = m.user_id
             WHERE m.id = ?",
            [$id]
        );
    }

    public function updateStatus($id, $status)
    {
        return update($this->table, ['status' => $status], 'id', $id);
    }
    

    public function addReply($id, $reply, $repliedBy = null)
    {
        $data = [
            'reply' => $reply,
            'replied_at' => date('Y-m-d H:i:s'),
            'status' => 'replied'
        ];
        
        if ($repliedBy) {
            $data['replied_by'] = $repliedBy;
        }
        
        return update($this->table, $data, 'id', $id);
    }
    

    public function getNewCount()
    {
        $result = OneFetch("SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'new'");
        return $result['count'];
    }
    

    public function getUserReplies($userId)
    {
        return AllFetch(
            "SELECT m.* 
             FROM {$this->table} m
             WHERE m.user_id = ? AND m.status = 'replied' AND m.reply IS NOT NULL 
             ORDER BY m.replied_at DESC",
            [$userId]
        );
    }
    

    public function delete($id)
    {
        return deleteRow($this->table, 'id', $id);
    }
}