<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheBus Dashboard - Übersichtliche Analyse</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        
        .header { background: rgba(255,255,255,0.95); padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { color: #333; font-size: 2.5em; margin-bottom: 10px; }
        .header .subtitle { color: #666; font-size: 1.1em; }
        
        .nav-tabs { background: white; padding: 0; margin: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        .nav-tabs ul { list-style: none; display: flex; background: #f8f9fa; }
        .nav-tabs li { flex: 1; }
        .nav-tabs a { display: block; padding: 15px 20px; text-decoration: none; color: #666; font-weight: bold; text-align: center; transition: all 0.3s; }
        .nav-tabs a:hover, .nav-tabs a.active { background: #007bff; color: white; }
        
        .container { max-width: 1600px; margin: 0 auto; padding: 0 20px; }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-number { font-size: 2.5em; font-weight: bold; color: #007bff; margin-bottom: 5px; }
        .stat-label { color: #666; font-size: 0.9em; }
        
        .search-filter { background: white; padding: 20px; margin: 20px 0; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .search-filter input, .search-filter select { padding: 10px; margin: 5px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        .search-filter button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        
        .tour-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin: 20px 0; }
        .tour-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s; }
        .tour-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        
        .tour-header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 15px; cursor: pointer; }
        .tour-header h3 { margin: 0; font-size: 1.1em; }
        .tour-header .quick-info { font-size: 0.9em; opacity: 0.9; margin-top: 5px; }
        
        .tour-content { padding: 15px; display: none; }
        .tour-content.expanded { display: block; }
        
        .info-section { margin: 10px 0; padding: 10px; border-radius: 5px; }
        .vehicle-info { background: #e3f2fd; border-left: 4px solid #2196f3; }
        .route-info { background: #f3e5f5; border-left: 4px solid #9c27b0; }
        .schedule-info { background: #e8f5e8; border-left: 4px solid #4caf50; }
        .stops-info { background: #fff3e0; border-left: 4px solid #ff9800; }
        
        .line-badge { display: inline-block; padding: 4px 12px; background: #007bff; color: white; border-radius: 20px; font-size: 0.8em; font-weight: bold; }
        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 0.75em; font-weight: bold; }
        .status-active { background: #4caf50; color: white; }
        .status-inactive { background: #f44336; color: white; }
        
        .collapsible-header { cursor: pointer; font-weight: bold; padding: 5px 0; border-bottom: 1px solid #eee; }
        .collapsible-content { max-height: 200px; overflow-y: auto; margin-top: 10px; }
        
        .stats-overview { background: white; margin: 20px 0; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        .advanced-filters {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .filter-row {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-row select, .filter-row input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .filter-row button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .filter-row button:hover {
            background: #0056b3;
        }
        
        .timetable-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .timetable-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
        }
        
        .timetable-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
            .conditions {
                margin-top: 10px;
                font-size: 12px;
                color: #666;
            }
            
            .route-preview {
                margin-top: 10px;
                padding: 8px;
                background: #f8f9fa;
                border-radius: 4px;
                font-size: 12px;
                border-left: 3px solid #007bff;
            }
            
            .timetable-times {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                margin-top: 8px;
                max-height: 200px;
                overflow-y: auto;
            }
            
            .time-badge {
                background: #007bff;
                color: white;
                padding: 3px 8px;
                border-radius: 12px;
                font-size: 11px;
                white-space: nowrap;
            }
            
            .no-service {
                margin-top: 10px;
                padding: 10px;
                background: #fff3cd;
                border: 1px solid #ffeaa7;
                border-radius: 4px;
                color: #856404;
                font-size: 12px;
            }
            
            .stops-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }
            
            .stop-card {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 15px;
                transition: all 0.3s ease;
            }
            
            .stop-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            }
            
            .stop-times {
                margin-top: 10px;
                font-size: 12px;
            }
            
            .departure-times {
                display: flex;
                flex-wrap: wrap;
                gap: 4px;
                margin-top: 8px;
                max-height: 150px;
                overflow-y: auto;
                padding: 5px;
                border: 1px solid #eee;
                border-radius: 4px;
            }
            
            .departure-badge {
                background: #28a745;
                color: white;
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 10px;
                white-space: nowrap;
            }
            
            .realtime-section {
                padding: 20px;
            }
            
            .realtime-controls {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
            }
            
            .refresh-btn {
                background: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
            }
            
            .refresh-btn:hover {
                background: #0056b3;
            }
            
            .current-time {
                font-weight: bold;
                color: #333;
            }
            
            .realtime-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }
            
            .realtime-card {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
            }
            
            .active-bus, .upcoming-bus {
                background: #f8f9fa;
                border-left: 4px solid #007bff;
                padding: 10px;
                margin: 10px 0;
                border-radius: 4px;
            }
            
            .upcoming-bus.urgent {
                border-left-color: #dc3545;
                background: #fff5f5;
            }
            
            .upcoming-bus.soon {
                border-left-color: #ffc107;
                background: #fffbf0;
            }
            
            .bus-progress {
                width: 100%;
                height: 6px;
                background: #e9ecef;
                border-radius: 3px;
                margin: 5px 0;
                overflow: hidden;
            }
            
            .progress-bar {
                height: 100%;
                background: linear-gradient(90deg, #28a745, #20c997);
                border-radius: 3px;
                transition: width 0.3s ease;
            }
            
            .departure-info {
                float: right;
                text-align: right;
            }
            
            @media (max-width: 768px) {
                .realtime-grid {
                    grid-template-columns: 1fr;
                }
                .realtime-controls {
                    flex-direction: column;
                    gap: 10px;
                }
            }
            
            .unassigned-section {
                padding: 20px;
            }
            
            .unassigned-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-bottom: 20px;
            }
            
            .unassigned-card {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
            }
            
            .vehicles-card {
                border-left: 4px solid #ffc107;
            }
            
            .tours-card {
                border-left: 4px solid #dc3545;
            }
            
            .success-message {
                background: #d4edda;
                color: #155724;
                padding: 15px;
                border-radius: 4px;
                border: 1px solid #c3e6cb;
                margin: 10px 0;
            }
            
            .warning-message {
                background: #fff3cd;
                color: #856404;
                padding: 15px;
                border-radius: 4px;
                border: 1px solid #ffeaa7;
                margin: 10px 0;
            }
            
            .unassigned-list {
                margin: 15px 0;
            }
            
            .unassigned-item {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                padding: 12px;
                margin: 8px 0;
            }
            
            .item-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
            }
            
            .item-type, .item-line {
                background: #007bff;
                color: white;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 12px;
            }
            
            .item-line {
                background: #28a745;
            }
            
            .item-details {
                display: flex;
                gap: 15px;
                font-size: 13px;
                color: #666;
            }
            
            .suggestion-box {
                background: #e3f2fd;
                border: 1px solid #bbdefb;
                border-radius: 6px;
                padding: 15px;
                margin-top: 15px;
            }
            
            .suggestion-box h4 {
                margin: 0 0 10px 0;
                color: #1976d2;
            }
            
            .suggestion-box ul {
                margin: 0;
                padding-left: 20px;
            }
            
            .summary-card {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
                margin-top: 20px;
            }
            
            .summary-stats {
                display: flex;
                justify-content: space-around;
                margin: 20px 0;
            }
            
            .stat-item {
                text-align: center;
            }
            
            .stat-number {
                display: block;
                font-size: 2em;
                font-weight: bold;
                color: #007bff;
            }
            
            .stat-label {
                display: block;
                font-size: 0.9em;
                color: #666;
                margin-top: 5px;
            }
            
            .efficiency-meter {
                margin-top: 20px;
            }
            
            .progress-meter {
                width: 100%;
                height: 20px;
                background: #e9ecef;
                border-radius: 10px;
                overflow: hidden;
                margin: 10px 0;
            }
            
            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #dc3545, #ffc107, #28a745);
                border-radius: 10px;
                transition: width 0.5s ease;
            }
            
            @media (max-width: 768px) {
                .unassigned-grid {
                    grid-template-columns: 1fr;
                }
                .summary-stats {
                    flex-direction: column;
                    gap: 15px;
                }
                .item-details {
                    flex-direction: column;
                    gap: 5px;
                }
            }
            
            .validation-section {
                padding: 20px;
            }
            
            .validation-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-bottom: 20px;
            }
            
            .validation-card {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
            }
            
            .validation-stats {
                display: flex;
                gap: 10px;
                margin: 15px 0;
                flex-wrap: wrap;
            }
            
            .stat-box {
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: bold;
                flex: 1;
                text-align: center;
                min-width: 120px;
            }
            
            .stat-box.error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            
            .stat-box.warning {
                background: #fff3cd;
                color: #856404;
                border: 1px solid #ffeaa7;
            }
            
            .stat-box.info {
                background: #d1ecf1;
                color: #0c5460;
                border: 1px solid #bee5eb;
            }
            
            .issues-list {
                max-height: 300px;
                overflow-y: auto;
                margin: 15px 0;
            }
            
            .issue-item {
                background: #fff5f5;
                border-left: 4px solid #dc3545;
                padding: 8px 12px;
                margin: 5px 0;
                border-radius: 4px;
                font-size: 13px;
            }
            
            .success-box {
                background: #d4edda;
                color: #155724;
                padding: 15px;
                border-radius: 6px;
                border: 1px solid #c3e6cb;
                text-align: center;
                margin: 15px 0;
            }
            
            .summary-validation {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
                margin-top: 20px;
            }
            
            .health-meter {
                margin: 20px 0;
            }
            
            .health-bar {
                width: 100%;
                height: 25px;
                background: #e9ecef;
                border-radius: 12px;
                overflow: hidden;
                margin: 10px 0;
            }
            
            .health-fill {
                height: 100%;
                background: linear-gradient(90deg, #dc3545, #ffc107, #28a745);
                border-radius: 12px;
                transition: width 0.8s ease;
            }
            
            .recommendations {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                padding: 15px;
                margin-top: 15px;
            }
            
            .recommendations h4 {
                margin: 0 0 10px 0;
                color: #495057;
            }
            
            .recommendations ul {
                margin: 0;
                padding-left: 20px;
            }
            
            @media (max-width: 768px) {
                .validation-grid {
                    grid-template-columns: 1fr;
                }
                .validation-stats {
                    flex-direction: column;
                }
                .stat-box {
                    min-width: auto;
                }
            }        .export-section {
            padding: 20px;
        }
        
        .export-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .export-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .export-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .export-card button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .export-card button:hover {
            background: #1e7e34;
        }
        
        @media (max-width: 768px) {
            .nav-tabs ul { flex-direction: column; }
            .tour-grid { grid-template-columns: 1fr; }
            .dashboard-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚌 TheBus Dashboard</h1>
        <div class="subtitle">Komplette Analyse aller Touren, Fahrzeuge und Routen</div>
    </div>

    <div class="container">
        <div class="nav-tabs">
            <ul>
                <li><a href="#" onclick="showTab('overview')" class="active">📊 Übersicht</a></li>
                <li><a href="#" onclick="showTab('realtime')">🔴 Live</a></li>
                <li><a href="#" onclick="showTab('unassigned')">⚠️ Unzugeordnet</a></li>
                <li><a href="#" onclick="showTab('validation')">🔍 Validierung</a></li>
                <li><a href="#" onclick="showTab('tours')">🚌 Touren</a></li>
                <li><a href="#" onclick="showTab('efficiency')">⚡ Effizienz</a></li>
                <li><a href="#" onclick="showTab('stops')">🚏 Haltestellen</a></li>
                <li><a href="#" onclick="showTab('timetables')">⏰ Zeitpläne</a></li>
                <li><a href="#" onclick="showTab('analytics')">� Analysen</a></li>
                <li><a href="#" onclick="showTab('vehicles')">� Fahrzeuge</a></li>
                <li><a href="#" onclick="showTab('export')">💾 Export</a></li>
            </ul>
        </div>

        <div id="content">
            <!-- Content wird hier von PHP eingefügt -->
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Alle Tabs verstecken
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Alle Nav-Links deaktivieren
            document.querySelectorAll('.nav-tabs a').forEach(link => {
                link.classList.remove('active');
            });
            
            // Gewählten Tab anzeigen
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        function toggleTour(id) {
            const content = document.getElementById('tour-content-' + id);
            const header = document.getElementById('tour-header-' + id);
            const baseColor = header.getAttribute('data-base-color');
            const lightColor = header.getAttribute('data-light-color');
            
            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                header.style.background = `linear-gradient(135deg, ${baseColor}, ${lightColor})`;
            } else {
                content.classList.add('expanded');
                header.style.background = `linear-gradient(135deg, #28a745, #20c997)`;
            }
        }

        function toggleCard(header) {
            const content = header.nextElementSibling;
            if (content && content.classList.contains('tour-content')) {
                content.classList.toggle('expanded');
            }
        }

        function filterTours() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const lineFilter = document.getElementById('lineFilter').value;
            
            document.querySelectorAll('.tour-card').forEach(card => {
                const tourName = card.dataset.tourName.toLowerCase();
                const tourLine = card.dataset.tourLine;
                
                const matchesSearch = tourName.includes(searchTerm);
                const matchesLine = lineFilter === '' || tourLine === lineFilter;
                
                if (matchesSearch && matchesLine) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function applyAdvancedFilter() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const lineFilter = document.getElementById('lineFilter').value;
            const vehicleFilter = document.getElementById('vehicleFilter') ? document.getElementById('vehicleFilter').value : '';
            const minStops = parseInt(document.getElementById('minStops') ? document.getElementById('minStops').value : 0) || 0;
            const maxStops = parseInt(document.getElementById('maxStops') ? document.getElementById('maxStops').value : 999) || 999;
            const minDistance = parseFloat(document.getElementById('minDistance') ? document.getElementById('minDistance').value : 0) || 0;
            const maxDistance = parseFloat(document.getElementById('maxDistance') ? document.getElementById('maxDistance').value : 999) || 999;
            
            let visibleCount = 0;
            
            document.querySelectorAll('.tour-card').forEach(card => {
                const tourName = card.dataset.tourName.toLowerCase();
                const tourLine = card.dataset.tourLine;
                const tourVehicle = card.dataset.tourVehicle || '';
                const tourStops = parseInt(card.dataset.tourStops) || 0;
                const tourDistance = parseFloat(card.dataset.tourDistance) || 0;
                
                const matchesSearch = !searchTerm || tourName.includes(searchTerm);
                const matchesLine = !lineFilter || tourLine === lineFilter;
                const matchesVehicle = !vehicleFilter || tourVehicle.includes(vehicleFilter);
                const matchesStops = tourStops >= minStops && tourStops <= maxStops;
                const matchesDistance = tourDistance >= minDistance && tourDistance <= maxDistance;
                
                if (matchesSearch && matchesLine && matchesVehicle && matchesStops && matchesDistance) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            if (document.getElementById('filterResults')) {
                document.getElementById('filterResults').innerHTML = `🔍 ${visibleCount} Touren gefunden`;
            }
        }

        function clearFilters() {
            document.getElementById('search').value = '';
            document.getElementById('lineFilter').value = '';
            if (document.getElementById('vehicleFilter')) document.getElementById('vehicleFilter').value = '';
            if (document.getElementById('minStops')) document.getElementById('minStops').value = '';
            if (document.getElementById('maxStops')) document.getElementById('maxStops').value = '';
            if (document.getElementById('minDistance')) document.getElementById('minDistance').value = '';
            if (document.getElementById('maxDistance')) document.getElementById('maxDistance').value = '';
            applyAdvancedFilter();
        }

        function exportData(format) {
            alert(`Export als ${format} wird vorbereitet... (Demo)`);
        }
        
        function filterTimetables() {
            const lineFilter = document.getElementById('timetableLineFilter').value;
            
            document.querySelectorAll('.timetable-card').forEach(card => {
                const cardLine = card.dataset.line;
                
                if (lineFilter === '' || cardLine === lineFilter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        function filterStops() {
            const searchTerm = document.getElementById('stopSearch').value.toLowerCase();
            const lineFilter = document.getElementById('stopLineFilter').value;
            const timeFrom = document.getElementById('timeFromFilter').value;
            const timeTo = document.getElementById('timeToFilter').value;
            
            document.querySelectorAll('.stop-card').forEach(card => {
                const stopName = card.dataset.stopName;
                const stopLines = card.dataset.stopLines.split(',');
                const allTimes = card.dataset.allTimes ? card.dataset.allTimes.split(',') : [];
                
                const matchesSearch = !searchTerm || stopName.includes(searchTerm);
                const matchesLine = !lineFilter || stopLines.includes(lineFilter);
                
                let matchesTime = true;
                if (timeFrom || timeTo) {
                    matchesTime = allTimes.some(time => {
                        if (timeFrom && time < timeFrom) return false;
                        if (timeTo && time > timeTo) return false;
                        return true;
                    });
                }
                
                if (matchesSearch && matchesLine && matchesTime) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        function clearStopFilters() {
            document.getElementById('stopSearch').value = '';
            document.getElementById('stopLineFilter').value = '';
            document.getElementById('timeFromFilter').value = '';
            document.getElementById('timeToFilter').value = '';
            filterStops();
        }
        
        function updateRealtime() {
            location.reload(); // Einfache Implementierung - in Produktion würde AJAX verwendet
        }
        
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('de-DE');
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
        
        // Zeit jede Sekunde aktualisieren
        setInterval(updateCurrentTime, 1000);
        
        // Beim Laden sofort Zeit setzen
        document.addEventListener('DOMContentLoaded', updateCurrentTime);

        function toggleCollapsible(id) {
            const content = document.getElementById(id);
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
            } else {
                content.style.display = 'none';
            }
        }
    </script>

    <?php
    class DashboardAnalyzer {
        private $config;
        private $heinsbergPath;
        private $fleetPath;
        private $data = ['lines' => [], 'routes' => [], 'tours' => [], 'timetables' => [], 'vehicles' => []];
        private $tourDetails = [];
        private $stats = [];
        private $timetableData = [];
        private $stopsData = [];
        
        public function __construct() {
            // Lade Konfiguration
            $this->config = require_once 'config.php';
            
            // Setze Pfade aus Config
            $this->heinsbergPath = __DIR__ . DIRECTORY_SEPARATOR . $this->config['paths']['operating_plan'];
            $this->fleetPath = __DIR__ . DIRECTORY_SEPARATOR . $this->config['paths']['vehicle_fleet'];
            
            echo "<!-- DEBUG Constructor Start -->";
            echo "<!-- DEBUG HeinsbergPath: " . $this->heinsbergPath . " -->";
            echo "<!-- DEBUG Exists: " . (is_dir($this->heinsbergPath) ? "YES" : "NO") . " -->";
            
            $this->loadAllData();
            echo "<!-- DEBUG After loadAllData: Tours=" . count($this->data['tours']) . " Vehicles=" . count($this->data['vehicles']) . " -->";
            
            $this->analyzeTourDetails();
            echo "<!-- DEBUG After analyzeTourDetails: " . count($this->tourDetails) . " -->";
            
            $this->analyzeTimetables();
            $this->analyzeStops();
            $this->calculateStats();
            echo "<!-- DEBUG After calculateStats: totalTours=" . $this->stats['totalTours'] . " -->";
            
            $this->generateDashboard();
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
            $this->loadTimetables();
            $this->loadVehicles();
        }
        
        private function loadLines() {
            $linesPath = $this->heinsbergPath . DIRECTORY_SEPARATOR . 'Lines';
            if (!is_dir($linesPath)) return;
            
            $files = glob($linesPath . DIRECTORY_SEPARATOR . '*.line');
            foreach ($files as $file) {
                $data = $this->loadJsonFile($file);
                if ($data) {
                    $lineName = basename($file, '.line');
                    $this->data['lines'][$lineName] = $data;
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
                    $routeName = basename($file, '.lineRoute');
                    $this->data['routes'][$routeName] = $data;
                }
            }
        }
        
        private function loadTours() {
            $toursPath = $this->heinsbergPath . DIRECTORY_SEPARATOR . 'Tours';
            echo "<!-- DEBUG loadTours: path=$toursPath -->";
            if (!is_dir($toursPath)) {
                echo "<!-- DEBUG loadTours: directory not found -->";
                return;
            }
            
            $files = glob($toursPath . DIRECTORY_SEPARATOR . '*.tour');
            echo "<!-- DEBUG loadTours: found " . count($files) . " tour files -->";
            foreach ($files as $file) {
                $data = $this->loadJsonFile($file);
                if ($data) {
                    $tourName = basename($file, '.tour');
                    $this->data['tours'][$tourName] = $data;
                }
            }
            echo "<!-- DEBUG loadTours: loaded " . count($this->data['tours']) . " tours -->";
        }
        
        private function loadTimetables() {
            $timetablesPath = $this->heinsbergPath . DIRECTORY_SEPARATOR . 'Timetables';
            if (!is_dir($timetablesPath)) return;
            
            $files = glob($timetablesPath . DIRECTORY_SEPARATOR . '*.timetable');
            foreach ($files as $file) {
                $data = $this->loadJsonFile($file);
                if ($data) {
                    $timetableName = basename($file, '.timetable');
                    $this->data['timetables'][$timetableName] = $data;
                }
            }
        }
        
        private function loadVehicles() {
            $vehicleFile = $this->fleetPath . DIRECTORY_SEPARATOR . 'config.vehiclefleet';
            $data = $this->loadJsonFile($vehicleFile);
            if ($data && isset($data['vehicles'])) {
                foreach ($data['vehicles'] as $vehicle) {
                    if (isset($vehicle['iD'])) {
                        $this->data['vehicles'][$vehicle['iD']] = $vehicle;
                    }
                }
            }
        }
        
        private function analyzeTourDetails() {
            foreach ($this->data['tours'] as $tourName => $tourData) {
                $details = $this->getTourDetails($tourName, $tourData);
                if ($details) {
                    $this->tourDetails[$tourName] = $details;
                }
            }
        }
        
        private function analyzeTimetables() {
            foreach ($this->data['timetables'] as $timetableName => $timetable) {
                $conditions = [];
                if (isset($timetable['conditions'])) {
                    $conditionsObj = $timetable['conditions'];
                    if ($conditionsObj['monday'] ?? false) $conditions[] = 'Montag';
                    if ($conditionsObj['tuesday'] ?? false) $conditions[] = 'Dienstag'; 
                    if ($conditionsObj['wednesday'] ?? false) $conditions[] = 'Mittwoch';
                    if ($conditionsObj['thursday'] ?? false) $conditions[] = 'Donnerstag';
                    if ($conditionsObj['friday'] ?? false) $conditions[] = 'Freitag';
                    if ($conditionsObj['saturday'] ?? false) $conditions[] = 'Samstag';
                    if ($conditionsObj['sunday'] ?? false) $conditions[] = 'Sonntag';
                    if ($conditionsObj['holiday'] ?? false) $conditions[] = 'Feiertag';
                }
                
                $this->timetableData[$timetableName] = [
                    'line' => $timetable['line'] ?? 'Unbekannt',
                    'conditions' => $conditions,
                    'routes' => $timetable['routes'] ?? [],
                    'totalDepartures' => count($timetable['routes'] ?? [])
                ];
            }
        }
        
        private function analyzeStops() {
            // Sammle alle Haltestellen aus allen Routen
            foreach ($this->data['routes'] as $routeName => $route) {
                if (!isset($route['routeStops'])) continue;
                
                foreach ($route['routeStops'] as $stop) {
                    $stopName = $stop['stopName'] ?? 'Unbekannt';
                    $line = $route['line'] ?? 'Unbekannt';
                    $routeId = $route['routeName'] ?? '?';
                    $travelTime = $stop['travelTimeMinutes'] ?? 0;
                    
                    if (!isset($this->stopsData[$stopName])) {
                        $this->stopsData[$stopName] = [
                            'name' => $stopName,
                            'lines' => [],
                            'routes' => [],
                            'lineFrequency' => [], // Wie oft jede Linie hier hält
                            'hourlyFrequency' => [], // Wie viele Fahrten pro Stunde
                            'totalDailyServices' => 0 // Gesamte Fahrten pro Tag
                        ];
                    }
                    
                    // Füge Linie hinzu wenn noch nicht vorhanden
                    if (!in_array($line, $this->stopsData[$stopName]['lines'])) {
                        $this->stopsData[$stopName]['lines'][] = $line;
                        $this->stopsData[$stopName]['lineFrequency'][$line] = 0;
                    }
                    
                    // Zähle Linie-Frequenz
                    if (!isset($this->stopsData[$stopName]['lineFrequency'][$line])) {
                        $this->stopsData[$stopName]['lineFrequency'][$line] = 0;
                    }
                    
                    // Füge Route-Info hinzu
                    $this->stopsData[$stopName]['routes'][] = [
                        'line' => $line,
                        'route' => $routeId,
                        'travelTime' => $travelTime,
                        'routeName' => $routeName
                    ];
                }
            }
            
            // Berechne Frequenzen basierend auf Zeitplänen
            foreach ($this->stopsData as $stopName => &$stopData) {
                // Initialisiere stündliche Frequenz (0-23 Uhr)
                for ($hour = 0; $hour < 24; $hour++) {
                    $stopData['hourlyFrequency'][$hour] = 0;
                }
                
                // Durchlaufe alle Routen dieser Haltestelle
                foreach ($stopData['routes'] as $routeInfo) {
                    $line = $routeInfo['line'];
                    $travelTime = $routeInfo['travelTime'];
                    
                    // Finde passende Zeitpläne für diese Linie
                    foreach ($this->data['timetables'] as $timetableName => $timetable) {
                        if (($timetable['line'] ?? '') === $line && isset($timetable['routes'])) {
                            foreach ($timetable['routes'] as $timeRoute) {
                                if (($timeRoute['route'] ?? '') === $routeInfo['route']) {
                                    $startHour = $timeRoute['startTime']['hour'] ?? 0;
                                    $startMinute = $timeRoute['startTime']['minute'] ?? 0;
                                    
                                    // Berechne Ankunftszeit an dieser Haltestelle
                                    $arrivalMinutes = ($startHour * 60) + $startMinute + $travelTime;
                                    $arrivalHour = intval($arrivalMinutes / 60) % 24;
                                    
                                    // Zähle für diese Stunde
                                    $stopData['hourlyFrequency'][$arrivalHour]++;
                                    $stopData['totalDailyServices']++;
                                    $stopData['lineFrequency'][$line]++;
                                }
                            }
                        }
                    }
                }
            }
            unset($stopData); // Referenz aufheben
            
            // Sortiere Haltestellen alphabetisch
            ksort($this->stopsData);
        }
        
        private function getTourDetails($tourName, $tourData) {
            $details = [
                'name' => $tourName,
                'line' => $tourData['line'] ?? 'Unbekannt',
                'tourNumber' => $tourData['name'] ?? 'Unbekannt',
                'vehicleId' => $tourData['fleetVehicleId'] ?? null,
                'vehicle' => null,
                'conditions' => $tourData['conditions'] ?? [],
                'routes' => [],
                'totalStops' => 0,
                'totalDistance' => 0,
                'totalTime' => 0,
                'realStartTimes' => []
            ];
            
            if ($details['vehicleId'] && isset($this->data['vehicles'][$details['vehicleId']])) {
                $details['vehicle'] = $this->data['vehicles'][$details['vehicleId']];
            }
            
            if (isset($tourData['slots'])) {
                foreach ($tourData['slots'] as $slot) {
                    $slotLine = $slot['line'] ?? $details['line'];
                    $slotId = $slot['slotId'] ?? null;
                    
                    if ($slotId) {
                        $routeInfo = $this->getRouteInfoForSlot($slotLine, $slotId);
                        if ($routeInfo) {
                            $details['routes'][] = $routeInfo;
                            $details['totalStops'] += $routeInfo['stopCount'];
                            $details['totalDistance'] += $routeInfo['distance'];
                            $details['totalTime'] += $routeInfo['time'];
                            
                            if ($routeInfo['startTime']) {
                                $details['realStartTimes'][] = $routeInfo['startTime'];
                            }
                        }
                    }
                }
            }
            
            return $details;
        }
        
        private function getRouteInfoForSlot($line, $slotId) {
            foreach ($this->data['timetables'] as $timetableName => $timetable) {
                if (($timetable['line'] ?? '') === $line && isset($timetable['routes'])) {
                    foreach ($timetable['routes'] as $route) {
                        if (($route['iD'] ?? null) == $slotId) {
                            $routeName = $route['route'] ?? '01';
                            $routeKey = "$line - $routeName";
                            
                            if (isset($this->data['routes'][$routeKey])) {
                                $routeData = $this->data['routes'][$routeKey];
                                
                                return [
                                    'slotId' => $slotId,
                                    'routeName' => $routeName,
                                    'startTime' => $route['startTime'] ?? null,
                                    'forward' => $routeData['forward'] ?? true,
                                    'endStopText' => $routeData['endStopTextOverwrite'] ?? '',
                                    'stopCount' => count($routeData['routeStops'] ?? []),
                                    'distance' => $this->calculateRouteDistance($routeData),
                                    'time' => $this->calculateRouteTime($routeData),
                                    'stops' => $routeData['routeStops'] ?? []
                                ];
                            }
                        }
                    }
                }
            }
            return null;
        }
        
        private function calculateRouteDistance($routeData) {
            if (!isset($routeData['routeStops']) || empty($routeData['routeStops'])) return 0;
            $lastStop = end($routeData['routeStops']);
            return $lastStop['distanceInMeters'] ?? 0;
        }
        
        private function calculateRouteTime($routeData) {
            if (!isset($routeData['routeStops']) || empty($routeData['routeStops'])) return 0;
            $totalTime = 0;
            foreach ($routeData['routeStops'] as $stop) {
                $totalTime += ($stop['travelTimeMinutes'] ?? 0) + ($stop['waitingTimeMinutes'] ?? 0);
            }
            return $totalTime;
        }
        
        private function calculateStats() {
            $this->stats = [
                'totalTours' => count($this->tourDetails),
                'totalVehicles' => count($this->data['vehicles']),
                'totalLines' => count($this->data['lines']),
                'totalRoutes' => count($this->data['routes']),
                'toursWithVehicles' => 0,
                'totalDistance' => 0,
                'totalTime' => 0,
                'vehicleTypes' => [],
                'lineStats' => []
            ];
            
            foreach ($this->tourDetails as $tour) {
                if ($tour['vehicle']) $this->stats['toursWithVehicles']++;
                $this->stats['totalDistance'] += $tour['totalDistance'];
                $this->stats['totalTime'] += $tour['totalTime'];
                
                if ($tour['vehicle']) {
                    $type = $tour['vehicle']['type'] ?? 'Unbekannt';
                    if (!isset($this->stats['vehicleTypes'][$type])) {
                        $this->stats['vehicleTypes'][$type] = 0;
                    }
                    $this->stats['vehicleTypes'][$type]++;
                }
                
                $line = $tour['line'];
                if (!isset($this->stats['lineStats'][$line])) {
                    $this->stats['lineStats'][$line] = ['tours' => 0, 'distance' => 0, 'time' => 0];
                }
                $this->stats['lineStats'][$line]['tours']++;
                $this->stats['lineStats'][$line]['distance'] += $tour['totalDistance'];
                $this->stats['lineStats'][$line]['time'] += $tour['totalTime'];
            }
        }
        
        private function generateDashboard() {
            echo '<div id="overview" class="tab-content active">';
            $this->generateOverviewTab();
            echo '</div>';
            
            echo '<div id="realtime" class="tab-content">';
            $this->generateRealtimeTab();
            echo '</div>';
            
            echo '<div id="unassigned" class="tab-content">';
            $this->generateUnassignedTab();
            echo '</div>';
            
            echo '<div id="validation" class="tab-content">';
            $this->generateValidationTabFixed();
            echo '</div>';
            
            echo '<div id="tours" class="tab-content">';
            $this->generateToursTab();
            echo '</div>';
            
            echo '<div id="efficiency" class="tab-content">';
            $this->generateEfficiencyTab();
            echo '</div>';
            
            echo '<div id="vehicles" class="tab-content">';
            $this->generateVehiclesTab();
            echo '</div>';
            
            echo '<div id="stops" class="tab-content">';
            $this->generateStopsTab();
            echo '</div>';
            
            echo '<div id="timetables" class="tab-content">';
            $this->generateTimetablesTab();
            echo '</div>';
            
            echo '<div id="analytics" class="tab-content">';
            $this->generateAnalyticsTab();
            echo '</div>';
            
            echo '<div id="export" class="tab-content">';
            $this->generateExportTab();
            echo '</div>';
            
            echo '<div id="lines" class="tab-content">';
            $this->generateLinesTab();
            echo '</div>';
        }
        
        private function generateOverviewTab() {
            echo "<!-- DEBUG: Overview - totalTours: " . $this->stats['totalTours'] . " -->";
            echo "<!-- DEBUG: tourDetails count: " . count($this->tourDetails) . " -->";
            
            echo '<div class="dashboard-grid">';
            echo "<div class='stat-card'><div class='stat-number'>🚌 {$this->stats['totalTours']}</div><div class='stat-label'>Touren</div></div>";
            echo "<div class='stat-card'><div class='stat-number'>🚐 {$this->stats['totalVehicles']}</div><div class='stat-label'>Fahrzeuge</div></div>";
            echo "<div class='stat-card'><div class='stat-number'>🛣️ {$this->stats['totalLines']}</div><div class='stat-label'>Linien</div></div>";
            echo "<div class='stat-card'><div class='stat-number'>📏 " . number_format($this->stats['totalDistance']/1000, 0) . " km</div><div class='stat-label'>Gesamt Distanz</div></div>";
            echo "<div class='stat-card'><div class='stat-number'>⏱️ " . number_format($this->stats['totalTime']/60, 0) . " h</div><div class='stat-label'>Gesamt Fahrzeit</div></div>";
            echo "<div class='stat-card'><div class='stat-number'>✅ {$this->stats['toursWithVehicles']}</div><div class='stat-label'>Zugewiesene Touren</div></div>";
            echo '</div>';
            
            echo '<div class="stats-overview">';
            echo '<h2>📊 Schnellübersicht</h2>';
            echo '<p>Diese Analyse zeigt alle echten Daten aus deinem TheBus System:</p>';
            echo '<ul>';
            echo '<li><strong>Touren:</strong> Alle ' . $this->stats['totalTours'] . ' Touren mit echten Fahrzeug-Zuordnungen</li>';
            echo '<li><strong>Fahrzeuge:</strong> Komplette Fleet mit Kennzeichen und Typen</li>';
            echo '<li><strong>Routen:</strong> Alle Haltestellen mit exakten Distanzen und Zeiten</li>';
            echo '<li><strong>Fahrpläne:</strong> Echte Startzeiten und Verkehrstage</li>';
            echo '</ul>';
            echo '</div>';
        }
        
        private function generateTourAnalytics() {
            // COLLAPSIBLE Analytics-Bereich
            echo '<div style="margin-bottom: 20px;">';
            echo '<div class="collapsible-header" onclick="toggleCollapsible(\'tour-analytics\')" style="cursor: pointer; color: ' . $this->config['ui']['primary_color'] . '; text-decoration: underline; font-size: 18px; font-weight: bold; margin: 15px 0;">📊 Erweiterte Tour-Analysen anzeigen/verstecken</div>';
            echo '<div id="tour-analytics" class="collapsible-content" style="display:none;">';
            
            echo '<div class="tour-analytics-container">';
            
            // 1. EFFIZIENZ-ANALYSE: STANDZEITEN
            echo '<div class="analytics-section">';
            echo '<h3>⏱️ Effizienz-Analyse: Fahrzeug-Standzeiten</h3>';
            echo '<div class="analytics-grid">';
            
            $vehicleStandtimes = $this->calculateVehicleStandtimes();
            
            echo '<div class="analytics-card">';
            echo '<h4>🚐 Fahrzeug-Auslastung</h4>';
            foreach ($vehicleStandtimes as $vehicle => $data) {
                $efficiency = $data['totalWorkTime'] > 0 ? round(($data['activeTime'] / $data['totalWorkTime']) * 100) : 0;
                $color = $efficiency > 80 ? '#28a745' : ($efficiency > 60 ? '#ffc107' : '#dc3545');
                
                echo "<div style='margin: 8px 0; padding: 8px; background: #f8f9fa; border-radius: 5px; border-left: 4px solid $color;'>";
                echo "<strong>$vehicle</strong><br>";
                echo "<small>Auslastung: $efficiency% | Standzeit: " . round($data['standtime']/60, 1) . "h | Touren: {$data['tourCount']}</small>";
                echo "</div>";
            }
            echo '</div>';
            
            // 2. ZEITANALYSE  
            echo '<div class="analytics-card">';
            echo '<h4>🕐 Zeitanalyse</h4>';
            $timeAnalysis = $this->analyzeTimePatterns();
            
            echo "<p><strong>Rush Hour Abdeckung:</strong> {$timeAnalysis['rushHourTours']} Touren ({$timeAnalysis['rushHourPercent']}%)</p>";
            echo "<p><strong>Nachtfahrten (22-06h):</strong> {$timeAnalysis['nightTours']} Touren</p>";
            echo "<p><strong>Parallele Touren:</strong> Max. {$timeAnalysis['maxParallel']} gleichzeitig</p>";
            
            echo '<div style="background: #e8f5e8; padding: 10px; margin: 10px 0; border-radius: 5px;">';
            echo '<strong>Stoßzeiten-Details:</strong><br>';
            foreach ($timeAnalysis['hourlyDistribution'] as $hour => $count) {
                if ($count > 0) {
                    $barWidth = ($count / max($timeAnalysis['hourlyDistribution'])) * 100;
                    echo "<div style='margin: 2px 0;'>";
                    echo "<span style='display: inline-block; width: 60px;'>{$hour}:00</span>";
                    echo "<div style='display: inline-block; width: 200px; background: #ddd; height: 15px; border-radius: 3px; vertical-align: middle;'>";
                    echo "<div style='width: {$barWidth}%; height: 100%; background: #007bff; border-radius: 3px;'></div>";
                    echo "</div>";
                    echo "<span style='margin-left: 10px;'>$count Touren</span>";
                    echo "</div>";
                }
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            // 3. PROBLEMSTRECKEN
            echo '<div class="analytics-section">';
            echo '<h3>⚠️ Problemstrecken-Analyse</h3>';
            echo '<div class="analytics-grid">';
            
            $problemRoutes = $this->identifyProblemRoutes();
            
            echo '<div class="analytics-card">';
            echo '<h4>🚫 Kritische Routen</h4>';
            if (!empty($problemRoutes['unassigned'])) {
                echo '<div style="background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;">';
                echo '<strong>Routen ohne Fahrzeugzuweisung:</strong><br>';
                foreach (array_slice($problemRoutes['unassigned'], 0, 5) as $route) {
                    echo "• Linie {$route['line']}: {$route['route']} ({$route['count']} Touren)<br>";
                }
                if (count($problemRoutes['unassigned']) > 5) {
                    echo "• ... und " . (count($problemRoutes['unassigned']) - 5) . " weitere<br>";
                }
                echo '</div>';
            }
            
            if (!empty($problemRoutes['lowFrequency'])) {
                echo '<div style="background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;">';
                echo '<strong>Selten gefahrene Routen (< 3x täglich):</strong><br>';
                foreach (array_slice($problemRoutes['lowFrequency'], 0, 5) as $route) {
                    echo "• Linie {$route['line']}: {$route['route']} (nur {$route['count']}x)<br>";
                }
                echo '</div>';
            }
            echo '</div>';
            
            // 4. HALTESTELLEN-COVERAGE
            echo '<div class="analytics-card">';
            echo '<h4>🚏 Haltestellen-Coverage</h4>';
            $stopCoverage = $this->analyzeStopCoverage();
            
            echo "<p><strong>Unique Haltestellen:</strong> {$stopCoverage['totalStops']}</p>";
            echo "<p><strong>Meist angefahren:</strong></p>";
            echo '<div style="max-height: 150px; overflow-y: auto;">';
            foreach (array_slice($stopCoverage['topStops'], 0, 10) as $stop => $count) {
                $percentage = round(($count / $stopCoverage['totalVisits']) * 100, 1);
                echo "<div style='margin: 3px 0;'>";
                echo "<strong>$stop:</strong> $count Fahrten ($percentage%)";
                echo "</div>";
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            // 5. BENCHMARK-VERGLEICHE
            echo '<div class="analytics-section">';
            echo '<h3>📈 Benchmark-Vergleiche</h3>';
            echo '<div class="analytics-grid">';
            
            $benchmarks = $this->calculateBenchmarks();
            
            echo '<div class="analytics-card">';
            echo '<h4>🏆 Performance-KPIs</h4>';
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<tr><th style="border: 1px solid #ddd; padding: 8px; background: #f8f9fa;">Linie</th>';
            echo '<th style="border: 1px solid #ddd; padding: 8px; background: #f8f9fa;">Touren</th>';
            echo '<th style="border: 1px solid #ddd; padding: 8px; background: #f8f9fa;">Ø Stops</th>';
            echo '<th style="border: 1px solid #ddd; padding: 8px; background: #f8f9fa;">Fahrzeug-Effizienz</th>';
            echo '<th style="border: 1px solid #ddd; padding: 8px; background: #f8f9fa;">Score</th></tr>';
            
            foreach ($benchmarks['lineComparison'] as $line => $metrics) {
                $scoreColor = $metrics['score'] > 80 ? '#28a745' : ($metrics['score'] > 60 ? '#ffc107' : '#dc3545');
                echo '<tr>';
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>$line</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$metrics['tours']}</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . round($metrics['avgStops'], 1) . "</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$metrics['efficiency']}%</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px; background: $scoreColor; color: white; font-weight: bold;'>{$metrics['score']}</td>";
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
            
            echo '<div class="analytics-card">';
            echo '<h4>📊 Gesamt-Statistiken</h4>';
            echo "<p><strong>Durchschnittliche Tour-Länge:</strong> " . round($benchmarks['avgTourLength'], 1) . " Stops</p>";
            echo "<p><strong>Durchschnittliche Distanz:</strong> " . round($benchmarks['avgDistance'], 1) . " km</p>";
            echo "<p><strong>Gesamt-Effizienz:</strong> {$benchmarks['overallEfficiency']}%</p>";
            echo "<p><strong>Optimierungspotential:</strong> {$benchmarks['optimizationPotential']}</p>";
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            echo '</div>'; // Ende tour-analytics-container
            echo '</div>'; // Ende collapsible content
            echo '</div>'; // Ende collapsible wrapper
        }
        
        private function generateEfficiencyTab() {
            echo '<h2>⚡ Effizienz-Analyse: Fahrzeug-Standzeiten & Optimierung</h2>';
            
            // Berechne erweiterte Standzeiten-Daten
            $efficiencyData = $this->calculateDetailedEfficiency();
            
            echo '<div class="efficiency-overview">';
            echo '<div class="efficiency-stats">';
            
            // Gesamt-Statistiken
            echo '<div class="stat-row">';
            echo "<div class='stat-card efficiency'>";
            echo "<div class='stat-number'>" . round($efficiencyData['overall']['efficiency']) . "%</div>";
            echo "<div class='stat-label'>Durchschnittliche Effizienz</div>";
            echo "</div>";
            
            echo "<div class='stat-card standtime'>";
            echo "<div class='stat-number'>" . round($efficiencyData['overall']['avgStandtime']/60, 1) . "h</div>";
            echo "<div class='stat-label'>Ø Standzeit pro Fahrzeug</div>";
            echo "</div>";
            
            echo "<div class='stat-card problem'>";
            echo "<div class='stat-number'>{$efficiencyData['overall']['problemVehicles']}</div>";
            echo "<div class='stat-label'>Fahrzeuge mit >4h Standzeit</div>";
            echo "</div>";
            
            echo "<div class='stat-card savings'>";
            echo "<div class='stat-number'>" . round($efficiencyData['overall']['potentialSavings']/60, 1) . "h</div>";
            echo "<div class='stat-label'>Einsparpotential täglich</div>";
            echo "</div>";
            echo '</div>';
            echo '</div>';
            
            // Detaillierte Fahrzeug-Analyse
            echo '<div class="vehicle-efficiency-analysis">';
            echo '<h3>🚐 Fahrzeug-Effizienz Analyse</h3>';
            echo '<div class="tour-grid">';
            
            foreach ($efficiencyData['vehicles'] as $vehicle => $data) {
                $efficiency = $data['efficiency'];
                $statusColor = $efficiency > 80 ? '#28a745' : ($efficiency > 60 ? '#ffc107' : '#dc3545');
                $statusText = $efficiency > 80 ? 'Gut' : ($efficiency > 60 ? 'OK' : 'Kritisch');
                
                echo '<div class="tour-card">';
                
                // Header wie bei tour-card
                echo '<div class="tour-header" style="background: linear-gradient(135deg, ' . $statusColor . ', ' . $statusColor . '99);" onclick="toggleCard(this)">';
                echo "<h3>🚌 $vehicle";
                if (!empty($data['vehicleId']) && $data['vehicleId'] !== 'N/A') {
                    echo " (ID: " . $data['vehicleId'] . ")";
                }
                echo "</h3>";
                echo '<div class="quick-info">';
                echo "Effizienz: $statusText ($efficiency%) • ";
                echo "Arbeitszeit: " . round($data['totalWorkTime']/60, 1) . "h • ";
                echo "Touren: " . $data['tourCount'];
                echo '</div>';
                echo '</div>';
                
                // Content - initially hidden like tour-card
                echo '<div class="tour-content">';
                
                // Info sections wie bei tour-card
                echo '<div class="info-section vehicle-info">';
                echo '<strong>🚌 Fahrzeug-Details</strong><br>';
                echo '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px;">';
                echo '<div><strong>Kennzeichen:</strong> ' . htmlspecialchars($vehicle) . '</div>';
                echo '<div><strong>Fahrzeug-ID:</strong> ' . htmlspecialchars($data['vehicleId'] ?? 'N/A') . '</div>';
                echo '<div><strong>Arbeitszeit:</strong> ' . round($data['totalWorkTime']/60, 1) . 'h</div>';
                echo '<div><strong>Aktive Zeit:</strong> ' . round($data['activeTime']/60, 1) . 'h</div>';
                echo '<div><strong>Standzeit:</strong> ' . round($data['standtime']/60, 1) . 'h</div>';
                echo '<div><strong>Effizienz:</strong> <span class="status-badge ' . ($efficiency > 80 ? 'status-active' : 'status-inactive') . '">' . $efficiency . '%</span></div>';
                echo '</div>';
                echo '</div>';
                
                // Route info section für Touren
                echo '<div class="info-section route-info">';
                echo '<strong>🛣️ Tour-Informationen</strong><br>';
                echo '<div style="margin-top: 10px;">';
                echo '<strong>Anzahl Touren:</strong> ' . $data['tourCount'] . '<br>';
                if (!empty($data['tours'])) {
                    echo '<strong>Tour-Namen:</strong> ';
                    $tourNames = array_slice(array_column($data['tours'], 'name'), 0, 3);
                    echo implode(', ', $tourNames);
                    if (count($data['tours']) > 3) echo ' (+' . (count($data['tours']) - 3) . ' weitere)';
                }
                echo '</div>';
                echo '</div>';
                
                // Standzeiten-Details section
                if (!empty($data['standtimeDetails'])) {
                    echo '<div class="info-section schedule-info">';
                    echo '<strong>⏱️ Standzeiten-Details</strong><br>';
                    echo '<div class="collapsible-content" style="margin-top: 10px;">';
                    
                    foreach ($data['standtimeDetails'] as $standtime) {
                        $duration = $standtime['duration'];
                        $durationColor = $duration > 120 ? '#dc3545' : ($duration > 60 ? '#ffc107' : '#28a745');
                        
                        echo '<div style="margin: 5px 0; padding: 8px; border-left: 3px solid ' . $durationColor . '; background: #f8f9fa; border-radius: 4px;">';
                        echo '<strong>' . $standtime['location'] . '</strong><br>';
                        echo '<small>' . $standtime['timeRange'] . ' (' . $duration . ' Min)</small>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
                
                echo '</div>'; // Ende tour-content
                echo '</div>'; // Ende tour-card
            } // Ende foreach vehicles
            echo '</div>'; // Ende tour-grid
            
            // Gesamt-Optimierungsempfehlungen
            echo '<div class="overall-recommendations">';
            echo '<h3>🎯 Gesamt-Optimierungsempfehlungen</h3>';
            echo '<div class="recommendations-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">';
            
            foreach ($efficiencyData['recommendations'] as $category => $recommendations) {
                $categoryIcons = [
                    'immediate' => '🚨',
                    'short_term' => '⏰',
                    'long_term' => '📈'
                ];
                $categoryNames = [
                    'immediate' => 'Sofortige Maßnahmen',
                    'short_term' => 'Kurzfristig (1-4 Wochen)',
                    'long_term' => 'Langfristig (1-3 Monate)'
                ];
                
                echo '<div class="recommendation-category" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';
                echo '<h4>' . $categoryIcons[$category] . ' ' . $categoryNames[$category] . '</h4>';
                echo '<ul>';
                foreach ($recommendations as $rec) {
                    echo "<li>$rec</li>";
                }
                echo '</ul>';
                echo '</div>';
            }
            
            echo '</div>'; // Ende recommendations-grid
            echo '</div>'; // Ende overall-recommendations
            echo '</div>'; // Ende vehicle-efficiency-analysis
            echo '</div>'; // Ende efficiency-overview
        }
        
        private function generateToursTab() {
            echo "<!-- DEBUG: tourDetails count: " . count($this->tourDetails) . " -->";
            echo "<!-- DEBUG: stats lineStats count: " . count($this->stats['lineStats']) . " -->";
            
            // ERWEITERTE TOUR-ANALYSEN
            $this->generateTourAnalytics();
            
            echo '<div class="advanced-filters">';
            echo '<h3>🔍 Erweiterte Filter & Suche</h3>';
            echo '<div class="filter-row">';
            echo '<input type="text" id="search" placeholder="Tour suchen..." onkeyup="applyAdvancedFilter()">';
            echo '<select id="lineFilter" onchange="applyAdvancedFilter()">';
            echo '<option value="">Alle Linien</option>';
            foreach (array_keys($this->stats['lineStats']) as $line) {
                echo "<option value='$line'>Linie $line</option>";
            }
            echo '</select>';
            echo '<select id="vehicleFilter" onchange="applyAdvancedFilter()">';
            echo '<option value="">Alle Fahrzeugtypen</option>';
            foreach (array_keys($this->stats['vehicleTypes']) as $type) {
                echo "<option value='$type'>$type</option>";
            }
            echo '</select>';
            echo '</div>';
            echo '<div class="filter-row">';
            echo '<label>Stops: </label>';
            echo '<input type="number" id="minStops" placeholder="Min" style="width: 80px;" onchange="applyAdvancedFilter()">';
            echo '<input type="number" id="maxStops" placeholder="Max" style="width: 80px;" onchange="applyAdvancedFilter()">';
            echo '<label>Distanz (km): </label>';
            echo '<input type="number" id="minDistance" placeholder="Min" step="0.1" style="width: 80px;" onchange="applyAdvancedFilter()">';
            echo '<input type="number" id="maxDistance" placeholder="Max" step="0.1" style="width: 80px;" onchange="applyAdvancedFilter()">';
            echo '<button onclick="clearFilters()">Filter zurücksetzen</button>';
            echo '</div>';
            echo '</div>';
            
            echo '<div class="tour-grid">';
            $tourCount = 0;
            foreach ($this->tourDetails as $tourName => $tour) {
                $tourCount++;
                $vehicleType = $tour['vehicle']['type'] ?? 'N/A';
                $licensePlate = $tour['vehicle']['licensePlate'] ?? 'N/A';
                
                echo "<div class='tour-card' data-tour-name='$tourName' data-tour-line='{$tour['line']}' data-tour-vehicle='$licensePlate' data-tour-stops='{$tour['totalStops']}' data-tour-distance='{$tour['totalDistance']}' data-vehicle-type='$vehicleType'>";
                
                $baseColor = $this->getLineColor($tour['line']);
                $lightColor = $this->getLightLineColor($tour['line']);
                
                echo "<div class='tour-header' id='tour-header-$tourCount' data-base-color='$baseColor' data-light-color='$lightColor' onclick='toggleTour($tourCount)' style='background: linear-gradient(135deg, $baseColor, $lightColor); color: white; padding: 15px; cursor: pointer;'>";
                echo "<h3><span class='line-badge' style='background: " . $this->getLineColor($tour['line']) . "; color: white;'>{$tour['line']}</span> {$tour['name']}</h3>";
                echo "<div class='quick-info'>";
                if ($tour['vehicle']) {
                    echo "🚐 " . ($tour['vehicle']['licensePlate'] ?? 'N/A') . " | ";
                }
                echo "🚏 {$tour['totalStops']} Stops | ";
                echo "📏 " . number_format($tour['totalDistance']/1000, 1) . " km | ";
                echo "⏱️ {$tour['totalTime']} min";
                echo "</div>";
                echo "</div>";
                
                echo "<div class='tour-content' id='tour-content-$tourCount'>";
                
                if ($tour['vehicle']) {
                    echo "<div class='info-section vehicle-info'>";
                    echo "<strong>🚐 Fahrzeug</strong><br>";
                    echo "Kennzeichen: " . ($tour['vehicle']['licensePlate'] ?? 'N/A') . "<br>";
                    echo "Typ: " . ($tour['vehicle']['type'] ?? 'N/A') . "<br>";
                    echo "ID: {$tour['vehicleId']}";
                    echo "</div>";
                }
                
                if (!empty($tour['realStartTimes'])) {
                    echo "<div class='info-section schedule-info'>";
                    echo "<strong>🕐 Startzeiten</strong><br>";
                    foreach ($tour['realStartTimes'] as $time) {
                        $hour = $time['hour'] ?? 0;
                        $minute = $time['minute'] ?? 0;
                        echo sprintf("%02d:%02d ", $hour, $minute);
                    }
                    echo "</div>";
                }
                
                if (!empty($tour['routes'])) {
                    echo "<div class='info-section route-info'>";
                    echo "<strong>🛣️ Routen (" . count($tour['routes']) . ")</strong><br>";
                    foreach ($tour['routes'] as $route) {
                        echo "Route {$route['routeName']}: {$route['stopCount']} Stops, " . number_format($route['distance']/1000, 1) . " km<br>";
                    }
                    echo "</div>";
                }
                
                if (!empty($tour['routes'][0]['stops'])) {
                    echo "<div class='info-section stops-info'>";
                    echo "<div class='collapsible-header' onclick='toggleCollapsible(\"stops-$tourCount\")'>🚏 Haltestellen anzeigen/verstecken</div>";
                    echo "<div id='stops-$tourCount' class='collapsible-content' style='display:none;'>";
                    foreach ($tour['routes'][0]['stops'] as $index => $stop) {
                        $stopName = $stop['stopName'] ?? 'Unbekannt';
                        $distance = number_format(($stop['distanceInMeters'] ?? 0)/1000, 2);
                        echo ($index + 1) . ". $stopName ($distance km)<br>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
                
                echo "</div>";
                echo "</div>";
            }
            echo '</div>';
        }
        
        private function generateVehiclesTab() {
            echo '<div class="stats-overview">';
            echo '<h2>🚐 Fahrzeug Übersicht</h2>';
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<tr style="background: #f8f9fa;"><th>Kennzeichen</th><th>Typ</th><th>ID</th><th>Kommentar</th></tr>';
            
            foreach ($this->data['vehicles'] as $vehicle) {
                echo '<tr>';
                echo '<td>' . ($vehicle['licensePlate'] ?? 'N/A') . '</td>';
                echo '<td>' . ($vehicle['type'] ?? 'N/A') . '</td>';
                echo '<td>' . ($vehicle['iD'] ?? 'N/A') . '</td>';
                echo '<td>' . ($vehicle['comment'] ?? 'Kein Kommentar') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
        }
        
        private function generateLinesTab() {
            echo '<div class="stats-overview">';
            echo '<h2>🛣️ Linien Statistik</h2>';
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<tr style="background: #f8f9fa;"><th>Linie</th><th>Touren</th><th>Distanz</th><th>Fahrzeit</th></tr>';
            
            foreach ($this->stats['lineStats'] as $line => $stats) {
                echo '<tr>';
                echo "<td><span class='line-badge' style='background: " . $this->getLineColor($line) . "; color: white;'>$line</span></td>";
                echo "<td>{$stats['tours']}</td>";
                echo "<td>" . number_format($stats['distance']/1000, 1) . " km</td>";
                echo "<td>" . number_format($stats['time']/60, 1) . " h</td>";
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
        }
        
        private function generateAnalyticsTab() {
            echo '<div class="dashboard-grid">';
            
            echo "<div class='stat-card'>";
            echo "<h3>🚍 Fahrzeugtypen</h3>";
            foreach ($this->stats['vehicleTypes'] as $type => $count) {
                echo "<div>$type: $count</div>";
            }
            echo "</div>";
            
            echo "<div class='stat-card'>";
            echo "<h3>📈 Top Linien (nach Touren)</h3>";
            arsort($this->stats['lineStats']);
            $top5 = array_slice($this->stats['lineStats'], 0, 5, true);
            foreach ($top5 as $line => $stats) {
                echo "<div>Linie $line: {$stats['tours']} Touren</div>";
            }
            echo "</div>";
            
            echo '</div>';
        }
        
        private function generateTimetablesTab() {
            echo '<div class="advanced-filters">';
            echo '<h2>📅 Fahrplan-Analyse</h2>';
            echo '<div class="filter-row">';
            echo '<select id="timetableLineFilter">';
            echo '<option value="">Alle Linien</option>';
            foreach ($this->stats['lineStats'] as $line => $stats) {
                echo "<option value='$line'>Linie $line</option>";
            }
            echo '</select>';
            echo '<button onclick="filterTimetables()">Filter anwenden</button>';
            echo '</div>';
            echo '</div>';
            
            echo '<div class="timetable-grid">';
            foreach ($this->timetableData as $timetableName => $data) {
                echo "<div class='timetable-card' data-line='{$data['line']}'>";
                echo "<h3>🕐 $timetableName</h3>";
                echo "<p><strong>Linie:</strong> {$data['line']}</p>";
                echo "<p><strong>Abfahrten:</strong> {$data['totalDepartures']}</p>";
                echo "<div class='conditions'>";
                if (!empty($data['conditions'])) {
                    echo "<strong>Gültigkeitstage:</strong><br>";
                    foreach ($data['conditions'] as $condition) {
                        echo "✅ $condition<br>";
                    }
                } else {
                    echo "<strong>Gültigkeitstage:</strong><br>";
                    echo "⚠️ Keine Bedingungen definiert<br>";
                }
                echo "</div>";
                
                // Zeige alle Routen an
                if (!empty($data['routes']) && count($data['routes']) > 0) {
                    echo "<div class='route-preview'>";
                    echo "<strong>Alle Abfahrtszeiten:</strong><br>";
                    
                    // Sortiere Routen nach Startzeit
                    $sortedRoutes = $data['routes'];
                    usort($sortedRoutes, function($a, $b) {
                        $timeA = ($a['startTime']['hour'] ?? 0) * 60 + ($a['startTime']['minute'] ?? 0);
                        $timeB = ($b['startTime']['hour'] ?? 0) * 60 + ($b['startTime']['minute'] ?? 0);
                        return $timeA - $timeB;
                    });
                    
                    echo "<div class='timetable-times'>";
                    foreach ($sortedRoutes as $route) {
                        // Verwende startTime Objekt, da startTimeMinutes oft 0 ist
                        $hour = $route['startTime']['hour'] ?? 0;
                        $minute = $route['startTime']['minute'] ?? 0;
                        $routeId = $route['route'] ?? '?';
                        $timeString = sprintf("%02d:%02d", $hour, $minute);
                        echo "<span class='time-badge'>Route $routeId: $timeString</span>";
                    }
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<div class='no-service'>";
                    echo "❌ <strong>Kein Busverkehr</strong><br>";
                    echo "An diesen Tagen fährt die Linie {$data['line']} nicht.";
                    echo "</div>";
                }
                
                echo "</div>";
            }
            echo '</div>';
        }
        
        private function generateExportTab() {
            echo '<div class="export-section">';
            echo '<h2>📤 Daten Export</h2>';
            echo '<div class="export-options">';
            echo '<div class="export-card">';
            echo '<h3>JSON Export</h3>';
            echo '<p>Exportiere alle Analysedaten als JSON</p>';
            echo '<button onclick="exportData(\'json\')">JSON Download</button>';
            echo '</div>';
            echo '<div class="export-card">';
            echo '<h3>CSV Export</h3>';
            echo '<p>Exportiere Touren-Daten als CSV</p>';
            echo '<button onclick="exportData(\'csv\')">CSV Download</button>';
            echo '</div>';
            echo '<div class="export-card">';
            echo '<h3>Statistik Report</h3>';
            echo '<p>Detaillierter Analysebericht</p>';
            echo '<button onclick="exportData(\'report\')">Report Download</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        
        private function generateStopsTab() {
            echo '<div class="advanced-filters">';
            echo '<h2>🚏 Haltestellen-Übersicht</h2>';
            echo '<div class="filter-row">';
            echo '<input type="text" id="stopSearch" placeholder="Haltestelle suchen..." onkeyup="filterStops()">';
            echo '<select id="stopLineFilter" onchange="filterStops()">';
            echo '<option value="">Alle Linien</option>';
            foreach ($this->stats['lineStats'] as $line => $stats) {
                echo "<option value='$line'>Linie $line</option>";
            }
            echo '</select>';
            echo '<input type="time" id="timeFromFilter" onchange="filterStops()" title="Abfahrt ab">';
            echo '<input type="time" id="timeToFilter" onchange="filterStops()" title="Abfahrt bis">';
            echo '<button onclick="clearStopFilters()">Filter zurücksetzen</button>';
            echo '</div>';
            echo '<p>📊 Total: ' . count($this->stopsData) . ' Haltestellen | 🕐 Verwende Zeit-Filter um Abfahrten zu filtern (z.B. 08:00 - 18:00)</p>';
            echo '</div>';
            
            echo '<div class="stops-grid">';
            foreach ($this->stopsData as $stopName => $stopData) {
                $linesCount = count($stopData['lines']);
                $routesCount = count($stopData['routes']);
                $totalServices = $stopData['totalDailyServices'] ?? 0;
                
                // Sammle alle Abfahrtszeiten für diese Haltestelle (URSPRÜNGLICHE FUNKTION)
                $departures = [];
                $allTimes = [];
                foreach ($stopData['routes'] as $routeInfo) {
                    $travelTime = $routeInfo['travelTime'];
                    
                    // Hole Timetable-Daten für diese Route
                    foreach ($this->timetableData as $timetableName => $timetable) {
                        if ($timetable['line'] === $routeInfo['line']) {
                            foreach ($timetable['routes'] as $timeRoute) {
                                if ($timeRoute['route'] === $routeInfo['route']) {
                                    $startHour = $timeRoute['startTime']['hour'] ?? 0;
                                    $startMinute = $timeRoute['startTime']['minute'] ?? 0;
                                    
                                    // Berechne Ankunftszeit an dieser Haltestelle
                                    $arrivalMinutes = ($startHour * 60) + $startMinute + $travelTime;
                                    $arrivalHour = intval($arrivalMinutes / 60) % 24;
                                    $arrivalMin = $arrivalMinutes % 60;
                                    
                                    $timeString = sprintf("%02d:%02d", $arrivalHour, $arrivalMin);
                                    $allTimes[] = $timeString;
                                    
                                    $departures[] = [
                                        'time' => $timeString,
                                        'line' => $routeInfo['line'],
                                        'route' => $routeInfo['route'],
                                        'sort' => $arrivalMinutes,
                                        'destination' => $routeInfo['routeName'] ?? $timeRoute['destination'] ?? 'Unbekanntes Ziel',
                                        'routeName' => $routeInfo['routeName'] ?? ''
                                    ];
                                }
                            }
                        }
                    }
                }
                
                // Sortiere nach Zeit
                usort($departures, function($a, $b) {
                    return $a['sort'] - $b['sort'];
                });
                
                $earliestTime = !empty($allTimes) ? min($allTimes) : '';
                $latestTime = !empty($allTimes) ? max($allTimes) : '';
                
                // Finde Rush Hours (Stunden mit den meisten Fahrten) - NEUE FUNKTION
                $hourlyFreq = $stopData['hourlyFrequency'] ?? [];
                arsort($hourlyFreq);
                $topHours = array_slice($hourlyFreq, 0, 3, true);
                
                // Finde häufigste Linien - NEUE FUNKTION
                $lineFreq = $stopData['lineFrequency'] ?? [];
                arsort($lineFreq);
                $topLines = array_slice($lineFreq, 0, 3, true);
                
                echo "<div class='stop-card' data-stop-name='" . strtolower($stopName) . "' data-stop-lines='" . implode(',', $stopData['lines']) . "' data-earliest-time='$earliestTime' data-latest-time='$latestTime' data-all-times='" . implode(',', $allTimes) . "'>";
                echo "<h3>🚏 $stopName</h3>";
                
                // URSPRÜNGLICHE GRUNDINFO
                echo "<p><strong>Linien:</strong> " . implode(', ', $stopData['lines']) . " ($linesCount)</p>";
                echo "<p><strong>Routen:</strong> $routesCount</p>";
                
                if (!empty($allTimes)) {
                    echo "<p><strong>Erste Fahrt:</strong> $earliestTime | <strong>Letzte Fahrt:</strong> $latestTime</p>";
                }
                
                // NEUE FREQUENZ-ANALYSE (ERWEITERT)
                if (!empty($topLines)) {
                    echo "<div style='background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
                    echo "<h4>📊 Linien-Frequenz (Tägliche Fahrten: $totalServices)</h4>";
                    foreach ($topLines as $line => $count) {
                        $percentage = $totalServices > 0 ? round(($count / $totalServices) * 100) : 0;
                        echo "<div style='display: flex; justify-content: space-between; margin: 5px 0;'>";
                        echo "<span><strong>Linie $line:</strong></span>";
                        echo "<span>$count Fahrten ($percentage%)</span>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
                
                // RUSH HOURS (NEUE FUNKTION)
                if (!empty($topHours)) {
                    echo "<div style='background: #fff3e0; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
                    echo "<h4>🕐 Rush Hours</h4>";
                    foreach ($topHours as $hour => $count) {
                        if ($count > 0) {
                            $timeRange = sprintf("%02d:00-%02d:59", $hour, $hour);
                            echo "<div style='display: flex; justify-content: space-between; margin: 5px 0;'>";
                            echo "<span><strong>$timeRange:</strong></span>";
                            echo "<span>$count Fahrten</span>";
                            echo "</div>";
                        }
                    }
                    echo "</div>";
                }
                
                // ERWEITERTE ENTWICKLER-INFOS
                echo "<div class='collapsible-header' onclick='toggleCollapsible(\"dev-info-$stopName\")' style='cursor: pointer; color: " . $this->config['ui']['primary_color'] . "; text-decoration: underline; margin: 15px 0 10px 0;'>" . $this->config['labels']['messages']['developer_info_toggle'] . "</div>";
                echo "<div id='dev-info-$stopName' class='collapsible-content' style='display:none;'>";
                
                // Route-Details
                echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
                echo "<h4>🛣️ Route-Details</h4>";
                $routeDetails = [];
                foreach ($stopData['routes'] as $routeInfo) {
                    $key = $routeInfo['line'] . ' - Route ' . $routeInfo['route'];
                    if (!isset($routeDetails[$key])) {
                        $routeDetails[$key] = [
                            'count' => 0,
                            'travelTimes' => [],
                            'routeName' => $routeInfo['routeName']
                        ];
                    }
                    $routeDetails[$key]['count']++;
                    $routeDetails[$key]['travelTimes'][] = $routeInfo['travelTime'];
                }
                
                foreach ($routeDetails as $routeKey => $details) {
                    $avgTravelTime = array_sum($details['travelTimes']) / count($details['travelTimes']);
                    $minTravelTime = min($details['travelTimes']);
                    $maxTravelTime = max($details['travelTimes']);
                    
                    echo "<div style='margin: 8px 0; padding: 8px; background: white; border-radius: 5px;'>";
                    echo "<strong>$routeKey</strong> ({$details['routeName']})<br>";
                    echo "<small>Fahrzeit: ø " . round($avgTravelTime) . " Min (Min: $minTravelTime, Max: $maxTravelTime) | Instanzen: {$details['count']}</small>";
                    echo "</div>";
                }
                echo "</div>";
                
                // Zeitverteilung (24h)
                echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
                echo "<h4>🕐 24h Zeitverteilung</h4>";
                $timeSlots = $this->config['time_settings']['time_slots'];
                $slotCounts = [0, 0, 0, 0, 0, 0, 0];
                
                foreach ($departures as $dep) {
                    $hour = intval(substr($dep['time'], 0, 2));
                    if ($hour >= 0 && $hour <= 5) $slotCounts[0]++;
                    elseif ($hour >= 6 && $hour <= 9) $slotCounts[1]++;
                    elseif ($hour >= 10 && $hour <= 11) $slotCounts[2]++;
                    elseif ($hour >= 12 && $hour <= 13) $slotCounts[3]++;
                    elseif ($hour >= 14 && $hour <= 17) $slotCounts[4]++;
                    elseif ($hour >= 18 && $hour <= 21) $slotCounts[5]++;
                    elseif ($hour >= 22 && $hour <= 23) $slotCounts[6]++;
                }
                
                for ($i = 0; $i < count($timeSlots); $i++) {
                    $percentage = count($departures) > 0 ? round(($slotCounts[$i] / count($departures)) * 100) : 0;
                    $barWidth = $percentage;
                    echo "<div style='margin: 5px 0;'>";
                    echo "<div style='display: flex; justify-content: space-between;'>";
                    echo "<span>{$timeSlots[$i]}:</span>";
                    echo "<span>{$slotCounts[$i]} Fahrten ($percentage%)</span>";
                    echo "</div>";
                    echo "<div style='background: #ddd; height: 10px; border-radius: 5px; margin: 2px 0;'>";
                    echo "<div style='background: #28a745; height: 100%; width: {$barWidth}%; border-radius: 5px;'></div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
                
                // Lücken-Analyse
                echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
                echo "<h4>⏱️ Taktung & Lücken-Analyse</h4>";
                if (count($departures) > 1) {
                    $intervals = [];
                    for ($i = 1; $i < count($departures); $i++) {
                        $interval = $departures[$i]['sort'] - $departures[$i-1]['sort'];
                        $intervals[] = $interval;
                    }
                    
                    $avgInterval = array_sum($intervals) / count($intervals);
                    $minInterval = min($intervals);
                    $maxInterval = max($intervals);
                    
                    // Finde große Lücken (aus Config)
                    $gapThreshold = $this->config['time_settings']['gap_threshold'];
                    $bigGaps = [];
                    for ($i = 0; $i < count($intervals); $i++) {
                        if ($intervals[$i] > $gapThreshold) {
                            $gapStart = $departures[$i]['time'];
                            $gapEnd = $departures[$i+1]['time'];
                            $bigGaps[] = "$gapStart - $gapEnd (" . round($intervals[$i]) . " Min)";
                        }
                    }
                    
                    echo "<p><strong>Durchschnittlicher Takt:</strong> " . round($avgInterval) . " Minuten</p>";
                    echo "<p><strong>Kürzester Takt:</strong> $minInterval Min | <strong>Längster:</strong> " . round($maxInterval) . " Min</p>";
                    
                    if (!empty($bigGaps)) {
                        $gapMsg = sprintf($this->config['labels']['messages']['large_gaps_found'], $gapThreshold);
                        echo "<p><strong>$gapMsg</strong></p>";
                        echo "<ul>";
                        $maxGaps = $this->config['optimization']['max_display_gaps'];
                        foreach (array_slice($bigGaps, 0, $maxGaps) as $gap) {
                            echo "<li>$gap</li>";
                        }
                        if (count($bigGaps) > $maxGaps) {
                            echo "<li><em>... und " . (count($bigGaps) - $maxGaps) . " weitere</em></li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>" . $this->config['labels']['messages']['no_gaps_found'] . "</p>";
                    }
                } else {
                    echo "<p>Nicht genügend Daten für Taktung-Analyse</p>";
                }
                echo "</div>";
                
                // Optimierungsvorschläge
                echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
                echo "<h4>💡 Optimierungsvorschläge</h4>";
                $suggestions = [];
                
                if ($totalServices > 100) {
                    $suggestions[] = "Sehr hohe Frequenz - prüfe ob alle Fahrten nötig sind";
                } elseif ($totalServices < 10) {
                    $suggestions[] = "Niedrige Frequenz - könnte mehr Service vertragen";
                }
                
                if (!empty($bigGaps)) {
                    $suggestions[] = "Große Taktlücken gefunden - zusätzliche Fahrten einplanen";
                }
                
                if (count($topLines) == 1) {
                    $suggestions[] = "Nur eine dominante Linie - andere Linien verstärken oder neue Route prüfen";
                }
                
                // Prüfe Rush Hour Verteilung
                $rushHourService = $slotCounts[1] + $slotCounts[4]; // Früh + Nachmittag
                $totalDayService = array_sum($slotCounts);
                if ($totalDayService > 0 && ($rushHourService / $totalDayService) < 0.4) {
                    $suggestions[] = "Wenig Rush-Hour Service - Stoßzeiten verstärken";
                }
                
                if (empty($suggestions)) {
                    $suggestions[] = "Keine offensichtlichen Optimierungen nötig";
                }
                
                echo "<ul>";
                foreach ($suggestions as $suggestion) {
                    echo "<li>$suggestion</li>";
                }
                echo "</ul>";
                echo "</div>";
                
                // Raw Data für Entwickler
                echo "<div style='background: #f1f3f4; padding: 15px; border-radius: 8px; margin: 10px 0; font-family: monospace; font-size: 12px;'>";
                echo "<h4 style='font-family: inherit; font-size: 14px;'>📊 Raw Data (JSON)</h4>";
                echo "<details>";
                echo "<summary style='cursor: pointer;'>Klicken für JSON-Daten</summary>";
                echo "<pre style='white-space: pre-wrap; max-height: 200px; overflow-y: auto;'>";
                $rawData = [
                    'stopName' => $stopName,
                    'totalServices' => $totalServices,
                    'lines' => $stopData['lines'],
                    'lineFrequency' => $lineFreq,
                    'hourlyFrequency' => $hourlyFreq,
                    'routeDetails' => $routeDetails,
                    'departureCount' => count($departures),
                    'timeSlotDistribution' => array_combine($timeSlots, $slotCounts)
                ];
                echo htmlspecialchars(json_encode($rawData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                echo "</pre>";
                echo "</details>";
                echo "</div>";
                
                echo "</div>"; // Ende dev-info
                
                // URSPRÜNGLICHE ABFAHRTSZEITEN (ALLE anzeigen ohne Begrenzung)
                echo "<div class='stop-times'>";
                echo "<h3>🚏 Alle Abfahrtszeiten</h3>";
                echo "<div style='max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: white; border-radius: 5px;'>";
                
                // ALLE Abfahrten anzeigen ohne Begrenzung
                foreach ($departures as $dep) {
                    $timeClass = '';
                    $hour = intval(substr($dep['time'], 0, 2));
                // Zeigt Rush Hour Status an (aus Config)
                $rushMorning = $this->config['time_settings']['rush_hours']['morning'];
                $rushEvening = $this->config['time_settings']['rush_hours']['evening'];
                
                if (($hour >= $rushMorning['start'] && $hour <= $rushMorning['end']) || 
                    ($hour >= $rushEvening['start'] && $hour <= $rushEvening['end'])) {
                    $timeClass = 'rush-hour-time';
                }                    echo "<div class='departure-item $timeClass' style='margin: 5px 0; padding: 8px; border-radius: 5px; background: " . 
                         ($timeClass ? '#fff3cd' : '#f8f9fa') . "; border-left: " . 
                         ($timeClass ? '4px solid ' . $this->config['ui']['rush_hour_color'] : '4px solid transparent') . ";'>";
                    echo "<strong>{$dep['time']}</strong> - ";
                    echo "<span class='line-badge' style='background: " . $this->getLineColor($dep['line']) . "; color: white; padding: 2px 6px; border-radius: 3px; margin-right: 5px;'>{$dep['line']}</span>";
                    echo " {$dep['destination']}";
                    if (!empty($dep['routeName'])) {
                        echo " <small style='color: #666;'>({$dep['routeName']})</small>";
                    }
                    echo "</div>";
                }
                
                echo "</div>";
                echo "<p style='text-align: center; color: #666; margin: 10px 0;'>";
                echo "<strong>Gesamt: " . count($departures) . " Abfahrten angezeigt</strong>";
                echo "</p>";
                echo "</div>";
                
                // VERKEHRSDICHTE-INDIKATOR (aus Config)
                if ($totalServices > 0) {
                    $density = $this->config['labels']['traffic_density']['low'];
                    $color = $this->config['ui']['success_color'];
                    
                    if ($totalServices > $this->config['traffic_density']['high']) {
                        $density = $this->config['labels']['traffic_density']['high'];
                        $color = $this->config['ui']['danger_color'];
                    } elseif ($totalServices > $this->config['traffic_density']['medium']) {
                        $density = $this->config['labels']['traffic_density']['medium'];
                        $color = $this->config['ui']['warning_color'];
                    }
                    
                    echo "<div style='background: $color; color: white; padding: 10px; border-radius: 8px; text-align: center; margin: 10px 0;'>";
                    echo "<strong>Verkehrsdichte: $density</strong>";
                    echo "</div>";
                }
                
                echo "</div>";
            }
            echo '</div>';
            
            // CSS und JavaScript für erweiterte Haltestellen-Funktionalität
            echo "<style>
            .collapsible-content {
                transition: all 0.3s ease-out;
            }
            .rush-hour-time {
                border-left: 4px solid " . $this->config['ui']['rush_hour_color'] . " !important;
            }
            .departure-item {
                transition: background-color 0.2s;
            }
            .departure-item:hover {
                background-color: #e9ecef !important;
            }
            .collapsible-header:hover {
                text-decoration: underline !important;
            }
            </style>";
            
            echo "<script>
            function toggleCollapsible(elementId) {
                const element = document.getElementById(elementId);
                if (element.style.display === 'none' || element.style.display === '') {
                    element.style.display = 'block';
                } else {
                    element.style.display = 'none';
                }
            }
            </script>";
        }
        
        private function generateRealtimeTab() {
            echo '<div class="realtime-section">';
            echo '<h2>🔴 Live Bus Tracking</h2>';
            echo '<div class="realtime-controls">';
            echo '<button onclick="updateRealtime()" class="refresh-btn">🔄 Aktualisieren</button>';
            echo '<div class="current-time">🕐 Aktuelle Zeit: <span id="currentTime"></span></div>';
            echo '</div>';
            
            echo '<div class="realtime-grid">';
            
            // Aktuelle Zeit simulieren (normalerweise würde das vom Client kommen)
            $currentHour = date('H');
            $currentMinute = date('i');
            $currentTimeMinutes = ($currentHour * 60) + $currentMinute;
            
            echo '<div class="realtime-card">';
            echo '<h3>🚌 Busse unterwegs (JETZT)</h3>';
            echo '<div class="active-buses" id="activeBuses">';
            
            $activeBuses = 0;
            foreach ($this->timetableData as $timetableName => $timetable) {
                foreach ($timetable['routes'] as $route) {
                    $startHour = $route['startTime']['hour'] ?? 0;
                    $startMinute = $route['startTime']['minute'] ?? 0;
                    $startTimeMinutes = ($startHour * 60) + $startMinute;
                    
                    // Simuliere Fahrtdauer (z.B. 60 Minuten)
                    $endTimeMinutes = $startTimeMinutes + 60;
                    
                    if ($currentTimeMinutes >= $startTimeMinutes && $currentTimeMinutes <= $endTimeMinutes) {
                        $activeBuses++;
                        $routeId = $route['route'] ?? '?';
                        $progress = round((($currentTimeMinutes - $startTimeMinutes) / 60) * 100);
                        
                        echo "<div class='active-bus'>";
                        echo "🚌 Linie {$timetable['line']} Route $routeId";
                        echo "<div class='bus-progress'>";
                        echo "<div class='progress-bar' style='width: {$progress}%'></div>";
                        echo "</div>";
                        echo "<small>Fahrt: " . sprintf("%02d:%02d", $startHour, $startMinute) . " | Fortschritt: {$progress}%</small>";
                        echo "</div>";
                    }
                }
            }
            
            if ($activeBuses == 0) {
                echo "<p>🚏 Momentan keine aktiven Fahrten</p>";
            }
            
            echo '</div>';
            echo '</div>';
            
            echo '<div class="realtime-card">';
            echo '<h3>⏰ Nächste Abfahrten (15 Min)</h3>';
            echo '<div class="upcoming-buses" id="upcomingBuses">';
            
            $upcomingBuses = [];
            foreach ($this->timetableData as $timetableName => $timetable) {
                foreach ($timetable['routes'] as $route) {
                    $startHour = $route['startTime']['hour'] ?? 0;
                    $startMinute = $route['startTime']['minute'] ?? 0;
                    $startTimeMinutes = ($startHour * 60) + $startMinute;
                    
                    $minutesUntil = $startTimeMinutes - $currentTimeMinutes;
                    if ($minutesUntil < 0) $minutesUntil += 24 * 60; // Nächster Tag
                    
                    if ($minutesUntil <= 15 && $minutesUntil > 0) {
                        $upcomingBuses[] = [
                            'line' => $timetable['line'],
                            'route' => $route['route'] ?? '?',
                            'time' => sprintf("%02d:%02d", $startHour, $startMinute),
                            'minutes' => $minutesUntil
                        ];
                    }
                }
            }
            
            // Sortiere nach Zeit
            usort($upcomingBuses, function($a, $b) {
                return $a['minutes'] - $b['minutes'];
            });
            
            if (empty($upcomingBuses)) {
                echo "<p>📭 Keine Abfahrten in den nächsten 15 Minuten</p>";
            } else {
                foreach (array_slice($upcomingBuses, 0, 10) as $bus) {
                    $urgency = $bus['minutes'] <= 3 ? 'urgent' : ($bus['minutes'] <= 7 ? 'soon' : 'normal');
                    echo "<div class='upcoming-bus $urgency'>";
                    echo "🚌 Linie {$bus['line']} Route {$bus['route']}";
                    echo "<div class='departure-info'>";
                    echo "<strong>{$bus['time']}</strong> | in {$bus['minutes']} Min";
                    echo "</div>";
                    echo "</div>";
                }
            }
            
            echo '</div>';
            echo '</div>';
            
            echo '</div>';
            echo '</div>';
        }
        
        private function generateUnassignedTab() {
            echo '<div class="unassigned-section">';
            echo '<h2>⚠️ Zuordnungs-Analyse</h2>';
            echo '<p>Hier siehst du alle Busse ohne Touren und Touren ohne Busse - wichtig für optimale Ressourcennutzung!</p>';
            
            // Sammle alle verwendeten Fahrzeug-IDs aus Touren
            $assignedVehicleIds = [];
            foreach ($this->data['tours'] as $tourName => $tour) {
                if (isset($tour['fleetVehicleId']) && !empty($tour['fleetVehicleId'])) {
                    $assignedVehicleIds[] = $tour['fleetVehicleId'];
                }
            }
            
            // Sammle alle Touren ohne Fahrzeuge
            $toursWithoutVehicles = [];
            foreach ($this->data['tours'] as $tourName => $tour) {
                if (!isset($tour['fleetVehicleId']) || empty($tour['fleetVehicleId'])) {
                    $toursWithoutVehicles[] = [
                        'name' => $tourName,
                        'line' => $tour['line'] ?? 'Unbekannt'
                    ];
                }
            }
            
            // Sammle alle Fahrzeuge ohne Touren
            $vehiclesWithoutTours = [];
            foreach ($this->data['vehicles'] as $vehicleId => $vehicle) {
                if (!in_array($vehicleId, $assignedVehicleIds)) {
                    $vehiclesWithoutTours[] = [
                        'id' => $vehicleId,
                        'licensePlate' => $vehicle['licensePlate'] ?? 'N/A',
                        'type' => $vehicle['type'] ?? 'Unbekannt',
                        'capacity' => $vehicle['passengerCapacity'] ?? 0
                    ];
                }
            }
            
            echo '<div class="unassigned-grid">';
            
            // Fahrzeuge ohne Touren
            echo '<div class="unassigned-card vehicles-card">';
            echo '<h3>🚐 Fahrzeuge ohne Touren (' . count($vehiclesWithoutTours) . ')</h3>';
            
            if (empty($vehiclesWithoutTours)) {
                echo '<div class="success-message">✅ Alle Fahrzeuge sind Touren zugeordnet!</div>';
            } else {
                echo '<div class="warning-message">⚠️ Diese Fahrzeuge werden nicht genutzt:</div>';
                echo '<div class="unassigned-list">';
                foreach ($vehiclesWithoutTours as $vehicle) {
                    echo '<div class="unassigned-item vehicle-item">';
                    echo '<div class="item-header">';
                    echo '<strong>🚐 ID: ' . $vehicle['id'] . '</strong>';
                    echo '<span class="item-type">' . $vehicle['type'] . '</span>';
                    echo '</div>';
                    echo '<div class="item-details">';
                    echo '<span>📋 Kennzeichen: ' . $vehicle['licensePlate'] . '</span>';
                    echo '<span>👥 Kapazität: ' . $vehicle['capacity'] . ' Personen</span>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                
                echo '<div class="suggestion-box">';
                echo '<h4>💡 Empfehlungen:</h4>';
                echo '<ul>';
                echo '<li>Erstelle neue Touren für diese Fahrzeuge</li>';
                echo '<li>Verwende sie als Reserve-Fahrzeuge</li>';
                echo '<li>Prüfe ob andere Touren mehr Kapazität benötigen</li>';
                echo '</ul>';
                echo '</div>';
            }
            echo '</div>';
            
            // Touren ohne Fahrzeuge
            echo '<div class="unassigned-card tours-card">';
            echo '<h3>🚌 Touren ohne Fahrzeuge (' . count($toursWithoutVehicles) . ')</h3>';
            
            if (empty($toursWithoutVehicles)) {
                echo '<div class="success-message">✅ Alle Touren haben Fahrzeuge zugeordnet!</div>';
            } else {
                echo '<div class="warning-message">⚠️ Diese Touren können nicht fahren:</div>';
                echo '<div class="unassigned-list">';
                foreach ($toursWithoutVehicles as $tour) {
                    echo '<div class="unassigned-item tour-item">';
                    echo '<div class="item-header">';
                    echo '<strong>🚌 ' . $tour['name'] . '</strong>';
                    echo '<span class="item-line">Linie ' . $tour['line'] . '</span>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                
                echo '<div class="suggestion-box">';
                echo '<h4>💡 Empfehlungen:</h4>';
                echo '<ul>';
                echo '<li>Weise verfügbare Fahrzeuge zu</li>';
                echo '<li>Kaufe zusätzliche Fahrzeuge</li>';
                echo '<li>Prüfe ob andere Touren weniger Fahrzeuge benötigen</li>';
                echo '</ul>';
                echo '</div>';
            }
            echo '</div>';
            
            echo '</div>';
            
            // Zusammenfassung
            echo '<div class="summary-card">';
            echo '<h3>📊 Zusammenfassung</h3>';
            echo '<div class="summary-stats">';
            echo '<div class="stat-item">';
            echo '<span class="stat-number">' . count($vehiclesWithoutTours) . '</span>';
            echo '<span class="stat-label">Ungenutzte Fahrzeuge</span>';
            echo '</div>';
            echo '<div class="stat-item">';
            echo '<span class="stat-number">' . count($toursWithoutVehicles) . '</span>';
            echo '<span class="stat-label">Touren ohne Fahrzeug</span>';
            echo '</div>';
            echo '<div class="stat-item">';
            echo '<span class="stat-number">' . count($assignedVehicleIds) . '</span>';
            echo '<span class="stat-label">Zugeordnete Fahrzeuge</span>';
            echo '</div>';
            echo '</div>';
            
            $efficiency = count($this->data['vehicles']) > 0 ? 
                round((count($assignedVehicleIds) / count($this->data['vehicles'])) * 100) : 0;
            
            echo '<div class="efficiency-meter">';
            echo '<h4>🎯 Fahrzeug-Effizienz</h4>';
            echo '<div class="progress-meter">';
            echo '<div class="progress-fill" style="width: ' . $efficiency . '%"></div>';
            echo '</div>';
            echo '<p>' . $efficiency . '% der Fahrzeuge sind in Nutzung</p>';
            echo '</div>';
            
            echo '</div>';
            echo '</div>';
        }
        
        private function generateValidationTabFixed() {
            echo '<div class="validation-section" style="padding: 20px;">';
            echo '<h2>🔍 Datenstruktur-Validierung</h2>';
            echo '<p>Analyse deiner TheBus-Daten auf Vollständigkeit und Konsistenz.</p>';
            
            // Grundlegende Statistiken
            $totalTours = count($this->data['tours']);
            $totalVehicles = count($this->data['vehicles']);
            
            // Tour-Analyse
            $toursWithVehicles = 0;
            $toursWithoutVehicles = 0;
            $toursWithInvalidVehicles = 0;
            $vehicleIssues = [];
            $vehicleUsageCount = [];
            $vehicleToTours = []; // Welche Touren verwenden welche Fahrzeuge
            
            foreach ($this->data['tours'] as $tourName => $tour) {
                if (isset($tour['fleetVehicleId']) && !empty($tour['fleetVehicleId'])) {
                    $vehicleId = $tour['fleetVehicleId'];
                    
                    // Prüfe ob das Fahrzeug wirklich existiert
                    if (isset($this->data['vehicles'][$vehicleId])) {
                        $toursWithVehicles++;
                        
                        // Zähle Fahrzeug-Verwendung
                        if (!isset($vehicleUsageCount[$vehicleId])) {
                            $vehicleUsageCount[$vehicleId] = 0;
                            $vehicleToTours[$vehicleId] = [];
                        }
                        $vehicleUsageCount[$vehicleId]++;
                        $vehicleToTours[$vehicleId][] = $tourName;
                        
                    } else {
                        $toursWithInvalidVehicles++;
                        $vehicleIssues[] = $tourName . " (Fahrzeug " . $vehicleId . " nicht gefunden)";
                    }
                } else {
                    $toursWithoutVehicles++;
                }
            }
            
            // Finde mehrfach verwendete Fahrzeuge
            $multipleUsageVehicles = [];
            foreach ($vehicleUsageCount as $vehicleId => $count) {
                if ($count > 1) {
                    $multipleUsageVehicles[$vehicleId] = [
                        'count' => $count,
                        'tours' => $vehicleToTours[$vehicleId],
                        'vehicle' => $this->data['vehicles'][$vehicleId] ?? null
                    ];
                }
            }
            
            // Gesamte Touren ohne funktionsfähige Fahrzeuge
            $totalProblematicTours = $toursWithoutVehicles + $toursWithInvalidVehicles;
            
            // Fahrzeug-Analyse
            $usedVehicleIds = [];
            foreach ($this->data['tours'] as $tour) {
                if (isset($tour['fleetVehicleId']) && !empty($tour['fleetVehicleId'])) {
                    // Nur zählen wenn das Fahrzeug auch wirklich existiert
                    if (isset($this->data['vehicles'][$tour['fleetVehicleId']])) {
                        $usedVehicleIds[$tour['fleetVehicleId']] = true;
                    }
                }
            }
            $unusedVehicles = $totalVehicles - count($usedVehicleIds);
            
            // Gesundheitsscore
            $healthScore = $totalTours > 0 ? round(($toursWithVehicles / $totalTours) * 100) : 100;
            
            // Dashboard Grid
            echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">';
            
            echo '<div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 20px; border-radius: 10px; text-align: center;">';
            echo '<div style="font-size: 2em; font-weight: bold;">' . $toursWithVehicles . '</div>';
            echo '<div>Touren mit Fahrzeugen</div>';
            echo '</div>';
            
            echo '<div style="background: linear-gradient(135deg, #dc3545, #e74c3c); color: white; padding: 20px; border-radius: 10px; text-align: center;">';
            echo '<div style="font-size: 2em; font-weight: bold;">' . ($toursWithoutVehicles + $toursWithInvalidVehicles) . '</div>';
            echo '<div>Touren ohne gültige Fahrzeuge</div>';
            echo '</div>';
            
            echo '<div style="background: linear-gradient(135deg, #6f42c1, #8e44ad); color: white; padding: 20px; border-radius: 10px; text-align: center;">';
            echo '<div style="font-size: 2em; font-weight: bold;">' . $unusedVehicles . '</div>';
            echo '<div>Ungenutzte Fahrzeuge</div>';
            echo '</div>';
            
            echo '<div style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 20px; border-radius: 10px; text-align: center;">';
            echo '<div style="font-size: 2em; font-weight: bold;">' . $healthScore . '%</div>';
            echo '<div>Gesundheitsscore</div>';
            echo '</div>';
            
            echo '<div style="background: linear-gradient(135deg, #fd7e14, #e55100); color: white; padding: 20px; border-radius: 10px; text-align: center;">';
            echo '<div style="font-size: 2em; font-weight: bold;">' . count($multipleUsageVehicles) . '</div>';
            echo '<div>Mehrfach verwendete Fahrzeuge</div>';
            echo '</div>';
            
            echo '<div style="background: linear-gradient(135deg, #20c997, #198754); color: white; padding: 20px; border-radius: 10px; text-align: center;">';
            echo '<div style="font-size: 2em; font-weight: bold;">' . count($usedVehicleIds) . '</div>';
            echo '<div>Eindeutig verwendete Fahrzeuge</div>';
            echo '</div>';
            
            echo '</div>';
            
            // Mehrfach verwendete Fahrzeuge anzeigen
            if (!empty($multipleUsageVehicles)) {
                echo '<div style="background: #fff3cd; color: #856404; padding: 20px; border-radius: 10px; border: 1px solid #ffeaa7; margin: 20px 0;">';
                echo '<h4>⚠️ Mehrfach verwendete Fahrzeuge (' . count($multipleUsageVehicles) . ')</h4>';
                echo '<p>Diese Fahrzeuge sind mehreren Touren zugeordnet - das kann zu Konflikten führen:</p>';
                
                foreach ($multipleUsageVehicles as $vehicleId => $usage) {
                    $vehicle = $usage['vehicle'];
                    $plate = $vehicle['licensePlate'] ?? 'N/A';
                    $type = $vehicle['type'] ?? 'N/A';
                    
                    echo '<div style="background: rgba(255,255,255,0.7); padding: 15px; margin: 10px 0; border-radius: 8px;">';
                    echo '<h5>🚐 Fahrzeug ' . htmlspecialchars($vehicleId) . ' (' . htmlspecialchars($plate) . ' - ' . htmlspecialchars($type) . ')</h5>';
                    echo '<p><strong>Verwendet in ' . $usage['count'] . ' Touren:</strong></p>';
                    echo '<ul>';
                    foreach ($usage['tours'] as $tourName) {
                        $tourLine = $this->data['tours'][$tourName]['line'] ?? 'N/A';
                        echo '<li><strong>' . htmlspecialchars($tourName) . '</strong> (Linie ' . htmlspecialchars($tourLine) . ')</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }
                
                echo '<div style="background: rgba(220, 53, 69, 0.1); padding: 15px; border-radius: 8px; margin: 10px 0;">';
                echo '<h5>💡 Empfehlung:</h5>';
                echo '<ul>';
                echo '<li>Weise jeder Tour ein eindeutiges Fahrzeug zu</li>';
                echo '<li>Erstelle separate Zeitslots wenn ein Fahrzeug mehrere Touren nacheinander fahren soll</li>';
                echo '<li>Nutze zusätzliche Fahrzeuge aus dem ungenutzten Pool</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
            }
            
            // Detailanalyse
            if ($toursWithoutVehicles > 0) {
                echo '<div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 10px; border: 1px solid #f5c6cb; margin: 20px 0;">';
                echo '<h4>⚠️ Touren ohne Fahrzeuge (' . $toursWithoutVehicles . ')</h4>';
                echo '<p>Diese Touren können nicht fahren, da kein Fahrzeug zugeordnet ist:</p>';
                if (!empty($vehicleIssues)) {
                    echo '<ul>';
                    foreach (array_slice($vehicleIssues, 0, 10) as $issue) {
                        echo '<li>' . htmlspecialchars($issue) . '</li>';
                    }
                    if (count($vehicleIssues) > 10) {
                        echo '<li><em>... und ' . (count($vehicleIssues) - 10) . ' weitere</em></li>';
                    }
                    echo '</ul>';
                }
                echo '</div>';
            } else {
                echo '<div style="background: #d4edda; color: #155724; padding: 20px; border-radius: 10px; border: 1px solid #c3e6cb; margin: 20px 0;">';
                echo '<h4>✅ Perfekte Fahrzeug-Zuordnung!</h4>';
                echo '<p>Alle Touren haben gültige Fahrzeuge zugeordnet.</p>';
                echo '</div>';
            }
            
            if ($unusedVehicles > 0) {
                echo '<div style="background: #fff3cd; color: #856404; padding: 20px; border-radius: 10px; border: 1px solid #ffeaa7; margin: 20px 0;">';
                echo '<h4>💡 Optimierungsvorschlag</h4>';
                echo '<p>' . $unusedVehicles . ' Fahrzeuge werden nicht genutzt. Du könntest:</p>';
                echo '<ul>';
                echo '<li>Neue Touren für diese Fahrzeuge erstellen</li>';
                echo '<li>Sie als Reserve-Fahrzeuge nutzen</li>';
                echo '<li>Bestehende Touren erweitern</li>';
                echo '</ul>';
                echo '</div>';
            }
            
            // Zusammenfassung
            echo '<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">';
            echo '<h4>📊 Zusammenfassung</h4>';
            echo '<ul>';
            echo '<li><strong>' . $totalTours . '</strong> Touren total</li>';
            echo '<li><strong>' . $totalVehicles . '</strong> Fahrzeuge verfügbar</li>';
            echo '<li><strong>' . $toursWithVehicles . '</strong> funktionsfähige Touren</li>';
            echo '<li><strong>' . $healthScore . '%</strong> Systemeffizienz</li>';
            echo '</ul>';
            
            if ($healthScore >= 90) {
                echo '<p style="color: #28a745; font-weight: bold;">🟢 Exzellent - Dein System ist optimal konfiguriert!</p>';
            } elseif ($healthScore >= 70) {
                echo '<p style="color: #ffc107; font-weight: bold;">🟡 Gut - Einige Verbesserungen möglich</p>';
            } else {
                echo '<p style="color: #dc3545; font-weight: bold;">🔴 Verbesserung empfohlen - Mehrere Zuordnungsprobleme</p>';
            }
            echo '</div>';
            
            echo '</div>';
        }
        
        private function generateValidationTab() {
            echo '<div class="validation-section">';
            echo '<h2>🔍 Datenstruktur-Validierung</h2>';
            echo '<p>Hier werden Unstimmigkeiten, fehlende Felder und strukturelle Probleme in deinen TheBus-Daten erkannt.</p>';
            
            // 1. Tour-Validierung
            echo '<div class="validation-grid">';
            echo '<div class="validation-card">';
            echo '<h3>🚌 Tour-Analyse</h3>';
            
            $tourIssues = [];
            $missingVehicles = 0;
            $invalidSlots = 0;
            $missingLines = 0;
            
            foreach ($this->data['tours'] as $tourName => $tour) {
                $tourProblems = [];
                
                // Prüfe fleetVehicleId
                if (!isset($tour['fleetVehicleId']) || empty($tour['fleetVehicleId'])) {
                    $tourProblems[] = "Keine Fahrzeug-ID";
                    $missingVehicles++;
                }
                
                // Prüfe ob Fahrzeug existiert
                if (isset($tour['fleetVehicleId']) && !isset($this->data['vehicles'][$tour['fleetVehicleId']])) {
                    $tourProblems[] = "Fahrzeug {$tour['fleetVehicleId']} existiert nicht";
                }
                
                // Prüfe Line
                if (!isset($tour['line']) || empty($tour['line'])) {
                    $tourProblems[] = "Keine Linie definiert";
                    $missingLines++;
                }
                
                // Prüfe Slots
                if (!isset($tour['slots']) || !is_array($tour['slots']) || empty($tour['slots'])) {
                    $tourProblems[] = "Keine Slots definiert";
                    $invalidSlots++;
                }
                
                if (!empty($tourProblems)) {
                    $tourIssues[$tourName] = $tourProblems;
                }
            }
            
            echo '<div class="validation-stats">';
            echo "<div class='stat-box error'>❌ {$missingVehicles} Touren ohne Fahrzeug</div>";
            echo "<div class='stat-box warning'>⚠️ {$invalidSlots} Touren ohne Slots</div>";
            echo "<div class='stat-box info'>ℹ️ {$missingLines} Touren ohne Linie</div>";
            echo '</div>';
            
            if (!empty($tourIssues)) {
                echo '<div class="issues-list">';
                echo '<h4>🔴 Problematische Touren (Top 10):</h4>';
                $count = 0;
                foreach ($tourIssues as $tourName => $problems) {
                    if ($count >= 10) {
                        echo '<p><em>... und ' . (count($tourIssues) - 10) . ' weitere</em></p>';
                        break;
                    }
                    echo "<div class='issue-item'>";
                    echo "<strong>$tourName:</strong> " . implode(', ', $problems);
                    echo "</div>";
                    $count++;
                }
                echo '</div>';
            } else {
                echo '<div class="success-box">✅ Alle Touren sind strukturell korrekt!</div>';
            }
            echo '</div>';
            
            // 2. Fahrzeug-Validierung
            echo '<div class="validation-card">';
            echo '<h3>🚐 Fahrzeug-Analyse</h3>';
            
            $vehicleIssues = [];
            $missingPlates = 0;
            $missingTypes = 0;
            $invalidCapacity = 0;
            
            foreach ($this->data['vehicles'] as $vehicleId => $vehicle) {
                $vehicleProblems = [];
                
                // Prüfe Kennzeichen
                if (!isset($vehicle['licensePlate']) || empty($vehicle['licensePlate'])) {
                    $vehicleProblems[] = "Kein Kennzeichen";
                    $missingPlates++;
                }
                
                // Prüfe Fahrzeugtyp
                if (!isset($vehicle['type']) || empty($vehicle['type'])) {
                    $vehicleProblems[] = "Kein Fahrzeugtyp";
                    $missingTypes++;
                }
                
                // Prüfe Kapazität (optional, nicht alle Fahrzeugdaten haben das)
                if (isset($vehicle['passengerCapacity']) && $vehicle['passengerCapacity'] <= 0) {
                    $vehicleProblems[] = "Ungültige Kapazität: " . $vehicle['passengerCapacity'];
                    $invalidCapacity++;
                }
                
                if (!empty($vehicleProblems)) {
                    $vehicleIssues[$vehicleId] = $vehicleProblems;
                }
            }
            
            echo '<div class="validation-stats">';
            echo "<div class='stat-box error'>❌ {$missingPlates} ohne Kennzeichen</div>";
            echo "<div class='stat-box warning'>⚠️ {$missingTypes} ohne Typ</div>";
            echo "<div class='stat-box info'>ℹ️ {$invalidCapacity} ungültige Kapazität</div>";
            echo '</div>';
            
            if (!empty($vehicleIssues)) {
                echo '<div class="issues-list">';
                echo '<h4>🔴 Problematische Fahrzeuge (Top 10):</h4>';
                $count = 0;
                foreach ($vehicleIssues as $vehicleId => $problems) {
                    if ($count >= 10) {
                        echo '<p><em>... und ' . (count($vehicleIssues) - 10) . ' weitere</em></p>';
                        break;
                    }
                    echo "<div class='issue-item'>";
                    echo "<strong>$vehicleId:</strong> " . implode(', ', $problems);
                    echo "</div>";
                    $count++;
                }
                echo '</div>';
            } else {
                echo '<div class="success-box">✅ Alle Fahrzeuge sind strukturell korrekt!</div>';
            }
            echo '</div>';
            echo '</div>';
            
            // 3. Gesundheitsscore
            $totalIssues = count($tourIssues) + count($vehicleIssues);
            $totalItems = count($this->data['tours']) + count($this->data['vehicles']);
            $healthScore = $totalItems > 0 ? round(((($totalItems - $totalIssues) / $totalItems) * 100)) : 100;
            
            echo '<div class="summary-validation">';
            echo '<h3>📊 Gesamt-Gesundheitsstatus</h3>';
            echo '<div class="health-meter">';
            echo '<p>Datenqualität: <strong>' . $healthScore . '%</strong></p>';
            echo '<div class="health-bar">';
            echo '<div class="health-fill" style="width: ' . $healthScore . '%"></div>';
            echo '</div>';
            
            if ($healthScore >= 90) {
                echo '<p style="color: #28a745;">🟢 Ausgezeichnet - Deine Daten sind in sehr gutem Zustand!</p>';
            } elseif ($healthScore >= 70) {
                echo '<p style="color: #ffc107;">🟡 Gut - Einige kleinere Probleme gefunden</p>';
            } else {
                echo '<p style="color: #dc3545;">🔴 Problematisch - Mehrere Datenprobleme gefunden</p>';
            }
            
            // 4. Empfehlungen
            $recommendations = [];
            if ($missingVehicles > 0) {
                $recommendations[] = "Weise {$missingVehicles} Touren Fahrzeuge zu";
            }
            if ($invalidSlots > 0) {
                $recommendations[] = "Definiere Slots für {$invalidSlots} Touren";
            }
            if ($missingLines > 0) {
                $recommendations[] = "Weise {$missingLines} Touren Linien zu";
            }
            if ($invalidCapacity > 0) {
                $recommendations[] = "Korrigiere Kapazitäten für {$invalidCapacity} Fahrzeuge";
            }
            if ($missingPlates > 0) {
                $recommendations[] = "Füge Kennzeichen für {$missingPlates} Fahrzeuge hinzu";
            }
            if ($missingTypes > 0) {
                $recommendations[] = "Definiere Fahrzeugtypen für {$missingTypes} Fahrzeuge";
            }
            
            if (!empty($recommendations)) {
                echo '<div class="recommendations">';
                echo '<h4>💡 Empfehlungen:</h4>';
                echo '<ul>';
                foreach ($recommendations as $rec) {
                    echo "<li>$rec</li>";
                }
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<div class="success-box">✅ Keine Verbesserungen nötig - alles perfekt konfiguriert!</div>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        // FARB-GENERATOR FÜR LINIEN (ohne Hardcoding)
        private function getLineColor($line) {
            // Hash die Liniennummer für konsistente Farben
            $hash = md5($line);
            
            // Extrahiere RGB-Werte aus dem Hash
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            
            // Stelle sicher, dass die Farben nicht zu hell/dunkel sind
            // Normalisiere zu einem guten Bereich (60-200 für gute Lesbarkeit)
            $r = 60 + ($r % 140);
            $g = 60 + ($g % 140);
            $b = 60 + ($b % 140);
            
            return sprintf("#%02x%02x%02x", $r, $g, $b);
        }
        
        // HELLERE VERSION FÜR HOVER-EFFEKTE
        private function getLightLineColor($line) {
            $baseColor = $this->getLineColor($line);
            
            // Konvertiere zu RGB
            $r = hexdec(substr($baseColor, 1, 2));
            $g = hexdec(substr($baseColor, 3, 2));
            $b = hexdec(substr($baseColor, 5, 2));
            
            // Mache 40% heller
            $r = min(255, $r + 60);
            $g = min(255, $g + 60);
            $b = min(255, $b + 60);
            
            return sprintf("#%02x%02x%02x", $r, $g, $b);
        }
        
        // NEUE ANALYSE-FUNKTIONEN FÜR ERWEITERTE TOUR-ANALYSEN
        
        private function calculateVehicleStandtimes() {
            $vehicleUsage = [];
            
            foreach ($this->tourDetails as $tourName => $tour) {
                // Sichere Überprüfung der verschachtelten Array-Struktur
                if (isset($tour['vehicle']) && is_array($tour['vehicle']) && isset($tour['vehicle']['licensePlate'])) {
                    $vehicle = $tour['vehicle']['licensePlate'];
                    
                    if (!isset($vehicleUsage[$vehicle])) {
                        $vehicleUsage[$vehicle] = [
                            'tours' => [],
                            'totalWorkTime' => 0,
                            'activeTime' => 0,
                            'standtime' => 0,
                            'tourCount' => 0
                        ];
                    }
                    
                    // Sammle Tour-Zeiten für Standzeit-Berechnung
                    foreach ($this->timetableData as $timetable) {
                        if ($timetable['line'] === $tour['line']) {
                            foreach ($timetable['routes'] as $route) {
                                $startHour = $route['startTime']['hour'] ?? 0;
                                $startMinute = $route['startTime']['minute'] ?? 0;
                                $duration = 60; // Angenommene Tour-Dauer
                                
                                $vehicleUsage[$vehicle]['tours'][] = [
                                    'start' => $startHour * 60 + $startMinute,
                                    'end' => $startHour * 60 + $startMinute + $duration
                                ];
                                $vehicleUsage[$vehicle]['activeTime'] += $duration;
                            }
                        }
                    }
                    
                    $vehicleUsage[$vehicle]['tourCount']++;
                }
            }
            
            // Berechne Standzeiten
            foreach ($vehicleUsage as $vehicle => &$data) {
                if (count($data['tours']) > 1) {
                    // Sortiere Touren nach Startzeit
                    usort($data['tours'], function($a, $b) {
                        return $a['start'] - $b['start'];
                    });
                    
                    $totalWorkTime = ($data['tours'][count($data['tours'])-1]['end'] - $data['tours'][0]['start']);
                    $data['totalWorkTime'] = $totalWorkTime;
                    $data['standtime'] = $totalWorkTime - $data['activeTime'];
                }
            }
            
            return $vehicleUsage;
        }
        
        private function analyzeTimePatterns() {
            $analysis = [
                'rushHourTours' => 0,
                'rushHourPercent' => 0,
                'nightTours' => 0,
                'maxParallel' => 0,
                'hourlyDistribution' => array_fill(0, 24, 0)
            ];
            
            $totalTours = 0;
            $timeSlots = []; // Für Parallelität
            
            foreach ($this->timetableData as $timetable) {
                foreach ($timetable['routes'] as $route) {
                    $hour = $route['startTime']['hour'] ?? 0;
                    $minute = $route['startTime']['minute'] ?? 0;
                    $totalTours++;
                    
                    // Rush Hour Check (aus Config)
                    $rushMorning = $this->config['time_settings']['rush_hours']['morning'];
                    $rushEvening = $this->config['time_settings']['rush_hours']['evening'];
                    
                    if (($hour >= $rushMorning['start'] && $hour <= $rushMorning['end']) || 
                        ($hour >= $rushEvening['start'] && $hour <= $rushEvening['end'])) {
                        $analysis['rushHourTours']++;
                    }
                    
                    // Nachtfahrten
                    if ($hour >= 22 || $hour <= 6) {
                        $analysis['nightTours']++;
                    }
                    
                    // Stündliche Verteilung
                    $hourIndex = $hour % 24; // Verhindere Index 24, verwende 0 stattdessen
                    $analysis['hourlyDistribution'][$hourIndex]++;
                    
                    // Für Parallelität - sammle Zeitfenster
                    $startTime = $hour * 60 + $minute;
                    $endTime = $startTime + 60; // Angenommene Tour-Dauer
                    $timeSlots[] = ['start' => $startTime, 'end' => $endTime];
                }
            }
            
            // Berechne maximale Parallelität
            $analysis['maxParallel'] = $this->calculateMaxParallelTours($timeSlots);
            
            // Rush Hour Prozent
            if ($totalTours > 0) {
                $analysis['rushHourPercent'] = round(($analysis['rushHourTours'] / $totalTours) * 100);
            }
            
            return $analysis;
        }
        
        private function calculateMaxParallelTours($timeSlots) {
            $events = [];
            
            foreach ($timeSlots as $slot) {
                $events[] = ['time' => $slot['start'], 'type' => 'start'];
                $events[] = ['time' => $slot['end'], 'type' => 'end'];
            }
            
            // Sortiere Events nach Zeit
            usort($events, function($a, $b) {
                if ($a['time'] == $b['time']) {
                    return $a['type'] == 'end' ? -1 : 1; // Ende vor Start bei gleicher Zeit
                }
                return $a['time'] - $b['time'];
            });
            
            $currentParallel = 0;
            $maxParallel = 0;
            
            foreach ($events as $event) {
                if ($event['type'] == 'start') {
                    $currentParallel++;
                    $maxParallel = max($maxParallel, $currentParallel);
                } else {
                    $currentParallel--;
                }
            }
            
            return $maxParallel;
        }
        
        private function identifyProblemRoutes() {
            $problems = [
                'unassigned' => [],
                'lowFrequency' => []
            ];
            
            $routeFrequency = [];
            
            // Sammle Route-Häufigkeiten
            foreach ($this->tourDetails as $tour) {
                $routeKey = $tour['line'] . '_' . ($tour['route'] ?? 'unknown');
                
                if (!isset($routeFrequency[$routeKey])) {
                    $routeFrequency[$routeKey] = [
                        'line' => $tour['line'],
                        'route' => $tour['route'] ?? 'unknown',
                        'count' => 0,
                        'unassigned' => 0
                    ];
                }
                
                $routeFrequency[$routeKey]['count']++;
                
                if (!$tour['vehicle']) {
                    $routeFrequency[$routeKey]['unassigned']++;
                }
            }
            
            // Identifiziere Probleme
            foreach ($routeFrequency as $route) {
                // Unassigned Routes
                if ($route['unassigned'] > 0) {
                    $problems['unassigned'][] = $route;
                }
                
                // Low Frequency Routes
                if ($route['count'] < 3) {
                    $problems['lowFrequency'][] = $route;
                }
            }
            
            // Sortiere nach Schweregrad
            usort($problems['unassigned'], function($a, $b) {
                return $b['unassigned'] - $a['unassigned'];
            });
            
            usort($problems['lowFrequency'], function($a, $b) {
                return $a['count'] - $b['count'];
            });
            
            return $problems;
        }
        
        private function analyzeStopCoverage() {
            $stopCounts = [];
            $totalVisits = 0;
            
            foreach ($this->tourDetails as $tour) {
                // Prüfe ob stops existiert und ein Array ist
                if (isset($tour['stops']) && is_array($tour['stops'])) {
                    foreach ($tour['stops'] as $stop) {
                        $stopName = $stop['name'] ?? 'Unknown';
                        $stopCounts[$stopName] = ($stopCounts[$stopName] ?? 0) + 1;
                        $totalVisits++;
                    }
                }
            }
            
            // Sortiere nach Häufigkeit
            arsort($stopCounts);
            
            return [
                'totalStops' => count($stopCounts),
                'totalVisits' => $totalVisits,
                'topStops' => $stopCounts
            ];
        }
        
        private function calculateBenchmarks() {
            $lineMetrics = [];
            $totalTours = 0;
            $totalStops = 0;
            $totalDistance = 0;
            
            // Sammle Metriken pro Linie
            foreach ($this->tourDetails as $tour) {
                $line = $tour['line'];
                
                if (!isset($lineMetrics[$line])) {
                    $lineMetrics[$line] = [
                        'tours' => 0,
                        'totalStops' => 0,
                        'totalDistance' => 0,
                        'assignedVehicles' => 0
                    ];
                }
                
                $lineMetrics[$line]['tours']++;
                $lineMetrics[$line]['totalStops'] += $tour['totalStops'];
                $lineMetrics[$line]['totalDistance'] += $tour['totalDistance'];
                
                if ($tour['vehicle']) {
                    $lineMetrics[$line]['assignedVehicles']++;
                }
                
                $totalTours++;
                $totalStops += $tour['totalStops'];
                $totalDistance += $tour['totalDistance'];
            }
            
            // Berechne Performance-Scores
            $lineComparison = [];
            foreach ($lineMetrics as $line => $metrics) {
                $efficiency = $metrics['tours'] > 0 ? round(($metrics['assignedVehicles'] / $metrics['tours']) * 100) : 0;
                $avgStops = $metrics['tours'] > 0 ? $metrics['totalStops'] / $metrics['tours'] : 0;
                
                // Score-Berechnung (gewichtete Faktoren)
                $score = ($efficiency * 0.6) + (min($avgStops / 10, 1) * 20) + (($metrics['tours'] / max($totalTours/10, 1)) * 20);
                $score = min(100, round($score));
                
                $lineComparison[$line] = [
                    'tours' => $metrics['tours'],
                    'avgStops' => $avgStops,
                    'efficiency' => $efficiency,
                    'score' => $score
                ];
            }
            
            // Gesamt-Benchmarks
            $avgTourLength = $totalTours > 0 ? $totalStops / $totalTours : 0;
            $avgDistance = $totalTours > 0 ? $totalDistance / $totalTours : 0;
            
            $assignedCount = 0;
            foreach ($this->tourDetails as $tour) {
                if ($tour['vehicle']) $assignedCount++;
            }
            $overallEfficiency = $totalTours > 0 ? round(($assignedCount / $totalTours) * 100) : 0;
            
            $optimizationPotential = $overallEfficiency > 90 ? 'Gering' : ($overallEfficiency > 70 ? 'Mittel' : 'Hoch');
            
            return [
                'lineComparison' => $lineComparison,
                'avgTourLength' => $avgTourLength,
                'avgDistance' => $avgDistance,
                'overallEfficiency' => $overallEfficiency,
                'optimizationPotential' => $optimizationPotential
            ];
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
            
            // Sammle Tour-Daten pro Fahrzeug - MIT ECHTEN DATEN!
            foreach ($this->tourDetails as $tourName => $tour) {
                // Sichere Überprüfung der verschachtelten Array-Struktur
                if (!isset($tour['vehicle']) || !is_array($tour['vehicle']) || !isset($tour['vehicle']['licensePlate'])) continue;
                
                $vehicle = $tour['vehicle']['licensePlate'];
                if (empty($vehicle)) continue;
                
                if (!isset($vehicleEfficiency[$vehicle])) {
                    $vehicleEfficiency[$vehicle] = [
                        'totalWorkTime' => 0,
                        'activeTime' => 0,
                        'standtime' => 0,
                        'tourCount' => 0,
                        'efficiency' => 0,
                        'standtimeDetails' => [],
                        'tours' => [],
                        'vehicleId' => $tour['vehicleId'] ?? 'N/A', // Fahrzeug-ID hinzufügen
                        'vehicleData' => $tour['vehicle'] ?? []
                    ];
                }
                
                // ECHTE Tour-Dauer aus den Route-Daten! 
                $realDuration = $tour['totalTime'] ?? 0; // Minuten aus echten Routen
                
                // Falls keine echten Daten, Fallback-Berechnung
                if ($realDuration == 0) {
                    $tourStops = count($tour['stops'] ?? []);
                    $realDuration = max(30, min(180, $tourStops * 8));
                }
                
                $vehicleEfficiency[$vehicle]['activeTime'] += $realDuration;
                $vehicleEfficiency[$vehicle]['tourCount']++;
                
                // Sammle Tour-Info für Standzeit-Berechnung
                $vehicleEfficiency[$vehicle]['tours'][] = [
                    'name' => $tourName,
                    'duration' => $realDuration,
                    'line' => $tour['line'] ?? 'Unknown',
                    'startTimes' => $tour['realStartTimes'] ?? [],
                    'stops' => $tour['stops'] ?? []
                ];
            }
            
            // Berechne ECHTE Standzeiten aus realStartTimes!
            foreach ($vehicleEfficiency as $vehicle => &$data) {
                if ($data['tourCount'] > 0) {
                    
                    // Sammle alle echten Startzeiten für Standzeit-Berechnung
                    $allStartTimes = [];
                    $totalRealStandtime = 0;
                    $standtimeDetails = [];
                    
                    foreach ($data['tours'] as $tour) {
                        if (!empty($tour['startTimes'])) {
                            // realStartTimes ist ein Array - nehme alle Zeiten
                            if (is_array($tour['startTimes'])) {
                                foreach ($tour['startTimes'] as $startTime) {
                                    if (is_string($startTime) && !empty($startTime)) {
                                        $allStartTimes[] = [
                                            'time' => $startTime,
                                            'duration' => $tour['duration'],
                                            'line' => $tour['line'],
                                            'tour' => $tour['name']
                                        ];
                                    }
                                }
                            } else if (is_string($tour['startTimes'])) {
                                // Falls es nur ein String ist
                                $allStartTimes[] = [
                                    'time' => $tour['startTimes'],
                                    'duration' => $tour['duration'],
                                    'line' => $tour['line'],
                                    'tour' => $tour['name']
                                ];
                            }
                        }
                    }
                    
                    // Sortiere nach Startzeit für Standzeit-Berechnung
                    usort($allStartTimes, function($a, $b) {
                        // Sichere String-Vergleiche für Zeiten
                        $timeA = is_string($a['time']) ? $a['time'] : '';
                        $timeB = is_string($b['time']) ? $b['time'] : '';
                        
                        // Konvertiere zu Unix-Timestamps für korrekte Sortierung
                        $timestampA = strtotime($timeA);
                        $timestampB = strtotime($timeB);
                        
                        if ($timestampA === false) $timestampA = 0;
                        if ($timestampB === false) $timestampB = 0;
                        
                        return $timestampA - $timestampB;
                    });
                    
                    if (count($allStartTimes) <= 1) {
                        // Nur 1 Tour oder weniger = KEINE Standzeit!
                        $data['standtime'] = 0;
                        $data['totalWorkTime'] = $data['activeTime'];
                        $data['efficiency'] = 100; // Perfekte Effizienz bei nur 1 Tour
                    } else {
                        // Berechne ECHTE Standzeiten zwischen Touren
                        for ($i = 1; $i < count($allStartTimes); $i++) {
                            $prevTour = $allStartTimes[$i-1];
                            $currentTour = $allStartTimes[$i];
                            
                            // Sichere Zeitkonvertierung
                            $prevStartTime = strtotime($prevTour['time']);
                            $currentStartTime = strtotime($currentTour['time']);
                            
                            if ($prevStartTime !== false && $currentStartTime !== false) {
                                // Berechne Ende der vorherigen Tour
                                $prevEndTime = $prevStartTime + ($prevTour['duration'] * 60);
                                
                                // Standzeit = Zeit zwischen Tour-Ende und nächstem Start
                                $standtimeMinutes = max(0, ($currentStartTime - $prevEndTime) / 60);
                                
                                if ($standtimeMinutes > 5) { // Nur echte Standzeiten > 5 Min
                                    $totalRealStandtime += $standtimeMinutes;
                                    $standtimeDetails[] = [
                                        'duration' => round($standtimeMinutes),
                                        'location' => 'Nach ' . $prevTour['line'],
                                        'timeRange' => date('H:i', $prevEndTime) . ' - ' . date('H:i', $currentStartTime)
                                    ];
                                }
                            }
                        }
                        
                        $data['standtime'] = round($totalRealStandtime);
                        $data['standtimeDetails'] = $standtimeDetails;
                        $data['totalWorkTime'] = $data['activeTime'] + $data['standtime'];
                        $data['efficiency'] = $data['totalWorkTime'] > 0 ? 
                            round(($data['activeTime'] / $data['totalWorkTime']) * 100) : 100;
                    }
                    
                    // Overall Stats updaten
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
                'efficiency' => $overallStats['vehicleCount'] > 0 ? round($overallStats['totalEfficiency'] / $overallStats['vehicleCount']) : 0,
                'avgStandtime' => $overallStats['vehicleCount'] > 0 ? round($overallStats['totalStandtime'] / $overallStats['vehicleCount']) : 0,
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
                $recommendations['immediate'][] = "🚨 {$overall['problemVehicles']} Fahrzeuge mit >4h Standzeit optimieren";
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
</body>
</html>