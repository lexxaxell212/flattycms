<?php
$page_title = "Panduan Maps";
//require_once SRC_PATH . "header.php";
?>

<div class="container">
   <head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    </head>
    
    <div class="app-container">
        
        <!-- FLOATING TOGGLE -->
        <button class="floating-toggle" onclick="toggleSidebar()" id="toggleBtn">
            <i class="fas fa-list"></i>
        </button>

        <!-- SIDEBAR PUTIH -->
        <div class="sidebar hidden" id="sidebar">
            <div class="sidebar-header">
                <div>
                    <div class="sidebar-title">
                        <i class="fas fa-location-dot"></i>
                        Destinasi Wisata
                        <span class="sidebar-count">5</span>
                    </div>
                </div>
                <button class="reset-btn" onclick="resetView()">
                    <i class="fas fa-home"></i> Kembali Pusat Kota
                </button>
            </div>
            
            <div class="tourism-list" id="tourismList"></div>
        </div>

        <!-- MAP -->
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let tourismData = [];

        async function loadLocations() {
            const res = await fetch('/api/api-maps.php', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const json = await res.json();
            tourismData = json.data;
            initMap();
            generateCards();
        }

        let map;
        
        function initMap() {
    map = L.map('map').setView([-6.9175, 107.6191], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    tourismData.forEach((place, index) => {
        const marker = L.marker([place.lat, place.lng]).addTo(map);
        marker.bindPopup(`
            <div style="text-align:center;padding:1.5rem;width:260px">
                <div style="font-size:2.2rem;color:#3b82f6;margin-bottom:0.8rem">
                    <i class="${place.icon}"></i>
                </div>
                <div style="font-weight:700;font-size:1.2rem;color:#1e293b;margin-bottom:0.5rem">
                    ${place.name}
                </div>
                <div style="color:#64748b;font-size:0.95rem;margin-bottom:1rem;line-height:1.4">
                    ${place.description}
                </div>
                <div style="color:#475569;font-weight:500;font-size:0.9rem">
                    📍 ${place.distance} dari pusat kota
                </div>
            </div>
        `);
    });
}

        loadLocations();

        function generateCards() {
            const container = document.getElementById('tourismList');
            container.innerHTML = '';
            tourismData.forEach((place, index) => {
                const card = document.createElement('div');
                card.className = 'tourism-card';
                card.onclick = () => selectPlace(index);
                card.innerHTML = `
                    <div class="tourism-icon">
                        <i class="${place.icon}"></i>
                    </div>
                    <div class="tourism-content">
                        <div class="tourism-name">${place.name}</div>
                        <div class="tourism-desc">${place.description}</div>
                        <div class="tourism-meta">
                            <div class="tourism-distance">
                                <i class="fas fa-road"></i> ${place.distance} dari pusat kota
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function selectPlace(index) {
            document.querySelectorAll('.tourism-card').forEach((c, i) => 
                c.classList.toggle('active', i === index)
            );
            const place = tourismData[index];
            map.flyTo([place.lat, place.lng], 15, { duration: 1 });
        }

        function resetView() {
            map.flyTo([-6.9175, 107.6191], 11);
            document.querySelectorAll('.tourism-card').forEach(c => 
                c.classList.remove('active')
            );
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const btn = document.getElementById('toggleBtn');
            sidebar.classList.toggle('hidden');
            btn.classList.toggle('open');
            btn.innerHTML = sidebar.classList.contains('hidden') 
                ? '<i class="fas fa-list"></i>' 
                : '<i class="fas fa-times"></i>';
        }

    </script>

</div>

<?php
//require_once SRC_PATH . "footer.php"; ?>