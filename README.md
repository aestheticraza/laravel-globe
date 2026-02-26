<div align="center">
    <h1>🌍 LaravelGlobe</h1>
    <p><b>Enterprise-Grade, Fully Independent Geographic & Localization Package for Laravel</b></p>
    <br>
</div>

**LaravelGlobe** is not just another typical region database. It is a highly normalized, strictly typed, independently maintained geographic engine for Laravel. Instead of relying on bloated, external GitHub syncs, LaravelGlobe ships with its own proprietary internal JSON datasets for **Countries, States, 150,000+ Cities, Currencies, and Timezones**.

This package gracefully solves geographical overlap anomalies via 5 advanced **Many-to-Many Pivot tables** (e.g., countries with multiple timezones, border-cities with dual currencies).

## 🚀 Key Features

- **Massive Offline Dataset**: 250 Countries, 4,963 States, 148,000+ Cities (Memory-safely split into 10 chunks), 423 Timezones, and 162 Currencies strictly bundled internally!
- **Zero OOM & Performance**: `LaravelGlobeSeeder` imports over 150,000 records without crashing PHP by disabling FK checks, chunking JSON data, and bypassing Eloquent overhead.
- **Advanced M-to-M Relationships**: 
  - `country_currency` (E.g. Zimbabwe accepts USD, ZWL, ZAR).
  - `city_timezone` (Border cities handle daylight saving dynamically).
  - `city_currency` (Border cities like Geneva accept dual currencies CHF + EUR).
  - `state_timezone` (States split across regions like Texas, USA).
- **Hybrid Geospatial Mapping**: Use trigonometric calculations natively via `City::scopeNearby($lat, $lng, $radius)`. It detects MySQL versions under the hood and utilizes `ST_Distance_Sphere` on 5.7+ or falls back to robust Haversine formulas.
- **Config-Driven Toggles**: Disable massive modules natively (like `postal_codes`, `caching`, or `geospatial`) via `config/laravelglobe.php` to save resources.
- **Instant Inheritance**: Attach the elegant `HasLocation` Trait across any Model in your system (User, Employee, Order) to inherit massive geographic scopes.

---

## 💻 Installation

1. **Install the Package via Composer**:
   ```bash
   composer require aestheticraza/laravelglobe
   ```

2. **Run the Install Command**:
   This powerful command auto-publishes properties, bindings, caching setups, and migrations.
   ```bash
   php artisan globe:install
   ```

3. **Migrate the Database**:
   Runs all 12 enterprise-aligned database schematics (Including M-to-M cache columns).
   ```bash
   php artisan migrate
   ```

4. **Seed the Planet**:
   This will bulk insert mappings cleanly across the 10 data city-chunks natively.
   ```bash
   php artisan db:seed --class="Aestheticraza\LaravelGlobe\Database\Seeders\LaravelGlobeSeeder"
   ```

---

## 🛠️ Usage Examples

### 1. The `HasLocation` Eloquent Trait
You can assign geographical behavior to any application model instantly. Add `use HasLocation;` to a `User` or `Order` model.

```php
use Aestheticraza\LaravelGlobe\Traits\HasLocation;

class Order extends Model {
    use HasLocation;
}

$order = Order::find(1);

// Automatic Relations
echo $order->country->name; // "Pakistan"
echo $order->city->name; // "Lahore"
echo $order->getFullAddressAttribute(); 

// Advanced Spatial Scopes: Fetch all pending orders within 50 KM of coordinates
$ordersNearMe = Order::where('status', 'pending')
                    ->withinRadius(31.5204, 74.3587, 50)
                    ->get();
```

### 2. Timezone Resolution & Denormalization
Because storing Timezone ID locally on 1.5 million endpoints is inefficient, LaravelGlobe natively resolves recursive Timezones backward:
```php
$city = City::where('name', 'Lahore')->first();
$timezone = $city->primaryTimezone(); // Crawls City -> State -> Country 
```
*Note: A `CityObserver` auto-caches this resolution back to the MySQL DB upon updates via our fallback caching.*

### 3. CLI Utilities
We treat Data Syncing very seriously.
```bash
# Export local DB updates securely as a JSON backup
php artisan globe:export --only-active

# Upsert changes back in to modify entries WITHOUT altering DB primary keys
php artisan globe:import --mode=upsert --preserve-user-data

# Console graphical dashboard view
php artisan globe:status
```

---

## ⚙️ Configuration Tuning
Toggling major caching and payload limitations is one boolean away in your `config/laravelglobe.php`:

```php
'features' => [
    'enable_postal_codes' => true,
    'enable_border_currencies' => true,
    'enable_geospatial' => true,
],
'performance' => [
    'cache_ttl' => 86400,
    'chunk_size' => 1000,
    'nearby_default_radius' => 10,
],
```

---

## 🛡️ Author & License
**Developed by:** [Ali Raza (@aestheticraza)](https://github.com/aestheticraza)
Proprietary & Independent. Maintained securely for Enterprise scaling apps.
