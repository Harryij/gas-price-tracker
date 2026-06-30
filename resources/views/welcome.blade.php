<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gas Price Tracker</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --ink: #172026;
            --muted: #66727c;
            --line: #dde4ea;
            --panel: #ffffff;
            --soft: #f5f8fa;
            --brand: #0f8b8d;
            --accent: #f6ae2d;
            --danger: #d1495b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #edf3f4;
            color: var(--ink);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page {
            min-height: 100vh;
        }

        .topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--line);
        }

        .topbar-inner,
        .shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        .topbar-inner {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand-lockup {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: var(--brand);
            color: white;
            font-weight: 800;
        }

        .brand-lockup h1 {
            margin: 0;
            font-size: 1.08rem;
            line-height: 1.2;
        }

        .brand-lockup p {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: .86rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .button {
            border: 1px solid var(--line);
            background: #ffffff;
            color: var(--ink);
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: .9rem;
            white-space: nowrap;
        }

        .button.primary {
            background: var(--ink);
            color: #ffffff;
            border-color: var(--ink);
        }

        .shell {
            padding: 28px 0 42px;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(300px, .85fr);
            gap: 18px;
            align-items: stretch;
        }

        .intro,
        .map-panel,
        .toolbar,
        .panel,
        .empty-state {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
        }

        .intro {
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 288px;
        }

        .eyebrow {
            margin: 0 0 12px;
            color: var(--brand);
            font-size: .78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .intro h2 {
            max-width: 720px;
            margin: 0;
            font-size: clamp(2rem, 4vw, 4rem);
            line-height: 1.02;
            letter-spacing: 0;
        }

        .intro-copy {
            max-width: 680px;
            margin: 18px 0 0;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.7;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 26px;
        }

        .stat {
            min-height: 92px;
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--soft);
        }

        .stat strong {
            display: block;
            font-size: 1.55rem;
            line-height: 1;
        }

        .stat span {
            display: block;
            margin-top: 8px;
            color: var(--muted);
            font-size: .86rem;
        }

        .map-panel {
            padding: 16px;
            min-height: 288px;
            display: flex;
            flex-direction: column;
        }

        .map-preview {
            position: relative;
            flex: 1;
            min-height: 230px;
            overflow: hidden;
            border-radius: 8px;
            background:
                linear-gradient(90deg, rgba(255,255,255,.62) 1px, transparent 1px),
                linear-gradient(rgba(255,255,255,.62) 1px, transparent 1px),
                #dbe9e7;
            background-size: 44px 44px;
        }

        .road {
            position: absolute;
            height: 16px;
            width: 130%;
            left: -15%;
            top: 52%;
            border-radius: 999px;
            background: #9fb7b5;
            transform: rotate(-14deg);
        }

        .road.secondary {
            top: 32%;
            transform: rotate(24deg);
            opacity: .75;
        }

        .pin {
            position: absolute;
            width: 18px;
            height: 18px;
            border: 3px solid white;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 10px 24px rgba(23,32,38,.22);
        }

        .pin.one { left: 24%; top: 32%; }
        .pin.two { left: 52%; top: 48%; background: var(--danger); }
        .pin.three { left: 72%; top: 28%; background: var(--brand); }
        .pin.four { left: 38%; top: 68%; background: #2f80ed; }

        .map-caption {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 14px;
            color: var(--muted);
            font-size: .9rem;
        }

        .status-pill {
            color: #0b5f61;
            background: #e3f4f2;
            border: 1px solid #b9dedb;
            padding: 7px 10px;
            border-radius: 999px;
            font-weight: 800;
            font-size: .78rem;
            white-space: nowrap;
        }

        .toolbar {
            margin-top: 18px;
            padding: 16px;
            display: grid;
            grid-template-columns: 1.5fr repeat(2, minmax(160px, .7fr)) auto;
            gap: 12px;
            align-items: end;
        }

        .field label {
            display: block;
            margin-bottom: 7px;
            color: var(--muted);
            font-size: .8rem;
            font-weight: 800;
        }

        .field input,
        .field select {
            width: 100%;
            min-height: 42px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 12px;
            color: var(--ink);
            background: #ffffff;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 18px;
            margin-top: 18px;
            align-items: start;
        }

        .panel {
            overflow: hidden;
        }

        .panel-header {
            padding: 18px 18px 0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .panel-header h3 {
            margin: 0;
            font-size: 1.05rem;
        }

        .panel-header p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: .9rem;
        }

        .station-list {
            display: grid;
            gap: 12px;
            padding: 18px;
        }

        .station-card {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #ffffff;
        }

        .station-card[hidden] {
            display: none;
        }

        .station-name {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .brand-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--brand-color, var(--brand));
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--brand-color, var(--brand)) 18%, transparent);
            flex: 0 0 auto;
        }

        .station-name strong {
            overflow-wrap: anywhere;
        }

        .station-meta {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.45;
        }

        .price-chips {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 8px;
            max-width: 340px;
        }

        .chip {
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 7px 10px;
            background: var(--soft);
            font-size: .82rem;
            white-space: nowrap;
        }

        .chip strong {
            margin-left: 4px;
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .price-table th,
        .price-table td {
            padding: 13px 18px;
            border-top: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
            font-size: .9rem;
        }

        .price-table th {
            color: var(--muted);
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .price-value {
            font-weight: 900;
            color: #0b5f61;
            white-space: nowrap;
        }

        .empty-state {
            margin-top: 18px;
            padding: 18px;
            color: var(--muted);
            line-height: 1.6;
        }

        .empty-state strong {
            display: block;
            color: var(--ink);
            margin-bottom: 4px;
        }

        @media (max-width: 920px) {
            .hero,
            .content-grid,
            .toolbar {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .topbar-inner,
            .shell {
                width: min(100% - 20px, 1180px);
            }

            .topbar-inner,
            .station-card,
            .panel-header {
                align-items: stretch;
                flex-direction: column;
            }

            .topbar-actions,
            .station-card {
                display: grid;
                grid-template-columns: 1fr;
            }

            .button {
                width: 100%;
                text-align: center;
            }

            .intro {
                padding: 20px;
            }

            .price-chips {
                justify-content: flex-start;
            }

            .price-table {
                min-width: 520px;
            }

            .table-scroll {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="topbar">
            <div class="topbar-inner">
                <a class="brand-lockup" href="/">
                    <span class="brand-mark">G</span>
                    <span>
                        <h1>Gas Price Tracker</h1>
                        <p>Zamboanga City station prices</p>
                    </span>
                </a>

                <div class="topbar-actions">
                    <a class="button" href="#stations">Browse stations</a>
                    <a class="button primary" href="#prices">Latest prices</a>
                </div>
            </div>
        </header>

        <main class="shell">
            <section class="hero" aria-labelledby="page-title">
                <div class="intro">
                    <div>
                        <p class="eyebrow">Local fuel watch</p>
                        <h2 id="page-title">Compare nearby gas stations before you drive.</h2>
                        <p class="intro-copy">
                            A simple starting dashboard for checking stations, fuel types, and recent prices. The map area is reserved so the next version can become interactive without changing the whole layout.
                        </p>
                    </div>

                    <div class="stats" aria-label="Tracker summary">
                        <div class="stat">
                            <strong>{{ $stations->count() }}</strong>
                            <span>Stations listed</span>
                        </div>
                        <div class="stat">
                            <strong>{{ $brands->count() }}</strong>
                            <span>Fuel brands</span>
                        </div>
                        <div class="stat">
                            <strong>
                                @if ($priceRange['min'] && $priceRange['max'])
                                    ₱{{ number_format($priceRange['min'], 2) }}-₱{{ number_format($priceRange['max'], 2) }}
                                @else
                                    --
                                @endif
                            </strong>
                            <span>Recorded price range</span>
                        </div>
                    </div>
                </div>

                <aside class="map-panel" aria-label="Map preview">
                    <div class="map-preview">
                        <span class="road"></span>
                        <span class="road secondary"></span>
                        <span class="pin one"></span>
                        <span class="pin two"></span>
                        <span class="pin three"></span>
                        <span class="pin four"></span>
                    </div>
                    <div class="map-caption">
                        <span>Map preview placeholder</span>
                        <span class="status-pill">Map-ready layout</span>
                    </div>
                </aside>
            </section>

            <section class="toolbar" aria-label="Station filters">
                <div class="field">
                    <label for="search">Search station or address</label>
                    <input id="search" type="search" placeholder="Try Veterans Avenue">
                </div>

                <div class="field">
                    <label for="brand">Brand</label>
                    <select id="brand">
                        <option>All brands</option>
                        @foreach ($brands as $brand)
                            <option>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="fuel">Fuel type</label>
                    <select id="fuel">
                        <option>All fuel types</option>
                        @foreach ($fuelTypes as $fuelType)
                            <option>{{ $fuelType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="button primary" type="button">Apply filters</button>
            </section>

            @unless ($databaseReady)
                <div class="empty-state">
                    <strong>Database is not ready yet.</strong>
                    Once MySQL is running and migrations are seeded, this page will automatically show your stations, brands, and prices.
                </div>
            @endunless

            <section class="content-grid">
                <div class="panel" id="stations">
                    <div class="panel-header">
                        <div>
                            <h3>Stations</h3>
                            <p>Nearby locations prepared for the future map view.</p>
                        </div>
                    </div>

                    <div class="station-list">
                        @forelse ($stations as $station)
                            <article
                                class="station-card"
                                data-station-card
                                data-search="{{ Str::lower($station->name.' '.$station->address.' '.$station->city.' '.($station->brand->name ?? '')) }}"
                                data-brand="{{ $station->brand->name ?? '' }}"
                                data-fuels="{{ $station->fuelPrices->pluck('fuelType.name')->filter()->implode('|') }}"
                            >
                                <div>
                                    <div class="station-name">
                                        <span class="brand-dot" style="--brand-color: {{ $station->brand->color ?? '#0f8b8d' }}"></span>
                                        <strong>{{ $station->name }}</strong>
                                    </div>
                                    <p class="station-meta">
                                        {{ $station->brand->name ?? 'Unknown brand' }} · {{ $station->address }} · {{ $station->city }}
                                    </p>
                                </div>

                                <div class="price-chips" aria-label="{{ $station->name }} prices">
                                    @forelse ($station->fuelPrices->sortBy('fuelType.name')->take(3) as $price)
                                        <span class="chip">
                                            {{ $price->fuelType->name ?? 'Fuel' }}
                                            <strong>₱{{ number_format($price->price, 2) }}</strong>
                                        </span>
                                    @empty
                                        <span class="chip">No prices yet</span>
                                    @endforelse
                                </div>
                            </article>
                        @empty
                            <div class="empty-state">
                                <strong>No stations to show yet.</strong>
                                Add seed data or create station records to populate this list.
                            </div>
                        @endforelse
                    </div>
                </div>

                <aside class="panel" id="prices">
                    <div class="panel-header">
                        <div>
                            <h3>Latest prices</h3>
                            <p>Most recent recorded fuel prices.</p>
                        </div>
                    </div>

                    <div class="table-scroll">
                        <table class="price-table">
                            <thead>
                                <tr>
                                    <th>Station</th>
                                    <th>Fuel</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestPrices as $price)
                                    <tr>
                                        <td>
                                            <strong>{{ $price->station->name ?? 'Station' }}</strong><br>
                                            <span style="color: var(--muted);">{{ $price->station->brand->name ?? 'Brand' }}</span>
                                        </td>
                                        <td>{{ $price->fuelType->name ?? 'Fuel' }}</td>
                                        <td class="price-value">₱{{ number_format($price->price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No price records yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </aside>
            </section>
        </main>
    </div>

    <script>
        const searchInput = document.querySelector('#search');
        const brandSelect = document.querySelector('#brand');
        const fuelSelect = document.querySelector('#fuel');
        const filterButton = document.querySelector('.toolbar .button');
        const stationCards = [...document.querySelectorAll('[data-station-card]')];

        function applyStationFilters() {
            const query = searchInput.value.trim().toLowerCase();
            const brand = brandSelect.value;
            const fuel = fuelSelect.value;

            stationCards.forEach((card) => {
                const matchesSearch = !query || card.dataset.search.includes(query);
                const matchesBrand = brand === 'All brands' || card.dataset.brand === brand;
                const fuels = card.dataset.fuels ? card.dataset.fuels.split('|') : [];
                const matchesFuel = fuel === 'All fuel types' || fuels.includes(fuel);

                card.hidden = !(matchesSearch && matchesBrand && matchesFuel);
            });
        }

        searchInput.addEventListener('input', applyStationFilters);
        brandSelect.addEventListener('change', applyStationFilters);
        fuelSelect.addEventListener('change', applyStationFilters);
        filterButton.addEventListener('click', applyStationFilters);
    </script>
</body>
</html>
