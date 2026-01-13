<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required',
            'items' => 'required|array',
            'total_price' => 'required|numeric',
            'special_instructions' => 'nullable|string',
            'order_type' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        // Check if there's an existing open order for this table (not yet paid)
        $existingOrder = Order::where('table_number', $validated['table_number'])
            ->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingOrder) {
            // Merge items from new order with existing order
            $existingItems = $existingOrder->items;
            $newItems = $validated['items'];
            
            // Combine items (merge quantities if same product)
            $mergedItems = $existingItems;
            foreach ($newItems as $newItem) {
                $found = false;
                foreach ($mergedItems as &$existingItem) {
                    if ($existingItem['id'] == $newItem['id']) {
                        $existingItem['qty'] += $newItem['qty'];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $mergedItems[] = $newItem;
                }
            }
            
            // Update existing order
            $existingOrder->items = $mergedItems;
            $existingOrder->total_price += $validated['total_price'];
            
            // Reset status to pending since there are new items to prepare
            $existingOrder->status = 'pending';
            
            // Append new instructions if any
            if (!empty($validated['special_instructions'])) {
                $existingOrder->special_instructions = trim(($existingOrder->special_instructions ?? '') . "\n" . $validated['special_instructions']);
            }
            
            // Touch updated_at to show this order was recently modified
            $existingOrder->touch();
            $existingOrder->save();
            
            $response = ['success' => true, 'order_id' => $existingOrder->id, 'merged' => true];

            // If user chose to pay now via PayWay, redirect them to pay the UPDATED total
            if (isset($validated['payment_method']) && $validated['payment_method'] === 'aba_payway') {
                $response['redirect_url'] = route('payment.pay', ['order' => $existingOrder->id]);
            }
            
            return response()->json($response);
        } else {
            // Create new order
            $validated['status'] = 'pending';
            $validated['order_type'] = $validated['order_type'] ?? 'dine_in';
            $validated['payment_method'] = $validated['payment_method'] ?? 'cash';
            $order = Order::create($validated);
            
            $response = ['success' => true, 'order_id' => $order->id, 'merged' => false];
            
            if ($validated['payment_method'] === 'aba_payway') {
                $response['redirect_url'] = route('payment.pay', ['order' => $order->id]);
            }
            
            return response()->json($response);
        }
    }


    public function status(Order $order)
    {
        return response()->json([
            'status' => $order->status,
        ]);
    }

    public function callWaiter(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required',
        ]);

        // Find or create a pending order for this table to mark the waiter call
        $order = Order::where('table_number', $validated['table_number'])
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            $order = Order::create([
                'table_number' => $validated['table_number'],
                'items' => [],
                'total_price' => 0,
                'status' => 'pending',
                'is_calling_waiter' => true
            ]);
        } else {
            $order->update(['is_calling_waiter' => true]);
        }

        return response()->json(['success' => true]);
    }
}
