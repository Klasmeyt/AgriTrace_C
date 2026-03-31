<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $path);

$table = $parts[1] ?? '';
$id = $parts[3] ?? null;

switch ($table) {
    case 'users': handleTable('users', $method, $id); break;
    case 'farms': handleTable('farms', $method, $id); break;
    case 'livestock': handleTable('livestock', $method, $id); break;
    case 'incidents': handleTable('incidents', $method, $id); break;
    case 'public_reports': handleTable('public_reports', $method, $id); break;
    case 'audit_log': handleTable('audit_log', $method, $id, true); break;
    case 'login': handleLogin(); break;
    case 'register': handleRegister(); break;
    default: apiResponse(['error' => 'Invalid endpoint'], 404);
}

function handleTable($table, $method, $id, $adminOnly = false) {
    global $supabase, $service_supabase;
    
    $client = $adminOnly ? $service_supabase : $supabase;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $res = $client->from($table)->select('*')->eq('id', $id)->single();
                apiResponse($res['data'] ?? null);
            } else {
                $res = $client->from($table)->select('*');
                apiResponse($res['data'] ?? []);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $res = $service_supabase->from($table)->insert($data);
            logAudit('Created', $table, json_encode($data));
            apiResponse($res['data'][0] ?? ['success' => true]);
            break;
            
        case 'PUT':
        case 'PATCH':
            if (!$id) apiResponse(['error' => 'ID required'], 400);
            $data = json_decode(file_get_contents('php://input'), true);
            $res = $service_supabase->from($table)->update($data)->eq('id', $id);
            logAudit('Updated', $table, "$table ID: $id");
            apiResponse($res['data'][0] ?? ['success' => true]);
            break;
            
        case 'DELETE':
            if (!$id) apiResponse(['error' => 'ID required'], 400);
            $service_supabase->from($table)->delete()->eq('id', $id);
            logAudit('Deleted', $table, "$table ID: $id");
            apiResponse(['success' => true]);
            break;
    }
}

// Rest of functions remain the same...
function handleLogin() {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = strtolower($input['email'] ?? '');
    
    global $supabase;
    $res = $supabase->from('users')->select('*')->eq('email', $email)->single();
    
    if (!$res['data']) {
        apiResponse(['error' => 'Invalid credentials'], 401);
    }
    
    $user = $res['data'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    
    apiResponse(['user' => $user]);
}

function logAudit($action, $recordType, $description) {
    global $service_supabase;
    $service_supabase->from('audit_log')->insert([
        'user_email' => $_SESSION['email'] ?? 'system',
        'action' => $action,
        'record_type' => $recordType,
        'description' => $description,
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'status' => 'Success'
    ]);
}
?>