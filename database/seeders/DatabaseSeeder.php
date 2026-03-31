<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $dataFile = database_path('seed-data.json');

        if (file_exists($dataFile)) {
            $this->seedFromJson($dataFile);
        } else {
            // Fallback to manual seeder
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $this->call([
                WdwContentSeeder::class,
            ]);
        }
    }

    private function seedFromJson(string $path): void
    {
        $data = json_decode(file_get_contents($path), true);

        // Users
        foreach ($data['users'] ?? [] as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $user['password'],
                    'email_verified_at' => now(),
                ]
            );
        }

        // Settings
        foreach ($data['settings'] ?? [] as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting->key ?? $setting['key']],
                ['value' => $setting->value ?? $setting['value'] ?? null]
            );
        }

        // Simple table inserts
        $simpleTables = ['pages', 'agents', 'gallery_photos', 'posts', 'entry_offers', 'prize_categories', 'prize_positions'];

        foreach ($simpleTables as $table) {
            if (empty($data[$table])) continue;
            if (DB::table($table)->count() > 0) continue; // Don't duplicate

            foreach ($data[$table] as $row) {
                $row = (array) $row;
                // Remove id to let auto-increment work
                unset($row['id']);
                DB::table($table)->insert($row);
            }
        }
    }
}
