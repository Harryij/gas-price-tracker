<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gas Price Tracker</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --ink: #202124;
            --muted: #5f6368;
            --line: #cfe7cf;
            --panel: #ffffff;
            --primary: #90EE90;
            --primary-dark: #176b35;
            --primary-soft: #eefdee;
            --green: #176b35;
            --surface: #f4fbf4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow: hidden;
            color: var(--ink);
            background: var(--surface);
        }

        button,
        input,
        select {
            font: inherit;
        }

        .map-shell,
        #stationMap {
            position: relative;
            width: 100vw;
            height: 100vh;
        }

        #stationMap {
            z-index: 1;
            background: #e8f8e8;
        }

        .leaflet-control-attribution {
            font-size: 10px;
        }

        .search-panel {
            position: absolute;
            z-index: 500;
            top: 18px;
            left: 18px;
            width: min(430px, calc(100vw - 36px));
            display: grid;
            gap: 10px;
        }

        .system-title {
            position: absolute;
            z-index: 510;
            top: 18px;
            left: 85%;
            transform: translateX(-50%);
            width: min(520px, calc(100vw - 40px));
            padding: 14px 20px;
            text-align: center;
        }

        .system-title h1 {
            margin: 0;
            color: #103b20;
            font-size: 1.60rem;
            line-height: 1.25;
            font-weight: 800;
            font-family: "Satisfy", cursive;
        }

        .search-box {
            min-height: 56px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 10px;
            padding: 0 14px;
            border-radius: 8px;
            background: var(--panel);
            box-shadow: 0 2px 8px rgba(60, 64, 67, .28);
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: block;
            object-fit: contain;
        }

        .search-box input {
            min-width: 0;
            height: 42px;
            border: 0;
            outline: 0;
            color: var(--ink);
            font-size: .96rem;
        }

        .icon-button {
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 50%;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            font-size: 1.25rem;
        }

        .filter-row {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .filter-row select,
        .filter-row button {
            min-height: 38px;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 0 14px;
            background: var(--panel);
            color: var(--ink);
            box-shadow: 0 1px 4px rgba(60, 64, 67, .2);
            white-space: nowrap;
        }

        .results-panel {
            position: absolute;
            z-index: 480;
            top: 126px;
            left: 18px;
            width: min(390px, calc(100vw - 36px));
            max-height: calc(100vh - 150px);
            overflow: hidden;
            border-radius: 8px;
            background: var(--panel);
            box-shadow: 0 2px 8px rgba(60, 64, 67, .24);
            display: flex;
            flex-direction: column;
            transition: width .2s ease, max-height .2s ease;
        }

        .results-panel.collapsed {
            width: min(260px, calc(100vw - 36px));
            max-height: 68px;
        }

        .results-panel.collapsed .station-list {
            display: none;
        }

        .results-panel.collapsed .stations-section {
            display: none;
        }

        .results-panel.collapsed .price-comparison {
            display: none;
        }

        .results-panel.collapsed .results-header p {
            display: none;
        }

        .results-header {
            padding: 16px 18px 10px;
            border-bottom: 1px solid var(--line);
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: start;
        }

        .results-header h1 {
            margin: 0;
            font-size: 1rem;
            line-height: 1.2;
        }

        .results-header p {
            grid-column: 1 / -1;
            margin: 5px 0 0;
            color: var(--muted);
            font-size: .84rem;
        }

        .price-comparison {
            padding: 14px 18px;
            border-bottom: 1px solid var(--line);
            background: #fbfffb;
        }

        .price-comparison.collapsed .comparison-list {
            display: none;
        }

        .comparison-header {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: center;
            margin-bottom: 10px;
        }

        .price-comparison.collapsed .comparison-header {
            margin-bottom: 0;
        }

        .price-comparison h2 {
            margin: 0;
            color: #103b20;
            font-size: .96rem;
            line-height: 1.2;
        }

        .comparison-list {
            display: grid;
            gap: 10px;
        }

        .comparison-item {
            width: 100%;
            display: grid;
            gap: 7px;
            padding: 10px;
            border: 1px solid #edf0f2;
            border-radius: 8px;
            background: white;
            text-align: left;
            cursor: pointer;
        }

        .comparison-item:hover,
        .comparison-item:focus-visible,
        .comparison-item.active {
            border-color: var(--primary);
            background: var(--primary-soft);
            outline: 0;
        }

        .comparison-fuel {
            margin: 0;
            font-size: .84rem;
            font-weight: 800;
            color: var(--ink);
        }

        .comparison-extremes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .comparison-extreme {
            min-width: 0;
            display: grid;
            gap: 2px;
        }

        .comparison-extreme span {
            color: var(--muted);
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .comparison-extreme strong {
            color: var(--green);
            font-size: .9rem;
            line-height: 1.2;
        }

        .comparison-extreme small {
            overflow: hidden;
            color: var(--muted);
            font-size: .72rem;
            line-height: 1.25;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .collapse-results {
            width: 32px;
            height: 32px;
            border: 1px solid var(--line);
            border-radius: 50%;
            background: var(--primary-soft);
            color: #103b20;
            cursor: pointer;
            font-size: 1.2rem;
            line-height: 1;
        }

        .collapse-results:hover,
        .collapse-results:focus-visible {
            background: var(--primary);
        }

        .stations-section {
            min-height: 0;
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            border-bottom: 1px solid var(--line);
            background: white;
        }

        .stations-section.collapsed {
            flex: 0 0 auto;
        }

        .stations-section.collapsed .station-list {
            display: none;
        }

        .stations-header {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: center;
            padding: 12px 18px;
            border-bottom: 1px solid #edf0f2;
            background: #ffffff;
        }

        .stations-section.collapsed .stations-header {
            border-bottom: 0;
        }

        .stations-header h2 {
            margin: 0;
            color: #103b20;
            font-size: .96rem;
            line-height: 1.2;
        }

        .station-list {
            min-height: 0;
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 6px 0;
        }

        .station-result {
            width: 100%;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 12px;
            align-items: start;
            border: 0;
            border-bottom: 1px solid #edf0f2;
            background: transparent;
            padding: 14px 18px;
            text-align: left;
            cursor: pointer;
        }

        .station-result:hover,
        .station-result.active {
            background: var(--primary-soft);
        }

        .station-result[hidden] {
            display: none;
        }

        .station-logo {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 0 0 2px rgba(255,255,255,.95), 0 1px 4px rgba(60,64,67,.22);
            object-fit: contain;
            padding: 4px;
        }

        .station-result strong {
            display: block;
            font-size: .94rem;
            line-height: 1.25;
        }

        .station-result span {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: .82rem;
            line-height: 1.35;
        }

        .lowest-price {
            color: var(--green);
            font-weight: 800;
            white-space: nowrap;
            font-size: .9rem;
        }

        .station-div-icon {
            overflow: visible;
        }

        .station-marker {
            position: relative;
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #ffffff;
            border: 3px solid var(--primary);
            box-shadow: 0 3px 9px rgba(60,64,67,.34);
            overflow: hidden;
        }

        .station-marker img {
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            display: block;
            object-fit: contain;
            padding: 3px;
            border-radius: 50%;
        }

        .station-marker.active {
            width: 52px;
            height: 52px;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(144,238,144,.42), 0 6px 16px rgba(60,64,67,.42);
        }

        .station-marker.lowest-comparison {
            border-color: #188038;
            box-shadow: 0 0 0 4px rgba(24,128,56,.24), 0 6px 16px rgba(60,64,67,.42);
        }

        .station-marker.highest-comparison {
            border-color: #b3261e;
            box-shadow: 0 0 0 4px rgba(179,38,30,.22), 0 6px 16px rgba(60,64,67,.42);
        }

        .station-marker.lowest-comparison.highest-comparison {
            border-color: #176b35;
            box-shadow: 0 0 0 4px rgba(23,107,53,.3), 0 6px 16px rgba(60,64,67,.42);
        }

        .station-marker-label {
            position: absolute;
            left: 50%;
            top: 45px;
            min-width: 132px;
            transform: translateX(-50%);
            display: grid;
            gap: 2px;
            padding: 6px 8px;
            border-radius: 8px;
            background: white;
            box-shadow: 0 1px 4px rgba(60,64,67,.25);
            font-size: .68rem;
            font-weight: 800;
            color: var(--ink);
        }

        .marker-price-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            line-height: 1.2;
            white-space: nowrap;
        }

        .marker-price-row span:first-child {
            color: var(--muted);
            font-weight: 700;
        }

        .detail-panel {
            position: absolute;
            z-index: 520;
            right: 18px;
            top: 18px;
            width: min(380px, calc(100vw - 36px));
            max-height: calc(100vh - 36px);
            overflow-y: auto;
            border-radius: 8px;
            background: var(--panel);
            box-shadow: 0 2px 10px rgba(60, 64, 67, .3);
            transform: translateX(calc(100% + 32px));
            transition: transform .2s ease;
        }

        .detail-panel.open {
            transform: translateX(0);
        }

        .detail-hero {
            min-height: 116px;
            padding: 16px;
            color: white;
            background:
                linear-gradient(135deg, rgba(23,107,53,.08), rgba(23,107,53,.42)),
                var(--station-color, var(--primary));
        }

        .detail-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .detail-close {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            background: rgba(16,59,32,.16);
            color: white;
            cursor: pointer;
            font-size: 1.15rem;
        }

        .detail-hero h2 {
            margin: 18px 0 4px;
            font-size: 1.35rem;
            line-height: 1.18;
        }

        .detail-hero p {
            margin: 0;
            opacity: .9;
            font-size: .9rem;
        }

        .detail-body {
            padding: 16px;
        }

        .meta-line {
            display: flex;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #edf0f2;
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.45;
        }

        .price-section {
            margin-top: 16px;
        }

        .price-section h3 {
            margin: 0 0 10px;
            font-size: .96rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid #edf0f2;
        }

        .price-row span {
            color: var(--muted);
            display: block;
            margin-top: 3px;
            font-size: .78rem;
        }

        .price-row strong {
            color: var(--green);
            font-size: 1rem;
            white-space: nowrap;
        }

        .empty-note {
            padding: 14px 18px;
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.5;
        }

        .zoom-controls {
            position: absolute;
            z-index: 490;
            right: 18px;
            bottom: 24px;
            display: grid;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(60,64,67,.25);
        }

        .zoom-controls button {
            width: 42px;
            height: 42px;
            border: 0;
            border-bottom: 1px solid var(--line);
            background: white;
            color: var(--ink);
            font-size: 1.25rem;
            cursor: pointer;
        }

        .zoom-controls button:last-child {
            border-bottom: 0;
        }

        .database-warning,
        .map-warning {
            position: absolute;
            z-index: 530;
            left: 50%;
            bottom: 24px;
            width: min(540px, calc(100vw - 36px));
            transform: translateX(-50%);
            border-radius: 8px;
            background: #fff8e1;
            border: 1px solid #f3d47a;
            padding: 12px 14px;
            color: #5f4700;
            box-shadow: 0 2px 8px rgba(60,64,67,.22);
            font-size: .9rem;
        }

        .map-warning[hidden] {
            display: none;
        }

        @media (max-width: 760px) {
            body {
                overflow: auto;
            }

            .map-shell,
            #stationMap {
                min-height: 100vh;
                height: 100svh;
            }

            .search-panel {
                top: 76px;
                left: 10px;
                width: calc(100vw - 20px);
            }

            .system-title {
                top: 10px;
                width: calc(100vw - 20px);
                padding: 11px 14px;
            }

            .system-title h1 {
                font-size: .98rem;
            }

            .results-panel {
                left: 10px;
                right: 10px;
                top: auto;
                bottom: 0;
                width: auto;
                max-height: 42vh;
                border-radius: 12px 12px 0 0;
            }

            .detail-panel {
                left: 10px;
                right: 10px;
                top: auto;
                bottom: 10px;
                width: auto;
                max-height: 72vh;
                transform: translateY(calc(100% + 24px));
            }

            .detail-panel.open {
                transform: translateY(0);
            }

            .zoom-controls {
                right: 12px;
                bottom: calc(42vh + 16px);
            }

            .station-marker-label {
                min-width: 112px;
                top: 42px;
                padding: 5px 7px;
                font-size: .62rem;
            }
        }
    </style>
</head>
<body>
    <main class="map-shell" aria-label="Zamboanga City gas station map">
        <div id="stationMap"></div>

        <section class="system-title" aria-label="System title">
            <h1>Zamboanga City Fuel Market Monitoring</h1>
        </section>

        <section class="search-panel" aria-label="Search and filters">
            <div class="search-box">
                <img class="brand-mark" src="{{ asset('brand-icons/gas-map-search-icon.png') }}" alt="Gas map icon">
                <input id="stationSearch" type="search" placeholder="Search gas stations, brands, or streets" autocomplete="off">
                <button class="icon-button" id="clearSearch" type="button" aria-label="Clear search">&times;</button>
            </div>

            <div class="filter-row">
                <select id="brandFilter" aria-label="Filter by brand">
                    <option>All brands</option>
                    @foreach ($brands as $brand)
                        <option>{{ $brand->name }}</option>
                    @endforeach
                </select>

                <select id="fuelFilter" aria-label="Filter by fuel type">
                    <option>All fuel types</option>
                    @foreach ($fuelTypes as $fuelType)
                        <option>{{ $fuelType->name }}</option>
                    @endforeach
                </select>

                <button id="resetFilters" type="button">Reset</button>
            </div>
        </section>

        <section class="results-panel" aria-label="Station results">
            <div class="results-header">
                <h1>Gas Price Dashboard</h1>
                <button class="collapse-results" id="toggleResults" type="button" aria-label="Minimize Gas Price Tracker card" aria-expanded="true">-</button>
                <p><span id="resultCount">{{ $mapStations->count() }}</span> stations in Zamboanga City. Click a marker or station to view details.</p>
            </div>

            <section class="price-comparison" id="priceComparisonSection" aria-label="Price comparison">
                <div class="comparison-header">
                    <h2>Price Comparison</h2>
                    <button class="collapse-results" id="toggleComparison" type="button" aria-label="Minimize Price Comparison section" aria-expanded="true">-</button>
                </div>
                <div class="comparison-list" id="priceComparison">
                    <p class="empty-note">No station prices are available yet.</p>
                </div>
            </section>

            <section class="stations-section" id="stationsSection" aria-label="Stations">
                <div class="stations-header">
                    <h2>Stations</h2>
                    <button class="collapse-results" id="toggleStations" type="button" aria-label="Minimize Stations section" aria-expanded="true">-</button>
                </div>

                <div class="station-list">
                    @forelse ($mapStations as $station)
                        <button
                            class="station-result"
                            type="button"
                            data-station-result="{{ $station['id'] }}"
                            style="--station-color: {{ $station['brandColor'] }}"
                        >
                            <img class="station-logo" src="{{ $station['brandIcon'] }}" alt="{{ $station['brand'] }} icon">
                            <span>
                                <strong>{{ $station['name'] }}</strong>
                                <span>{{ $station['brand'] }} &middot; {{ $station['address'] }}</span>
                            </span>
                            <span class="lowest-price">
                                View Station Prices
                            </span>
                        </button>
                    @empty
                        <p class="empty-note">No stations are available yet. Add station seed data to populate the map.</p>
                    @endforelse
                </div>
            </section>
        </section>

        <aside class="detail-panel" id="stationDetail" aria-live="polite" aria-label="Station information">
            <div class="detail-hero" id="detailHero">
                <div class="detail-top">
                    <span id="detailBrand">Select a station</span>
                    <button class="detail-close" id="closeDetail" type="button" aria-label="Close station details">&times;</button>
                </div>
                <h2 id="detailName">Station details</h2>
                <p id="detailSubtitle">Click a station marker to view prices and location.</p>
            </div>

            <div class="detail-body">
                <div class="meta-line">
                    <strong>Address</strong>
                    <span id="detailAddress">No station selected</span>
                </div>
                <div class="meta-line">
                    <strong>Coordinates</strong>
                    <span id="detailCoordinates">--</span>
                </div>

                <div class="price-section">
                    <h3>Fuel prices</h3>
                    <div id="detailPrices">
                        <p class="empty-note">Prices will appear here after selecting a station.</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="zoom-controls" aria-label="Map controls">
            <button id="zoomIn" type="button" aria-label="Zoom in">+</button>
            <button id="zoomOut" type="button" aria-label="Zoom out">&minus;</button>
        </div>

        <div class="map-warning" id="mapWarning" hidden>
            The map library or map tiles could not load. Check your internet connection, then refresh the page.
        </div>

        @unless ($databaseReady)
            <div class="database-warning">
                Database is not ready yet. Start MySQL, run the migrations and seeders, then reload this page to show stations on the map.
            </div>
        @endunless
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const stations = @json($mapStations);
        const mapCenter = @json($mapCenter);
        const mapBounds = @json($mapBounds);
        const searchInput = document.querySelector('#stationSearch');
        const clearSearch = document.querySelector('#clearSearch');
        const brandFilter = document.querySelector('#brandFilter');
        const fuelFilter = document.querySelector('#fuelFilter');
        const resetFilters = document.querySelector('#resetFilters');
        const resultsPanel = document.querySelector('.results-panel');
        const toggleResults = document.querySelector('#toggleResults');
        const resultCount = document.querySelector('#resultCount');
        const stationsSection = document.querySelector('#stationsSection');
        const toggleStations = document.querySelector('#toggleStations');
        const detailPanel = document.querySelector('#stationDetail');
        const detailHero = document.querySelector('#detailHero');
        const detailBrand = document.querySelector('#detailBrand');
        const detailName = document.querySelector('#detailName');
        const detailSubtitle = document.querySelector('#detailSubtitle');
        const detailAddress = document.querySelector('#detailAddress');
        const detailCoordinates = document.querySelector('#detailCoordinates');
        const detailPrices = document.querySelector('#detailPrices');
        const closeDetail = document.querySelector('#closeDetail');
        const zoomIn = document.querySelector('#zoomIn');
        const zoomOut = document.querySelector('#zoomOut');
        const mapWarning = document.querySelector('#mapWarning');
        const resultButtons = [...document.querySelectorAll('[data-station-result]')];
        const priceComparisonSection = document.querySelector('#priceComparisonSection');
        const priceComparison = document.querySelector('#priceComparison');
        const toggleComparison = document.querySelector('#toggleComparison');
        const markers = {};
        let map = null;

        function findStation(id) {
            return stations.find((station) => String(station.id) === String(id));
        }

        function markerHtml(station) {
            const label = station.prices.length
                ? station.prices.map((price) => `
                    <div class="marker-price-row">
                        <span>${price.fuel}</span>
                        <strong>&#8369;${price.price}</strong>
                    </div>
                `).join('')
                : `<div class="marker-price-row"><span>${station.brand}</span><strong>No prices</strong></div>`;

            return `
                <div class="station-marker" style="--station-color: ${station.brandColor}; width: 44px; height: 44px;">
                    <img src="${station.brandMapIcon}" alt="${station.brand} icon" style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; padding: 3px;">
                </div>
                <div class="station-marker-label">${label}</div>
            `;
        }

        function initializeMap() {
            if (!window.L) {
                mapWarning.hidden = false;
                return;
            }

            const southWest = [mapBounds.southWest.latitude, mapBounds.southWest.longitude];
            const northEast = [mapBounds.northEast.latitude, mapBounds.northEast.longitude];

            map = L.map('stationMap', {
                center: [mapCenter.latitude, mapCenter.longitude],
                zoom: 13,
                minZoom: 12,
                maxZoom: 18,
                zoomControl: false,
                maxBounds: [southWest, northEast],
                maxBoundsViscosity: 0.85,
            });

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            }).addTo(map);

            stations.forEach((station) => {
                const marker = L.marker([station.latitude, station.longitude], {
                    icon: L.divIcon({
                        className: 'station-div-icon',
                        html: markerHtml(station),
                        iconSize: [52, 68],
                        iconAnchor: [26, 52],
                    }),
                    title: station.name,
                });

                marker.on('click', () => setActiveStation(station.id, true));
                marker.addTo(map);
                markers[station.id] = marker;
            });

            if (stations.length) {
                const bounds = L.latLngBounds(stations.map((station) => [station.latitude, station.longitude]));
                map.fitBounds(bounds.pad(0.35), { maxZoom: 15 });
            }
        }

        function matchesFilters(station) {
            const query = searchInput.value.trim().toLowerCase();
            const brand = brandFilter.value;
            const fuel = fuelFilter.value;
            const matchesSearch = !query || station.searchText.includes(query);
            const matchesBrand = brand === 'All brands' || station.brand === brand;
            const matchesFuel = fuel === 'All fuel types' || station.fuelTypes.includes(fuel);

            return matchesSearch && matchesBrand && matchesFuel;
        }

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function updatePriceComparison(visibleStations) {
            const pricesByFuel = new Map();

            visibleStations.forEach((station) => {
                station.prices.forEach((price) => {
                    const fuelPrices = pricesByFuel.get(price.fuel) || [];
                    fuelPrices.push({
                        fuel: price.fuel,
                        price: Number(price.rawPrice),
                        formattedPrice: price.price,
                        stationId: station.id,
                        station: station.name,
                    });
                    pricesByFuel.set(price.fuel, fuelPrices);
                });
            });

            const comparisonRows = [...pricesByFuel.entries()]
                .sort(([fuelA], [fuelB]) => fuelA.localeCompare(fuelB))
                .map(([fuel, prices]) => {
                    const lowest = prices.reduce((best, current) => current.price < best.price ? current : best);
                    const highest = prices.reduce((best, current) => current.price > best.price ? current : best);

                    return `
                        <button
                            class="comparison-item"
                            type="button"
                            data-comparison-card
                            data-lowest-station="${lowest.stationId}"
                            data-highest-station="${highest.stationId}"
                        >
                            <h3 class="comparison-fuel">${escapeHtml(fuel)}</h3>
                            <div class="comparison-extremes">
                                <div class="comparison-extreme">
                                    <span>Lowest</span>
                                    <strong>&#8369;${lowest.formattedPrice}</strong>
                                    <small title="${escapeHtml(lowest.station)}">${escapeHtml(lowest.station)}</small>
                                </div>
                                <div class="comparison-extreme">
                                    <span>Highest</span>
                                    <strong>&#8369;${highest.formattedPrice}</strong>
                                    <small title="${escapeHtml(highest.station)}">${escapeHtml(highest.station)}</small>
                                </div>
                            </div>
                        </button>
                    `;
                });

            priceComparison.innerHTML = comparisonRows.length
                ? comparisonRows.join('')
                : '<p class="empty-note">No matching station prices are available.</p>';
        }

        function applyFilters() {
            let visibleCount = 0;
            const visibleLocations = [];
            const visibleStations = [];

            stations.forEach((station) => {
                const visible = matchesFilters(station);
                const result = document.querySelector(`[data-station-result="${station.id}"]`);
                const marker = markers[station.id];

                if (result) result.hidden = !visible;

                if (marker && map) {
                    if (visible && !map.hasLayer(marker)) marker.addTo(map);
                    if (!visible && map.hasLayer(marker)) marker.remove();
                }

                if (visible) {
                    visibleCount += 1;
                    visibleLocations.push([station.latitude, station.longitude]);
                    visibleStations.push(station);
                }
            });

            resultCount.textContent = visibleCount;
            updatePriceComparison(visibleStations);

            if (map && visibleLocations.length) {
                const bounds = L.latLngBounds(visibleLocations);
                map.fitBounds(bounds.pad(0.35), { maxZoom: 15, animate: true });
            }
        }

        function setActiveStation(id, moveMap = false) {
            const station = findStation(id);
            if (!station) return;

            const selectedMarker = markers[station.id];
            if (selectedMarker && map && !map.hasLayer(selectedMarker)) {
                selectedMarker.addTo(map);
            }

            document.querySelectorAll('[data-comparison-card]').forEach((card) => card.classList.remove('active'));
            resultButtons.forEach((button) => button.classList.toggle('active', button.dataset.stationResult === String(id)));

            Object.entries(markers).forEach(([markerId, marker]) => {
                const element = marker.getElement();
                const shape = element ? element.querySelector('.station-marker') : null;
                if (shape) {
                    shape.classList.toggle('active', markerId === String(id));
                    shape.classList.remove('lowest-comparison', 'highest-comparison');
                }
            });

            if (moveMap && map) {
                map.panTo([station.latitude, station.longitude], { animate: true });
            }

            detailHero.style.setProperty('--station-color', station.brandColor);
            detailBrand.textContent = station.brand;
            detailName.textContent = station.name;
            detailSubtitle.textContent = station.city;
            detailAddress.textContent = station.address;
            detailCoordinates.textContent = `${station.latitude.toFixed(6)}, ${station.longitude.toFixed(6)}`;

            if (station.prices.length) {
                detailPrices.innerHTML = station.prices.map((price) => `
                    <div class="price-row">
                        <div>
                            ${price.fuel}
                            <span>Updated ${price.effectiveAt}</span>
                        </div>
                        <strong>&#8369;${price.price}</strong>
                    </div>
                `).join('');
            } else {
                detailPrices.innerHTML = '<p class="empty-note">No prices have been recorded for this station yet.</p>';
            }

            detailPanel.classList.add('open');
        }

        function highlightStationsOnMap(stationIds) {
            const selectedIds = [...new Set(stationIds.map(String))];
            const lowestId = String(stationIds[0]);
            const highestId = String(stationIds[1]);
            const selectedLocations = [];

            document.querySelectorAll('[data-comparison-card]').forEach((card) => {
                const cardIds = [card.dataset.lowestStation, card.dataset.highestStation].map(String);
                card.classList.toggle('active', cardIds.every((id) => selectedIds.includes(id)));
            });

            resultButtons.forEach((button) => {
                button.classList.toggle('active', selectedIds.includes(String(button.dataset.stationResult)));
            });

            stations.forEach((station) => {
                const marker = markers[station.id];
                if (!marker || !map) return;

                const isSelected = selectedIds.includes(String(station.id));

                if (isSelected && !map.hasLayer(marker)) marker.addTo(map);
                if (!isSelected && map.hasLayer(marker)) marker.remove();

                const element = marker.getElement();
                const shape = element ? element.querySelector('.station-marker') : null;
                if (shape) {
                    shape.classList.toggle('active', isSelected);
                    shape.classList.toggle('lowest-comparison', isSelected && String(station.id) === lowestId);
                    shape.classList.toggle('highest-comparison', isSelected && String(station.id) === highestId);
                }

                if (isSelected) {
                    selectedLocations.push([station.latitude, station.longitude]);
                }
            });

            if (!map || !selectedLocations.length) return;

            if (selectedLocations.length === 1) {
                map.setView(selectedLocations[0], Math.max(map.getZoom(), 15), { animate: true });
                return;
            }

            map.fitBounds(L.latLngBounds(selectedLocations).pad(0.45), { maxZoom: 15, animate: true });
        }

        resultButtons.forEach((button) => {
            button.addEventListener('click', () => setActiveStation(button.dataset.stationResult, true));
        });

        priceComparison.addEventListener('click', (event) => {
            const card = event.target.closest('[data-comparison-card]');
            if (!card) return;

            highlightStationsOnMap([card.dataset.lowestStation, card.dataset.highestStation]);
        });

        [searchInput, brandFilter, fuelFilter].forEach((control) => {
            control.addEventListener('input', applyFilters);
            control.addEventListener('change', applyFilters);
        });

        clearSearch.addEventListener('click', () => {
            searchInput.value = '';
            applyFilters();
            searchInput.focus();
        });

        resetFilters.addEventListener('click', () => {
            searchInput.value = '';
            brandFilter.value = 'All brands';
            fuelFilter.value = 'All fuel types';
            applyFilters();
        });

        closeDetail.addEventListener('click', () => {
            detailPanel.classList.remove('open');
            resultButtons.forEach((button) => button.classList.remove('active'));

            Object.values(markers).forEach((marker) => {
                const element = marker.getElement();
                const shape = element ? element.querySelector('.station-marker') : null;
                if (shape) shape.classList.remove('active', 'lowest-comparison', 'highest-comparison');
            });
        });

        function setResultsPanelMinimized(isMinimized) {
            resultsPanel.classList.toggle('collapsed', isMinimized);
            toggleResults.textContent = isMinimized ? '+' : '-';
            toggleResults.setAttribute('aria-expanded', String(!isMinimized));
            toggleResults.setAttribute(
                'aria-label',
                isMinimized ? 'Expand Gas Price Tracker card' : 'Minimize Gas Price Tracker card'
            );
        }

        toggleResults.addEventListener('click', () => {
            setResultsPanelMinimized(!resultsPanel.classList.contains('collapsed'));
        });

        function setComparisonMinimized(isMinimized) {
            priceComparisonSection.classList.toggle('collapsed', isMinimized);
            toggleComparison.textContent = isMinimized ? '+' : '-';
            toggleComparison.setAttribute('aria-expanded', String(!isMinimized));
            toggleComparison.setAttribute(
                'aria-label',
                isMinimized ? 'Expand Price Comparison section' : 'Minimize Price Comparison section'
            );
        }

        toggleComparison.addEventListener('click', () => {
            setComparisonMinimized(priceComparisonSection.classList.contains('collapsed') === false);
        });

        function setStationsMinimized(isMinimized) {
            stationsSection.classList.toggle('collapsed', isMinimized);
            toggleStations.textContent = isMinimized ? '+' : '-';
            toggleStations.setAttribute('aria-expanded', String(!isMinimized));
            toggleStations.setAttribute(
                'aria-label',
                isMinimized ? 'Expand Stations section' : 'Minimize Stations section'
            );
        }

        toggleStations.addEventListener('click', () => {
            setStationsMinimized(stationsSection.classList.contains('collapsed') === false);
        });

        zoomIn.addEventListener('click', () => {
            if (map) map.zoomIn();
        });

        zoomOut.addEventListener('click', () => {
            if (map) map.zoomOut();
        });

        initializeMap();
        applyFilters();
    </script>
</body>
</html>
