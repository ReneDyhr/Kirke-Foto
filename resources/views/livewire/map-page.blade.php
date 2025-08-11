<div class="content">
    <div id="map">
    </div>
</div>
@script
    <script>
        const churches = JSON.parse('{!! json_encode($churches) !!}');
        // Globals similar to your component state
        let map = null;
        let infoWindow = null;
        let markers = [];
        let clicks = []; // tracks click LatLngs like your React code
        let center = {
            lat: 56.43031025601471,
            lng: 10.713182042019326
        };
        let zoom = 7;

        function churchInfoContent(church) {
            const parishName = church.parish || "";
            const deaneryName = church.deanery || "";
            const dioceseName = church.diocese || "";
            const parishUrl = church.parish_url || "";
            const churchUrl = church.url || "";

            const crumbs = [parishName, deaneryName, dioceseName].filter(Boolean).join(" - ");

            return `
        <div id="content">
            <h1 class="church-header">${escapeHtml(church.name || "")}</h1>
            <span class="church-breadcrumb">${escapeHtml(crumbs)}</span>
            <a class="church-button" href="/kirke/${encodeURIComponent(parishUrl)}/${encodeURIComponent(churchUrl)}">
            <button class="btn btn-sm btn-primary">
                Gå til ${escapeHtml(church.name || "")}&nbsp;&nbsp;<i class="fa fa-chevron-right"></i>
            </button>
            </a>
        </div>
        `;
        }

        function escapeHtml(s) {
            return String(s)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;");
        }

        function clearMarkers() {
            for (const m of markers) m.setMap(null);
            markers = [];
        }

        function renderMarkers() {
            clearMarkers();
            if (!map) return;

            const sharedInfoWindow = infoWindow || new google.maps.InfoWindow();
            infoWindow = sharedInfoWindow;

            churches.forEach(ch => {
                const lat = parseFloat(ch.latitude);
                const lng = parseFloat(ch.longitude);
                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

                const marker = new google.maps.Marker({
                    map,
                    position: {
                        lat,
                        lng
                    },
                    icon: "https://api.kirke-foto.dk/church-red.png",
                    title: ch.name || ""
                });

                marker.addListener("click", () => {
                    sharedInfoWindow.setContent(churchInfoContent(ch));
                    sharedInfoWindow.open({
                        anchor: marker,
                        map,
                        shouldFocus: false
                    });
                });

                markers.push(marker);
            });
        }

        function updateMapStateDisplay() {
            const el = document.getElementById("map-state");
            if (!el || !map) return;
            const c = map.getCenter().toJSON();
            el.textContent = `Zoom: ${map.getZoom()} | Center: ${c.lat.toFixed(5)}, ${c.lng.toFixed(5)}`;
        }

        // Main init (callback from Google Maps loader)
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center,
                zoom,
                streetViewControl: false,
                mapTypeControl: true
            });

            // Events equivalent to onClick / onIdle
            map.addListener("click", (e) => {
                if (e && e.latLng) {
                    clicks.push(e.latLng.toJSON());
                    // do whatever you need with clicks[]
                }
            });

            map.addListener("idle", () => {
                zoom = map.getZoom();
                center = map.getCenter().toJSON();
                updateMapStateDisplay();
            });

            // const enriched = enrichChurches(churches, parishes, deaneries, dioceses);
            renderMarkers();

            // Initial UI update
            updateMapStateDisplay();
        }

        // Expose initMap globally for the Google Maps script callback
        window.initMap = initMap;
        window.initMap();
    </script>
@endscript
