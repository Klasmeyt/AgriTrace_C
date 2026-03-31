<?php
require_once 'config.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$parts = explode('/', $path);

switch ($method) {
    case 'POST':
        if ($parts[0] === 'login') {
            handleLogin();
        } elseif ($parts[0] === 'register') {
            handleRegister();
        }
        break;
        
    case 'GET':
        if (isset($parts[1])) {
            getTableData($parts[1]);
        }
        break;
        
    // Add PUT, DELETE handlers...
}

function handleLogin() {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = strtolower(trim($input['email']));
    $password = $input['password'];
    
    // Authenticate with Supabase
    $response = $supabase->auth->signInWithPassword([
        'email' => $email,
        'password' => $password
    ]);
    
    if ($response->user) {
        $_SESSION['user_id'] = $response->user->id;
        $_SESSION['user_email'] = $response->user->email;
        $_SESSION['user_role'] = $response->user->user_metadata->role ?? 'Farmer';
        
        echo json_encode(['success' => true, 'user' => [
            'id' => $response->user->id,
            'email' => $response->user->email,
            'role' => $_SESSION['user_role']
        ]]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
}

function handleRegister() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Insert to Supabase users table
    $user = $supabase->from('users')
        ->insert([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => strtolower($input['email']),
            'mobile' => $input['mobile'],
            'role' => $input['role'],
            'status' => 'Pending'
        ])->select()->single();
    
    echo json_encode(['success' => true, 'user' => $user]);
}

function getTableData($table) {
    requireLogin();
    
    $data = $supabase->from($table)
        ->select('*')
        ->eq('user_id', $_SESSION['user_id']);
    
    echo json_encode($data);
}
?>