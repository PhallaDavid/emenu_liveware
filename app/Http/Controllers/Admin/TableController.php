<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TableController extends Controller
{
    private function getActiveOrder($tableName)
    {
        // Handle "Table 1" vs "1" mismatch
        return \App\Models\Order::where(function($q) use ($tableName) {
                $q->where('table_number', $tableName)
                  ->orWhere('table_number', preg_replace('/^Table\s+/i', '', $tableName));
            })
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->first();
    }

    public function index()
    {
        $tables = \App\Models\Table::all();
        // Check for active orders for each table
        foreach ($tables as $table) {
            $table->current_order = $this->getActiveOrder($table->name);
        }
        
        $categories = \App\Models\Category::with('products')->get();

        return view('admin.tables.index', compact('tables', 'categories'));
    }

    public function toggleStatus(Request $request, \App\Models\Table $table)
    {
        $table->status = $table->status === 'active' ? 'inactive' : 'active';
        $table->save();
        
        return back()->with('success', 'Table status updated.');
    }

    public function checkout(\App\Models\Table $table)
    {
        // Find active order
        $order = $this->getActiveOrder($table->name);

        if ($order) {
            $order->status = 'completed';
            $order->save();
        }

        return back()->with('success', 'Table checked out successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tables,name',
        ]);

        \App\Models\Table::create([
            'name' => $request->name,
            'status' => 'active'
        ]);

        return back()->with('success', 'Table created successfully.');
    }

    public function update(Request $request, \App\Models\Table $table)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tables,name,' . $table->id,
        ]);

        $table->name = $request->name;
        $table->save();

        return back()->with('success', 'Table updated successfully.');
    }

    public function destroy(\App\Models\Table $table)
    {
        $table->delete();
        return back()->with('success', 'Table deleted successfully.');
    }

    public function addItem(Request $request, \App\Models\Table $table)
    {
        $order = $this->getActiveOrder($table->name);

        if (!$order) {
            // Use stripped name ("1" instead of "Table 1") for consistency
             $order = \App\Models\Order::create([
                'table_number' => preg_replace('/^Table\s+/i', '', $table->name),
                'total_price' => 0,
                'status' => 'pending',
                'items' => [],
            ]);
        }

        $items = $order->items ?? [];
        $product = \App\Models\Product::findOrFail($request->product_id);
        
        $found = false;
        foreach ($items as &$item) {
            if ($item['id'] == $product->id) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $items[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image // Optional
            ];
        }

        $order->items = $items;
        $order->total_price = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);
        $order->save();

        return back()->with('success', 'Item added successfully.');
    }
}
