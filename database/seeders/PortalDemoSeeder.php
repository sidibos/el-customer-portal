<?php

namespace Database\Seeders;

use App\Models\BillingPreference;
use App\Models\Consumption;
use App\Models\Customer;
use App\Models\Meter;
use App\Models\MeterReading;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PortalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCustomer(
            customerName: 'Acme Energy Ltd',
            primaryEmail: 'primary@example.com',
            authorisedEmail: 'authorised@example.com',
            lastBill: 123.45,
            outstanding: 67.89
        );

        $this->seedCustomer(
            customerName: 'Beta Manufacturing PLC',
            primaryEmail: 'beta.primary@example.com',
            authorisedEmail: 'beta.authorised@example.com',
            lastBill: 987.65,
            outstanding: 120.00
        );
    }

    private function seedCustomer(
        string $customerName,
        string $primaryEmail,
        string $authorisedEmail,
        float $lastBill,
        float $outstanding
    ): void {
        $customer = Customer::create([
            'name' => $customerName,
            'last_bill_amount' => $lastBill,
            'outstanding_balance' => $outstanding,
        ]);

        BillingPreference::create([
            'customer_id' => $customer->id,
            'format' => 'PDF',
        ]);

        User::create([
            'name' => 'Primary Contact',
            'email' => $primaryEmail,
            'password' => Hash::make('password'),
            'customer_id' => $customer->id,
            'type' => 'primary',
            'phone' => '+441234567890',
        ]);

        User::create([
            'name' => 'Authorised User',
            'email' => $authorisedEmail,
            'password' => Hash::make('password'),
            'customer_id' => $customer->id,
            'type' => 'authorised',
            'phone' => '+441234567891',
        ]);

        // Two sites per customer
        $site1 = Site::create([
            'customer_id' => $customer->id,
            'name' => 'London HQ',
            'address' => '1 Example Street, London',
        ]);

        $site2 = Site::create([
            'customer_id' => $customer->id,
            'name' => 'Manchester Warehouse',
            'address' => '99 Warehouse Road, Manchester',
        ]);

        // Each site: one gas + one electric meter
        $this->seedSiteMeters($site1);
        $this->seedSiteMeters($site2);
    }

    private function seedSiteMeters(Site $site): void
    {
        $meters = [
            ['type' => 'electric', 'prefix' => 'ELEC'],
            ['type' => 'gas', 'prefix' => 'GAS'],
        ];

        foreach ($meters as $m) {
            $meter = Meter::create([
                'site_id' => $site->id,
                'meter_identifier' => sprintf('%s-%d-%s', $m['prefix'], $site->id, strtoupper(str()->random(6))),
                'type' => $m['type'],
                'is_active' => true,
            ]);

            // Seed some readings (latest reading is newest read_at)
            $now = Carbon::now();
            MeterReading::create([
                'meter_id' => $meter->id,
                'reading' => 1000.000,
                'read_at' => $now->copy()->subDays(30),
            ]);

            MeterReading::create([
                'meter_id' => $meter->id,
                'reading' => 1100.250,
                'read_at' => $now->copy()->subDays(7),
            ]);

            MeterReading::create([
                'meter_id' => $meter->id,
                'reading' => 1125.500,
                'read_at' => $now->copy()->subDays(1), // latest
            ]);

            // Seed last 6 months consumption (month = first day of month)
            $this->seedLastSixMonthsConsumption($meter);
        }
    }

    private function seedLastSixMonthsConsumption(Meter $meter): void
    {
        $start = Carbon::now()->startOfMonth(); // current month start

        for ($i = 0; $i < 6; $i++) {
            $month = $start->copy()->subMonths($i)->toDateString();

            // simple deterministic-ish usage by type + month offset
            $base = $meter->type === 'electric' ? 350 : 120;
            $usage = $base + ($i * 10) + ($meter->id % 7);

            Consumption::updateOrCreate(
                ['meter_id' => $meter->id, 'month' => $month],
                ['usage' => $usage]
            );
        }
    }
}