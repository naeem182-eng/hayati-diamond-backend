<label>Name</label><br>
<input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"><br><br>

<label>Category</label><br>
<input type="text" name="category" value="{{ old('category', $product->category ?? '') }}"><br><br>

<label>Image URL</label><br>
<input type="text" name="image_url" value="{{ old('image_url', $product->image_url ?? '') }}"><br><br>

<label>
    <input type="checkbox" name="is_active" value="1"
        {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
    Active
</label><br><br>

<button type="submit">Save</button>
