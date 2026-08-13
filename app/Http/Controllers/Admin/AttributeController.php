<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attribute\StoreAttributeRequest;
use App\Http\Requests\Admin\Attribute\UpdateAttributeRequest;
use App\Http\Requests\Admin\Attribute\StoreAttributeValueRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->latest()->paginate(10);
        return view('admin.attribute.index', compact('attributes'));
    }

    public function store(StoreAttributeRequest $request)
    {
        Attribute::create([
            'name' => $request->name,
            'is_variant' => $request->has('is_variant'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Thêm thuộc tính thành công!');
    }

    public function update(UpdateAttributeRequest $request, $id)
    {
        $attribute = Attribute::findOrFail($id);

        $attribute->update([
            'name' => $request->name,
            'is_variant' => $request->has('is_variant'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Cập nhật thuộc tính thành công!');
    }

    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Đã chuyển thuộc tính vào thùng rác!');
    }

    // Attribute Values Management
    public function storeValue(StoreAttributeValueRequest $request, $attributeId)
    {
        $attribute = Attribute::findOrFail($attributeId);

        AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value' => $request->value,
            'color_code' => $request->color_code,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Thêm giá trị thuộc tính thành công!');
    }

    public function destroyValue($id)
    {
        $val = AttributeValue::findOrFail($id);
        $val->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Đã xóa giá trị thuộc tính!');
    }

    // Trash & Restore
    public function trash()
    {
        $trashedAttributes = Attribute::onlyTrashed()->with('values')->latest()->paginate(10);
        return view('admin.attribute.trash', compact('trashedAttributes'));
    }

    public function restore($id)
    {
        $attribute = Attribute::onlyTrashed()->findOrFail($id);
        $attribute->restore();

        return redirect()->route('admin.attributes.trash')
            ->with('success', 'Đã khôi phục thuộc tính!');
    }

    public function forceDelete($id)
    {
        $attribute = Attribute::onlyTrashed()->findOrFail($id);
        $attribute->forceDelete();

        return redirect()->route('admin.attributes.trash')
            ->with('success', 'Đã xóa vĩnh viễn thuộc tính!');
    }
}
