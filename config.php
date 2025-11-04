<?php
/**
 * TheBus Dashboard - Konfigurationsdatei
 * 
 * ANLEITUNG FÜR ANDERE USER:
 * 1. Diese Datei anpassen (nur hier!)
 * 2. Ordnernamen zu deinen Daten-Ordnern ändern
 * 3. Optional: Zeiten und Schwellenwerte anpassen
 * 4. Server starten: php -S localhost:8080
 */

return [
    // ===== PFADE (ZWINGEND ANPASSEN!) =====
    'paths' => [
        'operating_plan' => 'HeinsbergServerBlackMautz',  // ← HIER deinen OperatingPlan-Ordner eintragen
        'vehicle_fleet' => 'FlottensystemMAUTZ',          // ← HIER deinen VehicleFleet-Ordner eintragen
    ],
    
    // ===== ZEITEN & KATEGORIEN =====
    'time_settings' => [
        // Rush Hour Zeiten (24h Format)
        'rush_hours' => [
            'morning' => ['start' => 7, 'end' => 9],      // Morgens 07:00-09:00
            'evening' => ['start' => 16, 'end' => 18],    // Abends 16:00-18:00
        ],
        
        // Zeitkategorien für 24h-Analyse
        'time_slots' => [
            'Nacht (00-05)',
            'Früh (06-09)', 
            'Vormittag (10-11)',
            'Mittag (12-13)',
            'Nachmittag (14-17)',
            'Abend (18-21)',
            'Spät (22-23)'
        ],
        
        // Takt-Analyse
        'gap_threshold' => 60,  // Minuten - ab wann gilt eine Lücke als "groß"
    ],
    
    // ===== VERKEHRSDICHTE SCHWELLENWERTE =====
    'traffic_density' => [
        'high' => 50,    // Ab 50 Fahrten = "Hoch"
        'medium' => 20,  // Ab 20 Fahrten = "Medium"
        // Unter 20 = "Niedrig"
    ],
    
    // ===== OPTIMIERUNG SETTINGS =====
    'optimization' => [
        'rush_hour_service_threshold' => 0.4,  // 40% der Fahrten sollten in Rush Hours sein
        'max_display_gaps' => 5,                // Max. Anzahl großer Lücken anzeigen
    ],
    
    // ===== LIVE SIMULATION =====
    'simulation' => [
        'default_trip_duration' => 60,  // Standard Fahrtdauer in Minuten für Live-Tracking
    ],
    
    // ===== UI EINSTELLUNGEN =====
    'ui' => [
        'container_max_width' => '1600px',
        'list_max_height' => '300px',
        'primary_color' => '#007bff',
        'success_color' => '#28a745',
        'warning_color' => '#ffc107',
        'danger_color' => '#dc3545',
        'rush_hour_color' => '#ff6b35',
    ],
    
    // ===== SPRACHE / LABELS =====
    'labels' => [
        'tabs' => [
            'overview' => 'Übersicht',
            'live' => 'Live',
            'unassigned' => 'Unassigned',
            'validation' => 'Validierung',
            'tours' => 'Touren',
            'stops' => 'Haltestellen',
            'timetables' => 'Fahrpläne',
            'analytics' => 'Analytics',
            'vehicles' => 'Fahrzeuge',
            'export' => 'Export'
        ],
        
        'traffic_density' => [
            'low' => 'Niedrig',
            'medium' => 'Medium', 
            'high' => 'Hoch'
        ],
        
        'messages' => [
            'unknown_destination' => 'Unbekanntes Ziel',
            'no_gaps_found' => '✅ Keine großen Lücken gefunden',
            'large_gaps_found' => '⚠️ Große Lücken (>%d Min):',
            'developer_info_toggle' => '🔧 Entwickler-Infos anzeigen/verstecken'
        ]
    ]
];