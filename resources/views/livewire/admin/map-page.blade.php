<div class="content">
    <div class="map-page-wrap">
        <aside class="map-page-legend" aria-label="Forklaring af markeringer">
            <p class="map-page-legend-title">Markeringer</p>
            <ul>
                <li>
                    <img src="/images/church-yellow.png" alt="" width="20" height="20" />
                    <span>Gul – åbent område</span>
                </li>
                <li>
                    <img src="/images/church-red.png" alt="" width="20" height="20" />
                    <span>Rød – dronegodkendelse</span>
                </li>
                <li>
                    <img src="/images/church-green.png" alt="" width="20" height="20" />
                    <span>Grøn – med DJI-billeder</span>
                </li>
                <li>
                    <img src="/images/church-blue.png" alt="" width="20" height="20" />
                    <span>Blå – kontaktet for nylig</span>
                </li>
                <li>
                    <img src="/images/church-pink.png" alt="" width="20" height="20" />
                    <span>Lyserød – kontakt for over 30 dage siden</span>
                </li>
                <li>
                    <img src="/images/church-black.png" alt="" width="20" height="20" />
                    <span>Sort – kontakt senere</span>
                </li>
            </ul>
        </aside>
        <div id="map" style="height: 80vh;">
        </div>
    </div>
</div>
@script
    <script>
        const kirker = JSON.parse('{!! json_encode($kirker) !!}');
        const finished = JSON.parse('{!! json_encode($finished) !!}');
        const contacted = JSON.parse('{!! json_encode($contacted) !!}');
        // Globals similar to your component state
        let map = null;
        let infoWindow = null;
        let markers = [];
        let clicks = []; // tracks click LatLngs like your React code
        let center = {
            lat: 56.43031025601471,
            lng: 10.713182042019326
        };
        let zoom = 8;

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

            // Render kirker: yellow (open_area) or red (drone_approval)
            Object.values(kirker).forEach(ch => {
                const lat = parseFloat(ch.latitude);
                const lng = parseFloat(ch.longitude);
                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

                const marker = new google.maps.Marker({
                    map,
                    position: {
                        lat,
                        lng
                    },
                    icon: ch.open_area ? "/images/church-yellow.png" : "/images/church-red.png",
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

            // Render finished: green icons
            Object.values(finished).forEach(ch => {
                const lat = parseFloat(ch.latitude);
                const lng = parseFloat(ch.longitude);
                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

                const marker = new google.maps.Marker({
                    map,
                    position: {
                        lat,
                        lng
                    },
                    icon: "/images/church-green.png",
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

            // Render contacted: blue (recent), pink (old), or black (contact_later)
            Object.values(contacted).forEach(ch => {
                const lat = parseFloat(ch.latitude);
                const lng = parseFloat(ch.longitude);
                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

                let icon = "/images/church-blue.png";
                if (ch.contact_later) {
                    icon = "/images/church-black.png";
                } else if (ch.old) {
                    icon = "/images/church-pink.png";
                }

                const title = ch.date ? `${ch.name} - ${ch.date}` : ch.name;

                const marker = new google.maps.Marker({
                    map,
                    position: {
                        lat,
                        lng
                    },
                    icon: icon,
                    title: title
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
