<?php
// Supabase Configuration
define('SUPABASE_URL', 'https://vkgwhdhreoxokaohcvxp.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZrZ3doZGhyZW94b2thb2hjdnhwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQxNzI0NzAsImV4cCI6MjA4OTc0ODQ3MH0.gjNM0Ujc7powUhAs9vn1z6bBoyOlvnVuSF1i01tn7y0');
define('SUPABASE_SERVICE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZrZ3doZGhyZW94b2thb2hjdnhwIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3NDE3MjQ3MCwiZXhwIjoyMDg5NzQ4NDcwfQ.TH9zr04Nuj2I9-0Y5PwTCl7dnMO9MRlQbLSdaLb8VDs');

session_start();

// Simple Supabase HTTP Client (works with all versions)
class SupabaseClient {
    private $url;
    private $key;
    
    public function __construct($url, $key) {
        $this->url = rtrim($url, '/');
        $this->key = $key;
    }
    
    private function request($path, $method = 'GET', $data = null) {
        $url = $this->url . '/rest/v1/' . $path;
        $headers = [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'data' => json_decode($response, true),
            'status' => $httpCode
        ];
    }
    
    public function from($table) {
        return new SupabaseTable($this, $table);
    }
}

class SupabaseTable {
    private $client;
    private $table;
    
    public function __construct($client, $table) {
        $this->client = $client;
        $this->table = $table;
    }
    
    public function select($columns = '*') {
        $this->method = 'GET';
        $this->columns = $columns;
        return $this;
    }
    
    public function eq($column, $value) {
        $this->filters[] = "$column=eq.$value";
        return $this;
    }
    
    public function single() {
        $this->limit = 1;
        return $this->execute();
    }
    
    public function execute() {
        $path = $this->table;
        if (!empty($this->filters)) {
            $path .= '?' . implode('&', $this->filters);
        }
        if (isset($this->limit)) {
            $path .= ($path ? '&' : '?') . 'limit=' . $this->limit;
        }
        
        $res = $this->client->request($path, $this->method);
        return $res;
    }
    
    public function insert($data) {
        $this->method = 'POST';
        $this->data = $data;
        return $this->execute();
    }
    
    public function update($data) {
        $this->method = 'PATCH';
        $this->data = $data;
        return $this;
    }
    
    public function delete() {
        $this->method = 'DELETE';
        return $this->execute();
    }
}

// Initialize clients
$supabase = new SupabaseClient(SUPABASE_URL, SUPABASE_ANON_KEY);
$service_supabase = new SupabaseClient(SUPABASE_URL, SUPABASE_SERVICE_KEY);

// Helper functions (same as before)
function apiResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUserRole() {
    return $_SESSION['role'] ?? null;
}

function requireRole($roles) {
    if (!isLoggedIn() || !in_array(getCurrentUserRole(), (array)$roles)) {
        header('Location: index.php?page=login');
        exit;
    }
}

// CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    exit;
}
?>