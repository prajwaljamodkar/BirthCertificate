<?php
class SupabaseDB {
    private $url;
    private $key;
    
    public function __construct() {
        $this->url = getenv('DB_HOST');
        $this->key = getenv('DB_ANON_KEY');
    }
    
    public function query($table, $filters = []) {
        $query = "{$this->url}/rest/v1/{$table}?select=*";
        // Add filter logic here
        
        $curl = curl_init($query);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'apikey: ' . $this->key,
                'Authorization: Bearer ' . $this->key
            ]
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($response, true);
    }
}
?>