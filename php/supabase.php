<?php
require_once 'config.php';

class SupabaseClient {
    private $baseUrl;
    private $apiKey;
    private $serviceKey;
    
    public function __construct() {
        $this->baseUrl = SUPABASE_URL . '/rest/v1';
        $this->apiKey = SUPABASE_KEY;
        $this->serviceKey = SUPABASE_SERVICE_KEY;
    }
    
    /**
     * Make a request to Supabase REST API
     */
    private function makeRequest($endpoint, $method = 'GET', $data = null, $headers = [], $useServiceKey = false) {
        $url = $this->baseUrl . $endpoint;
        
        $key = $useServiceKey ? $this->serviceKey : $this->apiKey;
        
        $defaultHeaders = [
            'apikey: ' . $key,
            'Authorization: Bearer ' . $key,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];
        
        $headers = array_merge($defaultHeaders, $headers);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }
    
    /**
     * Insert a new record
     */
    public function insert($table, $data) {
        return $this->makeRequest('/' . $table, 'POST', $data, [], true);
    }
    
    /**
     * Select records with optional filters
     */
    public function select($table, $columns = '*', $filters = []) {
        $query = '?select=' . $columns;
        
        foreach ($filters as $key => $value) {
            $query .= '&' . $key . '=eq.' . urlencode($value);
        }
        
        return $this->makeRequest('/' . $table . $query, 'GET');
    }
    
    /**
     * Select records with service key (admin function)
     */
    public function selectWithServiceKey($table, $columns = '*', $filters = []) {
        $query = '?select=' . $columns;
        
        foreach ($filters as $key => $value) {
            $query .= '&' . $key . '=eq.' . urlencode($value);
        }
        
        return $this->makeRequest('/' . $table . $query, 'GET', null, [], true);
    }
    
    /**
     * Custom query with full query string
     */
    public function customQuery($table, $queryString) {
        return $this->makeRequest('/' . $table . $queryString, 'GET');
    }

    /**
     * Custom query using service key (bypasses RLS - admin/backend use)
     */
    public function customQueryWithServiceKey($table, $queryString) {
        return $this->makeRequest('/' . $table . $queryString, 'GET', null, [], true);
    }
    
    /**
     * Update records
     */
    public function update($table, $data, $filters = []) {
        $query = '?';
        $first = true;
        
        foreach ($filters as $key => $value) {
            if (!$first) $query .= '&';
            $query .= $key . '=eq.' . urlencode($value);
            $first = false;
        }
        
        return $this->makeRequest('/' . $table . $query, 'PATCH', $data, [], true);
    }
    
    /**
     * Delete records
     */
    public function delete($table, $filters = []) {
        $query = '?';
        $first = true;
        
        foreach ($filters as $key => $value) {
            if (!$first) $query .= '&';
            $query .= $key . '=eq.' . urlencode($value);
            $first = false;
        }
        
        return $this->makeRequest('/' . $table . $query, 'DELETE', null, [], true);
    }
    
    /**
     * Upload file to Supabase Storage
     */
    public function uploadFile($bucket, $path, $file) {
        $url = SUPABASE_URL . '/storage/v1/object/' . $bucket . '/' . $path;
        
        $headers = [
            'apikey: ' . $this->apiKey,
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: ' . mime_content_type($file)
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }
    
    /**
     * Get public URL for uploaded file
     */
    public function getPublicUrl($bucket, $path) {
        return SUPABASE_URL . '/storage/v1/object/public/' . $bucket . '/' . $path;
    }
    
    /**
     * Select all records from a table (admin function)
     */
    public function selectAll($table, $columns = '*') {
        return $this->makeRequest('/' . $table . '?select=' . $columns, 'GET', null, [], true);
    }
    
    /**
     * Delete file from Supabase Storage
     */
    public function deleteFile($bucket, $path) {
        $url = SUPABASE_URL . '/storage/v1/object/' . $bucket . '/' . $path;
        
        $headers = [
            'apikey: ' . $this->serviceKey,
            'Authorization: Bearer ' . $this->serviceKey
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }
}
?>
