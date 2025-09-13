<?php

namespace Database\Seeders;

use App\Models\Fire;
use App\Models\Flood;
use App\Models\Garbage;
use App\Models\Traffic;
use App\Models\Tsunami;
use App\Models\User;
use App\Models\Utility;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // Create admin user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $user->assignRole('admin');

        // Seed all models
        $this->seedFire();
        $this->seedFlood();
        $this->seedTsunami();
        $this->seedGarbage();
        $this->seedTraffic();
        $this->seedUtility();
    }

    private function seedFire(): void
    {
        $fires = [
            [
                'name' => 'Quezon City Residential Fire',
                'description' => 'Fire incident at a residential compound in Barangay Commonwealth',
                'status' => 'contained',
                'date' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Makati Commercial Building Fire',
                'description' => 'Small fire at a commercial building in Makati CBD',
                'status' => 'extinguished',
                'date' => Carbon::now()->subDays(5),
            ],
            [
                'name' => 'Pasig Industrial Fire',
                'description' => 'Factory fire in Pasig industrial area',
                'status' => 'active',
                'date' => Carbon::now()->subHours(3),
            ],
            [
                'name' => 'Manila Shanty Fire',
                'description' => 'Fire incident in informal settlement area',
                'status' => 'under_investigation',
                'date' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($fires as $fire) {
            Fire::create($fire);
        }
    }

    private function seedFlood(): void
    {
        $floods = [
            [
                'name' => 'Marikina River Overflow',
                'description' => 'Heavy rainfall caused Marikina River to overflow affecting nearby communities',
                'severity' => 'high',
                'date' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Quezon City Flash Flood',
                'description' => 'Flash flooding in low-lying areas of QC due to blocked drainage',
                'severity' => 'medium',
                'date' => Carbon::now()->subHours(6),
            ],
            [
                'name' => 'Pasig City Flood Warning',
                'description' => 'Rising water levels in Pasig River tributaries',
                'severity' => 'low',
                'date' => Carbon::now()->subDays(3),
            ],
            [
                'name' => 'Manila Bay Storm Surge',
                'description' => 'Storm surge affecting coastal barangays in Manila',
                'severity' => 'high',
                'date' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($floods as $flood) {
            Flood::create($flood);
        }
    }

    private function seedTsunami(): void
    {
        $tsunamis = [
            [
                'name' => 'Pacific Ocean Tsunami Alert',
                'description' => 'Tsunami warning issued for Metro Manila coastal areas',
                'severity' => 'medium',
                'date' => Carbon::now()->subDays(10),
            ],
            [
                'name' => 'Manila Bay Tsunami Drill',
                'description' => 'Scheduled tsunami evacuation drill for coastal communities',
                'severity' => 'low',
                'date' => Carbon::now()->subDays(15),
            ],
        ];

        foreach ($tsunamis as $tsunami) {
            Tsunami::create($tsunami);
        }
    }

    private function seedGarbage(): void
    {
        $garbageReports = [
            [
                'name' => 'EDSA Littering Report',
                'description' => 'Heavy littering observed along EDSA Ortigas section',
                'status' => 'pending',
                'time' => Carbon::now()->subHours(2),
            ],
            [
                'name' => 'Marikina River Cleanup',
                'description' => 'Large amount of debris found in Marikina River after heavy rains',
                'status' => 'in_progress',
                'time' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'QC Dump Site Issue',
                'description' => 'Overflow at Quezon City dump site needs immediate attention',
                'status' => 'urgent',
                'time' => Carbon::now()->subHours(4),
            ],
            [
                'name' => 'BGC Waste Management',
                'description' => 'Regular waste collection in BGC area completed',
                'status' => 'completed',
                'time' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($garbageReports as $garbage) {
            Garbage::create($garbage);
        }
    }

    private function seedTraffic(): void
    {
        $trafficReports = [
            [
                'name' => 'EDSA Northbound Heavy Traffic',
                'description' => 'Severe traffic congestion from Ayala to Ortigas',
                'reason' => 'vehicle_breakdown',
                'date' => Carbon::now()->subMinutes(15),
            ],
            [
                'name' => 'C5 Road Accident',
                'description' => 'Multi-vehicle accident causing lane closure',
                'reason' => 'accident',
                'date' => Carbon::now()->subHours(1),
            ],
            [
                'name' => 'Commonwealth Ave Construction',
                'description' => 'Road construction causing traffic buildup',
                'reason' => 'construction',
                'date' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Katipunan Ave Flooding',
                'description' => 'Road flooding causing traffic diversion',
                'reason' => 'weather',
                'date' => Carbon::now()->subHours(3),
            ],
            [
                'name' => 'BGC Event Traffic',
                'description' => 'Heavy traffic due to major event in BGC',
                'reason' => 'event',
                'date' => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($trafficReports as $traffic) {
            Traffic::create($traffic);
        }
    }

    private function seedUtility(): void
    {
        $utilities = [
            [
                'name' => 'Maynilad Water Interruption',
                'description' => 'Scheduled water service interruption for pipe maintenance',
                'status' => 'scheduled',
                'date' => Carbon::now()->addDays(1),
            ],
            [
                'name' => 'Manila Water Leak Repair',
                'description' => 'Emergency repair of major water line leak in Taguig',
                'status' => 'emergency',
                'date' => Carbon::now()->subHours(2),
            ],
            [
                'name' => 'PLDT Internet Outage',
                'description' => 'Internet service disruption in Makati area',
                'status' => 'ongoing',
                'date' => Carbon::now()->subMinutes(45),
            ],
            [
                'name' => 'Globe Network Maintenance',
                'description' => 'Scheduled network maintenance affecting mobile services',
                'status' => 'completed',
                'date' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Gas Pipeline Inspection',
                'description' => 'Routine gas pipeline safety inspection in QC',
                'status' => 'in_progress',
                'date' => Carbon::now()->subHours(4),
            ],
        ];

        foreach ($utilities as $utility) {
            Utility::create($utility);
        }
    }
}
