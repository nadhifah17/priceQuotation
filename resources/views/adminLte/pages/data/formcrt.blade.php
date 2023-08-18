<div class="form-group mb-3">
    <label for="name">Alternatif:</label>
    <input type="text" name="name" id="name" placeholder="" required>
    @error('name')
        <div>{{ $message }}</div>
    @enderror
</div>
<div class="form-group mb-3">
    <label for="price">Overhead (0,05 ≤ OH ≤ 0,15):</label>
    <input type="number" name="price" id="price" min="0.00" max="1.00" step="0.01" value="{{ old('price') }}" required>
    @error('price')
        <div>{{ $message }}</div>
    @enderror
</div>
<div class="form-group mb-3">
    <label for="quality">Material Spec (1 ≤  Material Spec ≤ 5):</label>
    <input type="number" name="quality" id="quality" value="{{ old('quality') }}" min="1" max="5" required>
    @error('quality')
        <div>{{ $message }}</div>
    @enderror
</div>
<div class="form-group mb-3">
    <label for="service">Cycle Time (0 < CT ≤ 5):</label>
    <input type="number" name="service" id="service" value="{{ old('service') }}" required>
    @error('service')
        <div>{{ $message }}</div>
    @enderror
</div>
