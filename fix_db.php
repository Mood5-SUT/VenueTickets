<?php
$files = [
    "2026_05_07_193917_create_venues_table.php",
    "2026_05_07_193945_create_seat_maps_table.php",
    "2026_05_07_194010_create_zones_table.php",
    "2026_05_07_194032_create_seats_table.php",
    "2026_05_07_194051_create_pricing_tiers_table.php",
    "2026_05_07_194113_create_orders_table.php",
    "2026_05_07_194143_create_tickets_table.php",
    "2026_05_07_194203_create_resale_listings_table.php",
    "2026_05_07_194231_create_promo_codes_table.php",
    "2026_05_07_194306_create_promo_code_usage_table.php",
    "2026_05_07_194332_create_organizer_details_table.php",
    "2026_05_07_194354_create_payouts_table.php",
    "2026_05_07_194413_create_user_bans_table.php",
    "2026_05_07_194435_create_audit_logs_table.php",
    "2026_05_07_194455_create_failed_payments_table.php",
    "2026_05_07_194516_create_scan_logs_table.php",
    "2026_05_07_194537_create_platform_settings_table.php"
];

foreach ($files as $file) {
    $path = __DIR__ . "/database/migrations/" . $file;
    if (file_exists($path)) {
        unlink($path);
        echo "Deleted: $file\n";
    }
}

echo "Running migrate:fresh...\n";
passthru("php artisan migrate:fresh --seed");
