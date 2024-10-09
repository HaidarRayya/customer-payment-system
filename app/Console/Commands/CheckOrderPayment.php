<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class CheckOrderPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-order-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderService = new OrderService();
        $orders = Order::where('status', '', OrderStatus::UNPAID->value);
        foreach ($orders as $order) {
            if (now()->diffInMinutes(CarbonPeriod::create($order->created_at), true) >= 10) {
                $orderService->deleteOrder($order);
            }
        }
    }
}