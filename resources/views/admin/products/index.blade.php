@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin: 0;">📦 Products (ต้นแบบสินค้า)</h2>
        <a href="{{ route('admin.products.create') }}"
           style="background: #C8A951; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 10px rgba(200, 169, 81, 0.2);">
            ➕ New Product
        </a>
    </div>

    {{-- Table Section --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f8f4e8; border-bottom: 2px solid #C8A951;">
                    {{-- Sortable Columns --}}
                    @php
                        $sortLink = function($field, $label) use ($sort, $direction) {
                            $dir = ($sort == $field && $direction == 'asc') ? 'desc' : 'asc';
                            $icon = ($sort == $field) ? ($direction == 'asc' ? '▲' : '▼') : '↕';
                            $url = request()->fullUrlWithQuery(['sort' => $field, 'direction' => $dir]);
                            return "<a href='{$url}' style='text-decoration:none; color:#333; display:flex; align-items:center; gap:5px;'>{$label} <span style='font-size:10px; color:#C8A951;'>{$icon}</span></a>";
                        };
                    @endphp

                    <th style="padding: 15px; width: 80px;">{!! $sortLink('id', 'ID') !!}</th>
                    <th style="padding: 15px; width: 80px;">Image</th>
                    <th style="padding: 15px;">{!! $sortLink('name', 'Product Name') !!}</th>
                    <th style="padding: 15px;">{!! $sortLink('category', 'Category') !!}</th>
                    <th style="padding: 15px; text-align: center;">{!! $sortLink('is_active', 'Active') !!}</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fdfbf5'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 15px; color: #888;">#{{ $product->id }}</td>
                    <td style="padding: 10px;">
                        @if($product->image_url)
                            <img src="{{ asset('images/products/' . $product->image_url) }}"
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #eee;">
                        @else
                            <div style="width: 60px; height: 60px; background: #f5f5f5; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 10px;">No Pic</div>
                        @endif
                    </td>
                    <td style="padding: 15px;">
                        <strong style="color: #333;">{{ $product->name }}</strong>
                    </td>
                    <td style="padding: 15px;">
                        <span style="background: #f0f0f0; padding: 4px 10px; border-radius: 4px; font-size: 13px; color: #666;">
                            {{ $product->category }}
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        @if($product->is_active)
                            <span style="color: #28a745; background: #e9f7ef; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">Yes</span>
                        @else
                            <span style="color: #dc3545; background: #fdf2f2; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">No</span>
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               style="text-decoration: none; color: #007bff; border: 1px solid #007bff; padding: 5px 10px; border-radius: 4px; font-size: 13px;">
                               ✏️ Edit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('ยืนยันการลบสินค้าต้นแบบนี้?')"
                                        style="background: #fff; border: 1px solid #dc3545; color: #dc3545; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                    🗑 ลบ
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $products->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
