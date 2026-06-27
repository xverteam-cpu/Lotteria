<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Investment;

$users = User::all();
foreach ($users as $user) {
    echo "USER: {$user->id} {$user->name} balance={$user->balance}\n";
    $inv = Investment::where('user_id', $user->id)->orderByDesc('created_at')->get();
    foreach ($inv as $i) {
        echo "  INV: {$i->id} status={$i->status} payment_method={$i->payment_method} amount={$i->amount} starts_at=" . ($i->starts_at ? $i->starts_at->toDateTimeString() : 'null') . " interest_days_credited={$i->interest_days_credited} last_interest_accrued_at=" . ($i->last_interest_accrued_at ? $i->last_interest_accrued_at->toDateTimeString() : 'null') . "\n";
    }
}
