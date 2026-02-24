@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">💎 Stock Inventory (คลังสินค้าจริง)</h2>
        <a href="{{ route('admin.stock-items.create') }}"
           style="background: #333; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            ➕ New Stock Item
        </a>
    </div>

    {{-- Filter Bar --}}
    <div style="margin-bottom: 25px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #eee;">
        <form action="{{ route('admin.stock-items.index') }}" method="GET" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อสินค้า / Serial No..."
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>

            <select name="status" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; min-width: 150px;">
                <option value="">-- สถานะทั้งหมด --</option>
                <option value="IN_STOCK" {{ request('status') == 'IN_STOCK' ? 'selected' : '' }}>🟢 In Stock</option>
                <option value="SOLD" {{ request('status') == 'SOLD' ? 'selected' : '' }}>🔴 Sold</option>
            </select>

            <button type="submit" style="background: #C8A951; color: white; border: none; padding: 10px 25px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                🔍 Filter
            </button>
            <a href="{{ route('admin.stock-items.index') }}" style="text-decoration: none; color: #666; font-size: 13px;">ล้างค่า</a>
        </form>
    </div>

    {{-- Table Section --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                @php
                    $sortLink = function($field, $label) use ($sort, $direction) {
                        $dir = ($sort == $field && $direction == 'asc') ? 'desc' : 'asc';
                        $icon = ($sort == $field) ? ($direction == 'asc' ? '▲' : '▼') : '↕';
                        $url = request()->fullUrlWithQuery(['sort' => $field, 'direction' => $dir]);
                        return "<a href='{$url}' style='text-decoration:none; color:#333; display:flex; align-items:center; gap:3px;'>{$label} <span style='font-size:10px; color:#C8A951;'>{$icon}</span></a>";
                    };
                @endphp
                <tr style="background-color: #f8f4e8; border-bottom: 2px solid #C8A951;">
                    <th style="padding: 15px; width: 50px;">{!! $sortLink('id', 'ID') !!}</th>
                    <th style="padding: 15px;">Product / Pic</th>
                    <th style="padding: 15px;">{!! $sortLink('serial_no', 'Serial') !!}</th>
                    <th style="padding: 15px; text-align: center;">{!! $sortLink('ring_size', 'Size') !!}</th>
                    <th style="padding: 15px; text-align: right;">{!! $sortLink('gold_weight_actual', 'Gold(g)') !!}</th>
                    <th style="padding: 15px; background: #fffcf0; text-align: right;">Gold@Make</th>
                    <th style="padding: 15px; background: #fffcf0; text-align: right; color: #C8A951;">Gold Today</th>
                    <th style="padding: 15px; text-align: right;">Total Cost</th>
                    <th style="padding: 15px; text-align: right;">Δ Gold</th>
                    <th style="padding: 15px; text-align: center;">{!! $sortLink('status', 'Status') !!}</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockItems as $item)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fdfbf5'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 12px; text-align: center; color: #999;">{{ $item->id }}</td>
                    <td style="padding: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            @if($item->product && $item->product->image_url)
                                <img src="{{ asset('images/products/' . $item->product->image_url) }}" width="40" height="40" style="border-radius: 6px; object-fit: cover; border: 1px solid #eee;">
                            @endif
                            <div>
                                <strong style="display: block; color: #333;">{{ $item->product->name }}</strong>
                                <small style="color: #888;">{{ Str::limit($item->diamond_detail, 25) }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px;"><code>{{ $item->serial_no ?? '-' }}</code></td>
                    <td style="padding: 12px; text-align: center;">{{ $item->ring_size ?? '-' }}</td>
                    <td style="padding: 12px; text-align: right; font-weight: bold;">{{ number_format($item->gold_weight_actual, 2) }}</td>

                    <td style="padding: 12px; text-align: right; background: #fffcf0; color: #777;">{{ number_format($item->gold_price_at_make, 0) }}</td>
                    <td style="padding: 12px; text-align: right; background: #fffcf0; font-weight: bold; color: #C8A951;">{{ number_format($currentGoldPrice, 0) }}</td>

                    <td style="padding: 12px; text-align: right; font-weight: bold;">{{ number_format($item->total_cost, 2) }}</td>
                    <td style="padding: 12px; text-align: right;">
                        <span style="color: {{ $item->gold_difference >= 0 ? '#28a745' : '#dc3545' }}; font-weight: bold;">
                            {{ $item->gold_difference >= 0 ? '+' : '' }}{{ number_format($item->gold_difference, 2) }}
                        </span>
                    </td>

                    <td style="padding: 12px; text-align: center;">
                        <span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; background: {{ $item->status == 'IN_STOCK' ? '#e9f7ef' : '#fdf2f2' }}; color: {{ $item->status == 'IN_STOCK' ? '#1e8449' : '#a94442' }};">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <a href="{{ route('admin.stock-items.edit', $item->id) }}" style="text-decoration: none; font-size: 16px;">✏️</a>
                            <form action="{{ route('admin.stock-items.destroy', $item->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('ลบรายการนี้ใช่หรือไม่?')" style="border: none; background: none; cursor: pointer; font-size: 16px;">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $stockItems->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
