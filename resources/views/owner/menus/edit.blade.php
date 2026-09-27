@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">
        <h1 class="text-3xl font-bold text-[#2c1f16]">Edit Menu</h1>
    </div>

    <form action="{{ route('owner.menus.update', $menu) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-[#2c1f16] mb-6">Menu Details</h2>
            <div class="grid grid-cols-1 xl:grid-cols-[160px_1fr] gap-6">
                <div x-data="{ preview: '{{ $menu->image ? asset('storage/' . $menu->image) : '' }}' }">
                    <label class="block text-sm font-medium text-[#5c4432] mb-2">Menu Image</label>
                    <div class="w-36 h-36 rounded-3xl border border-dashed border-black/10 bg-black/[0.02] overflow-hidden">
                        <template x-if="preview"><img :src="preview" class="w-full h-full object-cover"></template>
                        <template x-if="!preview"><div class="w-full h-full flex items-center justify-center"><span class="text-xs text-[#5c4432] text-center px-3">Upload</span></div></template>
                    </div>
                    <input type="file" name="image" accept="image/*" @change="const file = $event.target.files[0]; if(file) preview = URL.createObjectURL(file);" class="w-full mt-3 text-sm">
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-medium text-[#5c4432]">Menu Name</label>
                            <input type="text" name="name" value="{{ old('name', $menu->name) }}" class="w-full mt-2 rounded-2xl border border-black/10 px-4 h-11" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#5c4432]">Category</label>
                            <select name="category" class="w-full mt-2 rounded-2xl border border-black/10 px-4 h-11" required>
                                @foreach(['Coffee', 'Non-Coffee', 'Signature', 'Food'] as $cat)
                                    <option value="{{ $cat }}" {{ $menu->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#5c4432]">Selling Price (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', rtrim(rtrim($menu->price, '0'), '.')) }}" class="w-full mt-2 rounded-2xl border border-black/10 px-4 h-11" required>
                    </div>
                    <div>
                    <label class="text-sm font-medium text-[#5c4432]">Masa Simpan (Hari)</label>
                    <input type="number" name="expires_in_days" value="{{ old('expires_in_days', $menu->expires_in_days ?? 1) }}" min="1" class="w-full mt-2 rounded-2xl border border-black/10 px-4 h-11" required>
                    <p class="text-xs text-[#5c4432] mt-1">Berapa hari menu ini bertahan setelah diproduksi?</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#5c4432]">Description (Optional)</label>
                        <textarea name="description" rows="3" class="w-full mt-2 rounded-2xl border border-black/10 px-4 py-3">{{ old('description', $menu->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6" 
             x-data="{ 
                ingredients: [
                    @foreach($menu->ingredients as $ingredient)
                        { id: {{ $ingredient->id }}, raw_material_id: '{{ $ingredient->id }}', quantity: '{{ rtrim(rtrim($ingredient->pivot->quantity, '0'), '.') }}' },
                    @endforeach
                    @if($menu->ingredients->isEmpty())
                        { id: Date.now(), raw_material_id: '', quantity: '' }
                    @endif
                ],
                addIngredient() { this.ingredients.push({ id: Date.now(), raw_material_id: '', quantity: '' }); },
                removeIngredient(index) { this.ingredients.splice(index, 1); }
             }">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-[#2c1f16]">Recipe / Ingredients</h2>
                </div>
                <button type="button" @click="addIngredient()" class="inline-flex items-center h-9 px-4 rounded-xl bg-black/[0.04] text-[#2c1f16] text-sm font-medium hover:bg-black/[0.08] transition">
                    + Add Material
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(item, index) in ingredients" :key="item.id">
                    <div class="flex items-end gap-4 p-4 rounded-2xl border border-black/10 bg-black/[0.01]">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-[#5c4432]">Raw Material</label>
                            <select :name="`ingredients[${index}][raw_material_id]`" x-model="item.raw_material_id" class="w-full mt-2 rounded-xl border border-black/10 px-4 h-11" required>
                                <option value="">Select Material...</option>
                                @foreach($rawMaterials as $rm)
                                    <option value="{{ $rm->id }}">{{ $rm->name }} (in {{ $rm->base_unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-1/3">
                            <label class="text-sm font-medium text-[#5c4432]">Quantity</label>
                            <input type="number" step="0.01" :name="`ingredients[${index}][quantity]`" x-model="item.quantity" class="w-full mt-2 rounded-xl border border-black/10 px-4 h-11" required>
                        </div>
                        <button type="button" @click="removeIngredient(index)" x-show="ingredients.length > 1" class="h-11 w-11 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition shrink-0">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('owner.menus.index') }}" class="inline-flex items-center justify-center h-11 px-5 rounded-2xl border border-black/10 text-[#2c1f16] font-medium hover:bg-black/[0.03] transition">Cancel</a>
            <button type="submit" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#2c1f16] text-white font-medium hover:opacity-90 transition">Update Menu</button>
        </div>
    </form>
</div>
@endsection