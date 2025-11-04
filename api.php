<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Lade die gleiche Logik wie dashboard_analyzer.php aber nur für Daten
$config = require_once 'config.php';

class DataAPI {
    private $heinsbergPath;
    private $fleetPath;
    private $data = ['lines' => [], 'routes' => [], 'tours' => [], 'vehicles' => []];
    private $tourDetails = [];
    private $stats = [];
    
    public function __construct() {
        $this->heinsbergPath = __DIR__ . DIRECTORY_SEPARATOR . $GLOBALS['config']['paths']['operating_plan'];
        $this->fleetPath = __DIR__ . DIRECTORY_SEPARATOR . $GLOBALS['config']['paths']['vehicle_fleet'];
        
        $this->loadAllData();
        $this->analyzeTourDetails();
        $this->calculateStats();
    }
    
    private function loadJsonFile($filePath) {
        if (!file_exists($filePath)) return null;
        $content = file_get_contents($filePath);
        if (!$content) return null;
        
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
        }
        $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $content);
        $content = trim($content);
        
        return json_decode($content, true);
    }
    
    private function loadAllData() {
        $this->loadLines();
        $this->loadRoutes();
        $this->loadTours();
        $this->loadVehicles();
    }
    
    private function loadLines() {
        $linesPath = $this->heinsbergPath . DIRECTORY_SEPARATOR . 'Lines';
        if (!is_dir($linesPath)) return;
        
        $files = glob($linesPath . DIRECTORY_SEPARATOR . '*.line');
        foreach ($files as $file) {
            $data = $this->loadJsonFile($file);
            if ($data) {
                $this->data['lines'][basename($file, '.line')] = $data;
            }
        }
    }
    
    private function loadRoutes() {
        $routesPath = $this->heinsbergPath . DIRECTORY_SEPARATOR . 'Routes';
        if (!is_dir($routesPath)) return;
        
        $files = glob($routesPath . DIRECTORY_SEPARATOR . '*.lineRoute');
        foreach ($files as $file) {
            $data = $this->loadJsonFile($file);
            if ($data) {
                $this->data['routes'][basename($file, '.lineRoute')] = $data;
            }
        }
    }
    
    private function loadTours() {
        $toursPath = $this->heinsbergPath . DIRECTORY_SEPARATOR . 'Tours';
        if (!is_dir($toursPath)) return;
        
        $files = glob($toursPath . DIRECTORY_SEPARATOR . '*.tour');
        foreach ($files as $file) {
            $data = $this->loadJsonFile($file);
            if ($data) {
                $this->data['tours'][basename($file, '.tour')] = $data;
            }
        }
    }
    
    private function loadVehicles() {
        $vehicleFile = $this->fleetPath . DIRECTORY_SEPARATOR . 'config.vehiclefleet';
        $data = $this->loadJsonFile($vehicleFile);
        if ($data && isset($data['vehicles'])) {
            foreach ($data['vehicles'] as $vehicle) {
                if (isset($vehicle['id'])) {
                    $this->data['vehicles'][$vehicle['id']] = $vehicle;
                }
            }
        }
    }
    
    private function analyzeTourDetails() {
        foreach ($this->data['tours'] as $tourName => $tourData) {
            $this->tourDetails[$tourName] = $this->getTourDetails($tourName, $tourData);
        }
    }
    
    private function getTourDetails($tourName, $tourData) {
        $details = [
            'name' => $tourName,
            'vehicleId' => $tourData['vehicleId'] ?? null,
            'vehicle' => null,
            'line' => $tourData['line'] ?? 'Unbekannt',
            'slots' => $tourData['slots'] ?? [],
            'stops' => 0,
            'distance' => 0,
            'time' => 0,
            'efficiency' => 0
        ];
        
        if ($details['vehicleId'] && isset($this->data['vehicles'][$details['vehicleId']])) {
            $details['vehicle'] = $this->data['vehicles'][$details['vehicleId']];
            $details['efficiency'] = rand(85, 98); // Simulation für Demo
        }
        
        // Berechne Stops und andere Metriken
        if (isset($tourData['slots'])) {
            foreach ($tourData['slots'] as $slot) {
                if (isset($slot['routeId'])) {
                    $routeKey = $details['line'] . ' - ' . sprintf('%02d', $slot['routeId']);
                    if (isset($this->data['routes'][$routeKey])) {
                        $route = $this->data['routes'][$routeKey];
                        if (isset($route['routeStops'])) {
                            $details['stops'] += count($route['routeStops']);
                            $lastStop = end($route['routeStops']);
                            $details['distance'] += $lastStop['distanceInMeters'] ?? 0;
                        }
                    }
                }
            }
        }
        
        return $details;
    }
    
    private function calculateStats() {
        $this->stats = [
            'totalTours' => count($this->data['tours']),
            'totalVehicles' => count($this->data['vehicles']),
            'totalLines' => count($this->data['lines']),
            'totalDistance' => 0,
            'totalTime' => 0,
            'toursWithVehicles' => 0,
            'lineStats' => [],
            'vehicleTypes' => []
        ];
        
        foreach ($this->tourDetails as $tour) {
            if ($tour['vehicleId']) {
                $this->stats['toursWithVehicles']++;
            }
            
            $this->stats['totalDistance'] += $tour['distance'];
            
            // Line stats
            $line = $tour['line'];
            if (!isset($this->stats['lineStats'][$line])) {
                $this->stats['lineStats'][$line] = ['tours' => 0, 'distance' => 0];
            }
            $this->stats['lineStats'][$line]['tours']++;
            $this->stats['lineStats'][$line]['distance'] += $tour['distance'];
        }
        
        // Vehicle types
        foreach ($this->data['vehicles'] as $vehicle) {
            $type = $vehicle['vehicleType'] ?? 'Unbekannt';
            $this->stats['vehicleTypes'][$type] = ($this->stats['vehicleTypes'][$type] ?? 0) + 1;
        }
    }
    
    public function getAPIData() {
        return [
            'stats' => $this->stats,
            'tours' => array_slice($this->tourDetails, 0, 10), // Erste 10 für Demo
            'vehicles' => array_slice($this->data['vehicles'], 0, 5), // Erste 5 für Demo
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}

$api = new DataAPI();
echo json_encode($api->getAPIData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>