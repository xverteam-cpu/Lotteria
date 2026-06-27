<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Investment;
use Illuminate\Support\Carbon;

$investments = Investment::where('status', 'approved')->get();
if ($investments->isEmpty()) {
    echo "No approved investments found.\n";
}
foreach ($investments as $i) {
    echo "ID: {$i->id}\n";
    echo "  status: {$i->status}\n";
    echo "  starts_at: " . ($i->starts_at ? $i->starts_at->toDateTimeString() : 'null') . "\n";
    echo "  approved_at: " . ($i->approved_at ? $i->approved_at->toDateTimeString() : 'null') . "\n";
    echo "  last_interest_accrued_at: " . ($i->last_interest_accrued_at ? $i->last_interest_accrued_at->toDateTimeString() : 'null') . "\n";
    echo "  interest_days_credited: {$i->interest_days_credited}\n";
    echo "  amount: {$i->amount}\n";
    echo "  daily_interest_rate: {$i->daily_interest_rate}\n";
    echo "  duration_days: {$i->duration_days}\n";
    echo "  elapsedInterestDays: {$i->elapsedInterestDays()}\n";
    echo "  dailyInterestAmount: {$i->dailyInterestAmount()}\n";
    echo "  earnedInterest: {$i->earnedInterest()}\n";
    echo "  now: " . Carbon::now()->toDateTimeString() . "\n";
    echo "---\n";
}
