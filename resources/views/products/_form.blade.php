<div class="mb-3">
    <label class="form-label">Product Type</label>
    <select name="product_type_id" class="form-select" required>
        <option value="">-- Select --</option>
        @foreach ($productTypes as $type)
            <option value="{{ $type->id }}"
                @selected(old('product_type_id', $product->product_type_id ?? '') == $type->id)>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
    @error('product_type_id') <div class="text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">Product ID (10-digit, from manufacturing unit)</label>
    <input type="text" name="product_id" maxlength="10" class="form-control"
           value="{{ old('product_id', $product->product_id ?? '') }}" required>
    @error('product_id') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Product Code (4 chars)</label>
        <input type="text" name="product_code" maxlength="4" class="form-control"
               value="{{ old('product_code', $product->product_code ?? '') }}" required>
        @error('product_code') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Revision (2 chars)</label>
        <input type="text" name="revision" maxlength="2" class="form-control"
               value="{{ old('revision', $product->revision ?? '') }}" required>
        @error('revision') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Manufacturing Number (4 chars)</label>
        <input type="text" name="manufacturing_number" maxlength="4" class="form-control"
               value="{{ old('manufacturing_number', $product->manufacturing_number ?? '') }}" required>
        @error('manufacturing_number') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select" required>
        @foreach (['Active', 'Under Test', 'Failed'] as $option)
            <option value="{{ $option }}"
                @selected(old('status', $product->status ?? 'Active') == $option)>
                {{ $option }}
            </option>
        @endforeach
    </select>
    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
</div>

