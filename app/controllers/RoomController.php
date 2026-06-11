<?php
/**
 * PredictCup Room Controller
 */

class RoomController {
    private $roomModel;
    private $predictionModel;
    private $userModel;
    
    public function __construct() {
        $this->roomModel = new Room();
        $this->predictionModel = new Prediction();
        $this->userModel = new User();
    }
    
    public function getAllRooms() {
        $allRooms = $this->roomModel->getAllRooms();
        $todayMatches = $this->matchModel->getTodayMatches();
        
        if (isLoggedIn()) {
            $userId = getCurrentUserId();
            $user = $this->userModel->getUserById($userId);
            $stats = $this->userModel->getStats($userId);
            $globalRank = $this->userModel->getGlobalRank($userId);
            
            include_once __DIR__ . '/../views/dashboard.php';
        } else {
            include_once __DIR__ . '/../views/rooms/join.php';
        }
    }
    
    public function create() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . '/login');
        }
        
        if (isPostRequest()) {
            $data = [
                'user_id' => getCurrentUserId(),
                'name' => sanitize($_POST['name'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'is_public' => isset($_POST['is_public']) ? 1 : 0,
                'password' => $_POST['password'] ?? '',
                'max_members' => (int)($_POST['max_members'] ?? MAX_ROOM_MEMBERS)
            ];
            
            if (empty($data['name'])) {
                setFlashMessage('error', 'Room name is required');
                redirect(BASE_URL . '/rooms/create');
            }
            
            $result = $this->roomModel->createRoom($data);
            
            if ($result['success']) {
                setFlashMessage('success', "Room created successfully! Invite code: {$result['code']}");
                redirect(BASE_URL . "/rooms/view/{$result['id']}");
            } else {
                setFlashMessage('error', $result['message']);
                redirect(BASE_URL . '/rooms/create');
            }
        }
        
        include_once __DIR__ . '/../views/rooms/create.php';
    }
    
    public function join() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . '/login');
        }
        
        if (isPostRequest()) {
            $code = strtoupper(sanitize($_POST['invite_code'] ?? ''));
            $password = $_POST['password'] ?? '';
            
            if (empty($code)) {
                setFlashMessage('error', 'Room code is required');
                redirect(BASE_URL . '/rooms/join');
            }
            
            $result = $this->roomModel->joinRoom(getCurrentUserId(), $code, $password);
            
            if ($result['success']) {
                setFlashMessage('success', 'Successfully joined the room!');
                redirect(BASE_URL . '/dashboard');
            } else {
                setFlashMessage('error', $result['message']);
                redirect(BASE_URL . '/rooms/join');
            }
        }
        
        $allRooms = $this->roomModel->getAllRooms();
        include_once __DIR__ . '/../views/rooms/join.php';
    }
    
    public function view($id) {
        $room = $this->roomModel->getRoomById($id);
        
        if (!$room) {
            setFlashMessage('error', 'Room not found');
            redirect(BASE_URL . '/rooms');
        }
        
        $currentUser = getCurrentUser();
        $isMember = isUserInRoom($id, $currentUser['id']);
        $isOwner = $currentUser['id'] === $room['owner_id'];
        
        $members = $this->roomModel->getRoomMembers($id);
        $leaderboard = $this->roomModel->getRoomLeaderboard($id);
        $upcomingMatches = $this->matchModel->getUpcomingMatches(10);
        
        include_once __DIR__ . '/../views/rooms/view.php';
    }
    
    public function members($id) {
        $room = $this->roomModel->getRoomById($id);
        
        if (!$room) {
            setFlashMessage('error', 'Room not found');
            redirect(BASE_URL . '/rooms');
        }
        
        if (!isUserInRoom($id, getCurrentUserId())) {
            setFlashMessage('error', 'You must be a member to view this room');
            redirect(BASE_URL . '/rooms');
        }
        
        $members = $this->roomModel->getRoomMembers($id);
        $currentUser = getCurrentUser();
        $isOwner = $currentUser['id'] === $room['owner_id'];
        
        if ($isOwner && isPostRequest() && isset($_POST['remove_member'])) {
            $result = $this->roomModel->removeMember($id, (int)$_POST['member_id']);
            
            if ($result['success']) {
                setFlashMessage('success', 'Member removed successfully');
            } else {
                setFlashMessage('error', $result['message']);
            }
            
            redirect(BASE_URL . "/rooms/members/{$id}");
        }
        
        include_once __DIR__ . '/../views/rooms/members.php';
    }
    
    public function edit($id) {
        $room = $this->roomModel->getRoomById($id);
        
        if (!$room) {
            setFlashMessage('error', 'Room not found');
            redirect(BASE_URL . '/rooms');
        }
        
        $currentUser = getCurrentUser();
        
        if ($currentUser['id'] !== $room['owner_id']) {
            setFlashMessage('error', 'Only room owner can edit this room');
            redirect(BASE_URL . "/rooms/view/{$id}");
        }
        
        if (isPostRequest()) {
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'max_members' => (int)($_POST['max_members'] ?? MAX_ROOM_MEMBERS)
            ];
            
            $result = $this->roomModel->updateRoom($id, $data);
            
            if ($result['success']) {
                setFlashMessage('success', 'Room updated successfully');
                redirect(BASE_URL . "/rooms/view/{$id}");
            } else {
                setFlashMessage('error', $result['message']);
            }
        }
        
        include_once __DIR__ . '/../views/rooms/edit.php';
    }
    
    public function delete($id) {
        $room = $this->roomModel->getRoomById($id);
        
        if (!$room) {
            setFlashMessage('error', 'Room not found');
            redirect(BASE_URL . '/rooms');
        }
        
        $currentUser = getCurrentUser();
        
        if ($currentUser['id'] !== $room['owner_id']) {
            setFlashMessage('error', 'Only room owner can delete this room');
            redirect(BASE_URL . "/rooms/view/{$id}");
        }
        
        $result = $this->roomModel->deleteRoom($id);
        
        if ($result['success']) {
            setFlashMessage('success', 'Room deleted successfully');
        } else {
            setFlashMessage('error', $result['message']);
        }
        
        redirect(BASE_URL . '/rooms');
    }
    
    public function leave($id) {
        $room = $this->roomModel->getRoomById($id);
        
        if (!$room) {
            setFlashMessage('error', 'Room not found');
            redirect(BASE_URL . '/rooms');
        }
        
        $currentUser = getCurrentUser();
        
        if ($currentUser['id'] === $room['owner_id']) {
            setFlashMessage('error', 'Owner cannot leave the room. Delete the room instead.');
            redirect(BASE_URL . "/rooms/view/{$id}");
        }
        
        $result = $this->roomModel->leaveRoom($currentUser['id'], $id);
        
        if ($result['success']) {
            setFlashMessage('success', 'You have left the room');
        } else {
            setFlashMessage('error', $result['message']);
        }
        
        redirect(BASE_URL . '/dashboard');
    }
}
