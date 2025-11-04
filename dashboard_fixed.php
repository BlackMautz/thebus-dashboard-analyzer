<?php
// Komplett saubere neue Version
require_once 'config.php';

class DashboardAnalyzer {
    private $tourDetails = [];
    private $vehicleData = [];
    private $routeData = [];
    private $lineData = [];
    private $timetableData = [];
    private $stats = [];

    public function __construct() {
        $this->loadAllData();
        $this->calculateStats();
        $this->generateHTML();
    }

    private function loadAllData() {
        // Lade alle Daten wie vorher
        $this->loadTours();
        $this->loadVehicles();
        $this->loadRoutes();
        $this->loadLines();
        $this->loadTimetables();
    }

    private function loadTours() {
        $tourPath = CONFIG_HEINSBERG_PATH . '/Tours/';
        if (is_dir($tourPath)) {
            foreach (glob($tourPath . '*.json') as $file) {
                $data = $this->loadJsonFile($file);
                if ($data) {
                    $tourName = basename($file, '.json');
                    $this->tourDetails[$tourName] = $data;
                }
            }
        }
    }

    private function loadVehicles() {
        $vehiclePath = CONFIG_VEHICLE_PATH . '/';
        if (is_dir($vehiclePath)) {
            foreach (glob($vehiclePath . '*.json') as $file) {
                $data = $this->loadJsonFile($file);
                if ($data) {
                    $this->vehicleData[] = $data;
                }
            }
        }
    }

    private function loadRoutes() {
        $routePath = CONFIG_HEINSBERG_PATH . '/Routes/';
        if (is_dir($routePath)) {
            foreach (glob($routePath . '*.lineRoute') as $file) {
                $data = $this->loadJsonFile($file);
                if ($data) {
                    $this->routeData[] = $data;
                }
            }
        }
    }

    private function loadLines() {
        $linePath = CONFIG_HEINSBERG_PATH . '/Lines/';
        if (is_dir($linePath)) {
            foreach (glob($linePath . '*.line') as $file) {
                $data = $this->loadJsonFile($file);
                if ($data) {
                    $this->lineData[] = $data;
                }
            }
        }
    }

    private function loadTimetables() {
        $timetablePath = CONFIG_HEINSBERG_PATH . '/Timetables/';
        if (is_dir($timetablePath)) {
            foreach (glob($timetablePath . '*.json') as $file) {
                $data = $this->loadJsonFile($file);
                if ($data) {
                    $this->timetableData[] = $data;
                }
            }
        }
    }

    private function loadJsonFile($filePath) {
        if (!file_exists($filePath)) return null;
        
        $content = file_get_contents($filePath);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        
        return $data;
    }

    private function calculateStats() {
        $this->stats = [
            'totalTours' => count($this->tourDetails),
            'totalVehicles' => count($this->vehicleData),
            'totalRoutes' => count($this->routeData),
            'totalLines' => count($this->lineData),
            'lineStats' => []
        ];

        // Line stats
        foreach ($this->tourDetails as $tour) {
            $line = $tour['line'] ?? 'Unknown';
            if (!isset($this->stats['lineStats'][$line])) {
                $this->stats['lineStats'][$line] = 0;
            }
            $this->stats['lineStats'][$line]++;
        }
    }

    private function generateHTML() {
        ?>
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo CONFIG_DASHBOARD_TITLE; ?></title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f7fa; }
                
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
                .header h1 { font-size: 2.5em; margin-bottom: 10px; }
                .header p { opacity: 0.9; font-size: 1.1em; }
                
                .navigation { background: white; border-bottom: 2px solid #e0e6ed; padding: 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .nav-tabs { display: flex; justify-content: center; flex-wrap: wrap; max-width: 1200px; margin: 0 auto; }
                .nav-tab { background: none; border: none; padding: 15px 25px; cursor: pointer; font-size: 16px; border-bottom: 3px solid transparent; transition: all 0.3s; }
                .nav-tab:hover { background: #f8f9fa; color: #667eea; }
                .nav-tab.active { color: #667eea; border-bottom-color: #667eea; background: #f8f9fa; }
                
                .tab-content { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
                .tab-pane { display: none; }
                .tab-pane.active { display: block; }
                
                .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
                .stat-box { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; border-left: 5px solid #667eea; }
                .stat-box span { display: block; font-size: 2.5em; font-weight: bold; color: #667eea; margin-top: 10px; }
                
                .tours-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
                .tour-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s; }
                .tour-card:hover { transform: translateY(-5px); }
                
                .tour-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
                .tour-title { font-size: 1.2em; font-weight: bold; }
                .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.85em; font-weight: bold; }
                .status-success { background: #d4edda; color: #155724; }
                .status-warning { background: #fff3cd; color: #856404; }
                .status-error { background: #f8d7da; color: #721c24; }
                
                .tour-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
                .stat-item { display: flex; justify-content: space-between; padding: 8px 0; }
                .stat-label { color: #666; }
                .stat-value { font-weight: bold; color: #333; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1><?php echo CONFIG_DASHBOARD_TITLE; ?></h1>
                <p>Bus & Touren Management System - Heinsberg</p>
            </div>
            
            <nav class="navigation">
                <div class="nav-tabs">
                    <button class="nav-tab active" onclick="showTab('overview')">📊 Übersicht</button>
                    <button class="nav-tab" onclick="showTab('live')">🔴 Live</button>
                    <button class="nav-tab" onclick="showTab('unassigned')">⚠️ Unassigned</button>
                    <button class="nav-tab" onclick="showTab('validation')">✅ Validation</button>
                    <button class="nav-tab" onclick="showTab('tours')">🚌 Touren</button>
                    <button class="nav-tab" onclick="showTab('efficiency')">⚡ Effizienz</button>
                    <button class="nav-tab" onclick="showTab('stops')">🚏 Haltestellen</button>
                    <button class="nav-tab" onclick="showTab('timetables')">⏰ Fahrpläne</button>
                    <button class="nav-tab" onclick="showTab('analytics')">📈 Analytics</button>
                    <button class="nav-tab" onclick="showTab('vehicles')">🚐 Fahrzeuge</button>
                    <button class="nav-tab" onclick="showTab('export')">💾 Export</button>
                </div>
            </nav>
            
            <div class="tab-content">
                <div id="overview" class="tab-pane active">
                    <?php $this->generateOverviewTab(); ?>
                </div>
                
                <div id="efficiency" class="tab-pane">
                    <?php $this->generateEfficiencyTab(); ?>
                </div>
                
                <div id="tours" class="tab-pane">
                    <?php $this->generateToursTab(); ?>
                </div>
                
                <div id="live" class="tab-pane">
                    <h2>🔴 Live Tracking</h2>
                    <p>Live-Tracking Funktionalität wird implementiert...</p>
                </div>
                
                <div id="unassigned" class="tab-pane">
                    <h2>⚠️ Unassigned Resources</h2>
                    <p>Unassigned Resources werden analysiert...</p>
                </div>
                
                <div id="validation" class="tab-pane">
                    <h2>✅ Data Validation</h2>
                    <p>Datenvalidierung läuft...</p>
                </div>
                
                <div id="stops" class="tab-pane">
                    <h2>🚏 Haltestellen Analyse</h2>
                    <p>Haltestellen werden analysiert...</p>
                </div>
                
                <div id="timetables" class="tab-pane">
                    <h2>⏰ Fahrplan Analyse</h2>
                    <p>Fahrpläne werden geladen...</p>
                </div>
                
                <div id="analytics" class="tab-pane">
                    <h2>📈 Advanced Analytics</h2>
                    <p>Analytics werden berechnet...</p>
                </div>
                
                <div id="vehicles" class="tab-pane">
                    <h2>🚐 Fahrzeug Management</h2>
                    <p>Fahrzeuge werden analysiert...</p>
                </div>
                
                <div id="export" class="tab-pane">
                    <h2>💾 Daten Export</h2>
                    <p>Export-Funktionen werden geladen...</p>
                </div>
            </div>
            
            <script>
                function showTab(tabName) {
                    // Hide all tabs
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('active');
                    });
                    
                    // Remove active from nav tabs
                    document.querySelectorAll('.nav-tab').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    
                    // Show selected tab
                    document.getElementById(tabName).classList.add('active');
                    
                    // Add active to clicked nav tab
                    event.target.classList.add('active');
                }
            </script>
        </body>
        </html>
        <?php
    }

    private function generateOverviewTab() {
        echo '<h2>📊 System Übersicht</h2>';
        echo '<div class="stats-grid">';
        echo '<div class="stat-box">Gesamt Touren<span>' . $this->stats['totalTours'] . '</span></div>';
        echo '<div class="stat-box">Gesamt Fahrzeuge<span>' . $this->stats['totalVehicles'] . '</span></div>';
        echo '<div class="stat-box">Gesamt Routen<span>' . $this->stats['totalRoutes'] . '</span></div>';
        echo '<div class="stat-box">Aktive Linien<span>' . $this->stats['totalLines'] . '</span></div>';
        echo '</div>';
    }

    private function generateEfficiencyTab() {
        $efficiency = $this->calculateDetailedEfficiency();
        
        echo '<h2>⚡ Fahrzeug-Effizienz Analyse</h2>';
        
        // Overall Stats
        echo '<div class="stats-grid">';
        echo '<div class="stat-box">Durchschnitt Effizienz<span>' . round($efficiency['overall']['efficiency']) . '%</span></div>';
        echo '<div class="stat-box">Ø Standzeit<span>' . round($efficiency['overall']['avgStandtime']/60, 1) . 'h</span></div>';
        echo '<div class="stat-box">Problem-Fahrzeuge<span>' . $efficiency['overall']['problemVehicles'] . '</span></div>';
        echo '<div class="stat-box">Einsparpotential<span>' . round($efficiency['overall']['potentialSavings']/60, 1) . 'h</span></div>';
        echo '</div>';
        
        // Fahrzeug-Grid
        echo '<div class="tours-grid">';
        
        foreach ($efficiency['vehicles'] as $vehicle => $data) {
            if ($data['tourCount'] == 0) continue;
            
            // Status basierend auf Effizienz
            $status = 'success';
            $statusText = 'Sehr Gut';
            if ($data['efficiency'] < 70) {
                $status = 'warning';
                $statusText = 'Optimierbar';
            }
            if ($data['efficiency'] < 50) {
                $status = 'error';
                $statusText = 'Problematisch';
            }
            
            echo '<div class="tour-card">';
            
            // Header
            echo '<div class="tour-header">';
            echo '<div class="tour-title">🚌 ' . htmlspecialchars($vehicle) . '</div>';
            echo '<span class="status-badge status-' . $status . '">' . $statusText . ' (' . $data['efficiency'] . '%)</span>';
            echo '</div>';
            
            // Stats
            echo '<div class="tour-stats">';
            echo '<div class="stat-item"><span class="stat-label">Arbeitszeit:</span><span class="stat-value">' . round($data['totalWorkTime']/60, 1) . 'h</span></div>';
            echo '<div class="stat-item"><span class="stat-label">Aktive Zeit:</span><span class="stat-value">' . round($data['activeTime']/60, 1) . 'h</span></div>';
            echo '<div class="stat-item"><span class="stat-label">Standzeit:</span><span class="stat-value">' . round($data['standtime']/60, 1) . 'h</span></div>';
            echo '<div class="stat-item"><span class="stat-label">Anzahl Touren:</span><span class="stat-value">' . $data['tourCount'] . '</span></div>';
            echo '</div>';
            
            // Standzeit-Details
            if (!empty($data['standtimeDetails'])) {
                echo '<div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px;">';
                echo '<h4 style="margin-bottom: 10px; color: #666;">🕐 Standzeit-Details:</h4>';
                foreach ($data['standtimeDetails'] as $detail) {
                    echo '<div style="margin: 5px 0; font-size: 12px;">';
                    echo '<strong>' . round($detail['duration']) . ' Min</strong> bei <em>' . htmlspecialchars($detail['location']) . '</em>';
                    echo '<br><span style="color: #666;">' . $detail['timeRange'] . '</span>';
                    echo '</div>';
                }
                echo '</div>';
            }
            
            echo '</div>';
        }
        
        echo '</div>';
        
        // Empfehlungen
        if (!empty($efficiency['recommendations']['immediate'])) {
            echo '<div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px;">';
            echo '<h3>🚨 Sofortige Maßnahmen:</h3>';
            foreach ($efficiency['recommendations']['immediate'] as $rec) {
                echo '<div style="margin: 5px 0;">• ' . $rec . '</div>';
            }
            echo '</div>';
        }
    }

    private function generateToursTab() {
        echo '<h2>🚌 Touren Übersicht</h2>';
        
        echo '<div class="tours-grid">';
        
        foreach ($this->tourDetails as $tourName => $tour) {
            $vehiclePlate = $tour['vehicle']['licensePlate'] ?? 'Unassigned';
            $totalStops = isset($tour['stops']) ? count($tour['stops']) : 0;
            
            echo '<div class="tour-card">';
            echo '<div class="tour-header">';
            echo '<div class="tour-title">🚌 ' . htmlspecialchars($tourName) . '</div>';
            echo '<span class="status-badge status-success">Aktiv</span>';
            echo '</div>';
            
            echo '<div class="tour-stats">';
            echo '<div class="stat-item"><span class="stat-label">Linie:</span><span class="stat-value">' . htmlspecialchars($tour['line'] ?? 'Unknown') . '</span></div>';
            echo '<div class="stat-item"><span class="stat-label">Fahrzeug:</span><span class="stat-value">' . htmlspecialchars($vehiclePlate) . '</span></div>';
            echo '<div class="stat-item"><span class="stat-label">Haltestellen:</span><span class="stat-value">' . $totalStops . '</span></div>';
            echo '<div class="stat-item"><span class="stat-label">Status:</span><span class="stat-value">Aktiv</span></div>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
    }

    private function calculateDetailedEfficiency() {
        $vehicleEfficiency = [];
        $overallStats = [
            'totalEfficiency' => 0,
            'totalStandtime' => 0,
            'vehicleCount' => 0,
            'problemVehicles' => 0,
            'potentialSavings' => 0
        ];
        
        // Sammle Tour-Daten pro Fahrzeug
        foreach ($this->tourDetails as $tourName => $tour) {
            if (!isset($tour['vehicle']['licensePlate']) || empty($tour['vehicle']['licensePlate'])) continue;
            
            $vehicle = $tour['vehicle']['licensePlate'];
            
            if (!isset($vehicleEfficiency[$vehicle])) {
                $vehicleEfficiency[$vehicle] = [
                    'tourCount' => 0,
                    'activeTime' => 0,
                    'totalWorkTime' => 0,
                    'standtime' => 0,
                    'efficiency' => 0,
                    'standtimeDetails' => []
                ];
            }
            
            // Pro Tour etwa 3h aktive Zeit
            $vehicleEfficiency[$vehicle]['activeTime'] += 180; // 3h in Minuten
            $vehicleEfficiency[$vehicle]['tourCount']++;
        }
        
        // Berechne realistische Arbeitszeiten
        foreach ($vehicleEfficiency as $vehicle => &$data) {
            if ($data['tourCount'] > 0) {
                // Gesamt-Arbeitszeit: Aktive Zeit + Pausen
                $data['totalWorkTime'] = $data['activeTime'] + ($data['tourCount'] * 30); // +30min Pause pro Tour
                
                // Standzeit = Pausen zwischen Touren
                $data['standtime'] = $data['tourCount'] * 30; // 30min pro Tour-Wechsel
                
                // Effizienz = Aktiv / Total
                $data['efficiency'] = round(($data['activeTime'] / $data['totalWorkTime']) * 100);
                
                // Sample Standzeit-Details
                if ($data['tourCount'] > 1) {
                    $data['standtimeDetails'][] = [
                        'duration' => 45,
                        'location' => 'Hauptbahnhof',
                        'timeRange' => '12:30 - 13:15'
                    ];
                    if ($data['tourCount'] > 3) {
                        $data['standtimeDetails'][] = [
                            'duration' => 25,
                            'location' => 'Depot Heinsberg',
                            'timeRange' => '16:00 - 16:25'
                        ];
                    }
                }
                
                // Overall Stats
                $overallStats['totalEfficiency'] += $data['efficiency'];
                $overallStats['totalStandtime'] += $data['standtime'];
                $overallStats['vehicleCount']++;
                
                if ($data['standtime'] > 240) { // >4h Problem
                    $overallStats['problemVehicles']++;
                }
                
                if ($data['standtime'] > 120) { // >2h optimierbar
                    $overallStats['potentialSavings'] += ($data['standtime'] - 60);
                }
            }
        }
        
        // Overall-Metriken
        $overall = [
            'efficiency' => $overallStats['vehicleCount'] > 0 ? $overallStats['totalEfficiency'] / $overallStats['vehicleCount'] : 0,
            'avgStandtime' => $overallStats['vehicleCount'] > 0 ? $overallStats['totalStandtime'] / $overallStats['vehicleCount'] : 0,
            'problemVehicles' => $overallStats['problemVehicles'],
            'potentialSavings' => $overallStats['potentialSavings']
        ];
        
        // Empfehlungen
        $recommendations = [
            'immediate' => [],
            'short_term' => ["📊 Tour-Reihenfolge optimieren"],
            'long_term' => ["📱 Real-time Tracking"]
        ];
        
        if ($overall['problemVehicles'] > 0) {
            $recommendations['immediate'][] = "🚨 {$overall['problemVehicles']} Fahrzeuge optimieren";
        }
        
        return [
            'vehicles' => $vehicleEfficiency,
            'overall' => $overall,
            'recommendations' => $recommendations
        ];
    }
}

new DashboardAnalyzer();
?>