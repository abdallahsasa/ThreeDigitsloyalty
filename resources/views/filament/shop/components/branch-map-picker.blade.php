@php
    $isAr = app()->getLocale() === 'ar';
@endphp

<div
    wire:ignore
    x-data="{
        lat: $wire.entangle('data.latitude'),
        lng: $wire.entangle('data.longitude'),
        searchQuery: '',
        searching: false,
        map: null,
        marker: null,
        init() {
            const loadLeaflet = () => {
                if (window.L) {
                    this.setupMap();
                    return;
                }
                if (!document.getElementById('leaflet-css')) {
                    const link = document.createElement('link');
                    link.id = 'leaflet-css';
                    link.rel = 'stylesheet';
                    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                    document.head.appendChild(link);
                }
                if (!document.getElementById('leaflet-js')) {
                    const script = document.createElement('script');
                    script.id = 'leaflet-js';
                    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    script.onload = () => this.setupMap();
                    document.head.appendChild(script);
                } else {
                    const timer = setInterval(() => {
                        if (window.L) {
                            clearInterval(timer);
                            this.setupMap();
                        }
                    }, 100);
                }
            };
            loadLeaflet();
        },
        setupMap() {
            const hasCoords = this.lat !== null && this.lat !== '' && this.lng !== null && this.lng !== '';
            const startLat = hasCoords ? parseFloat(this.lat) : 33.5138;
            const startLng = hasCoords ? parseFloat(this.lng) : 36.2765;

            this.map = L.map(this.$refs.mapContainer).setView([startLat, startLng], hasCoords ? 16 : 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(this.map);

            if (hasCoords) {
                this.placeMarker(startLat, startLng);
            }

            this.map.on('click', (e) => {
                this.setCoords(e.latlng.lat, e.latlng.lng);
            });

            setTimeout(() => this.map && this.map.invalidateSize(), 250);
        },
        placeMarker(lat, lng) {
            if (this.marker) {
                this.marker.setLatLng([lat, lng]);
            } else {
                this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                this.marker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    this.setCoords(pos.lat, pos.lng, false);
                });
            }
        },
        setCoords(lat, lng, pan = true) {
            const fixedLat = Number(lat).toFixed(7);
            const fixedLng = Number(lng).toFixed(7);
            this.lat = fixedLat;
            this.lng = fixedLng;
            if (this.$wire && typeof this.$wire.set === 'function') {
                this.$wire.set('data.latitude', fixedLat, false);
                this.$wire.set('data.longitude', fixedLng, false);
            }
            this.placeMarker(parseFloat(fixedLat), parseFloat(fixedLng));
            if (pan && this.map) {
                this.map.setView([parseFloat(fixedLat), parseFloat(fixedLng)], Math.max(this.map.getZoom(), 15));
            }
        },
        clearLocation() {
            this.lat = null;
            this.lng = null;
            if (this.$wire && typeof this.$wire.set === 'function') {
                this.$wire.set('data.latitude', null, false);
                this.$wire.set('data.longitude', null, false);
            }
            if (this.marker && this.map) {
                this.map.removeLayer(this.marker);
                this.marker = null;
            }
        },
        useMyLocation() {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition((pos) => {
                this.setCoords(pos.coords.latitude, pos.coords.longitude);
            });
        },
        async searchPlace() {
            if (!this.searchQuery.trim()) return;
            this.searching = true;
            try {
                const url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(this.searchQuery.trim());
                const res = await fetch(url);
                const data = await res.json();
                if (data && data.length > 0) {
                    this.setCoords(parseFloat(data[0].lat), parseFloat(data[0].lon));
                }
            } catch (e) {
            } finally {
                this.searching = false;
            }
        }
    }"
    style="display: grid; gap: 0.75rem;"
>
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 0.5rem;">
        <label style="font-size: 0.875rem; font-weight: 600;">
            {{ $isAr ? 'موقع الفرع على الخريطة (لتنبيهات القرب التلقائية)' : 'Branch Location on Map (for Nearby Wallet Alerts)' }}
        </label>
        <span x-show="lat && lng" style="font-size: 0.78rem; color: #10b981; font-weight: 700;">
            ✓ {{ $isAr ? 'تم تحديد الموقع' : 'Location pinned' }}
        </span>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
        <input type="text"
               x-model="searchQuery"
               @keydown.enter.prevent="searchPlace()"
               placeholder="{{ $isAr ? 'ابحث عن مدينة أو حي أو شارع...' : 'Search city, neighborhood, or street...' }}"
               style="flex: 1; min-width: 200px; padding: 0.55rem 0.8rem; border-radius: 10px; border: 1px solid rgba(148,163,184,0.35); background: transparent; font-size: 0.86rem;">
        <button type="button"
                @click="searchPlace()"
                style="padding: 0.55rem 1rem; border-radius: 10px; border: none; background: #743EE2; color: #ffffff; font-weight: 700; font-size: 0.84rem; cursor: pointer;">
            <span x-show="!searching">{{ $isAr ? 'بحث' : 'Search' }}</span>
            <span x-show="searching">...</span>
        </button>
        <button type="button"
                @click="useMyLocation()"
                style="padding: 0.55rem 1rem; border-radius: 10px; border: 1px solid rgba(16,185,129,0.45); background: rgba(16,185,129,0.14); color: #10b981; font-weight: 700; font-size: 0.84rem; cursor: pointer;">
            📍 {{ $isAr ? 'موقعي الحالي' : 'My Location' }}
        </button>
        <button type="button"
                x-show="lat && lng"
                @click="clearLocation()"
                style="padding: 0.55rem 0.9rem; border-radius: 10px; border: 1px solid rgba(248,113,113,0.4); background: rgba(248,113,113,0.1); color: #f87171; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
            {{ $isAr ? 'مسح' : 'Clear' }}
        </button>
    </div>

    <div x-ref="mapContainer"
         style="width: 100%; height: 320px; border-radius: 14px; border: 1px solid rgba(148,163,184,0.3); overflow: hidden; z-index: 1;">
    </div>

    <p style="margin: 0; font-size: 0.78rem; color: #94a3b8;">
        {{ $isAr ? 'انقر في أي مكان على الخريطة أو اسحب الدبوس لتحديد موقع الفرع بدقة.' : 'Click anywhere on the map or drag the pin to set your exact branch location.' }}
    </p>
</div>
