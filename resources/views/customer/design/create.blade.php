@extends('layouts.customer')

@section('title', 'Create Custom Design - Apparify')

@section('content')
<div class="container pb-5">
    {{-- Judul Tanpa Ikon sesuai instruksi sebelumnya --}}
    <h2 class="mb-4 fw-bold" style="color: var(--dark-text)">Create Custom Design</h2>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background-color: white;">
                <div class="card-body p-4">
                    {{-- Design Guidelines: Tetap menggunakan warna #6096B4 --}}
                    <div class="alert border-0 mb-4 shadow-sm" style="background-color: #6096B4; border-radius: 12px; color: white;">
                        <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Design Guidelines:</h6>
                        <ul class="mb-0 small" style="list-style-type: disc; padding-left: 20px;">
                            <li>Supported formats: <strong>PNG, JPG, JPEG, SVG</strong> (Max 5MB)</li>
                            <li>Recommended resolution: Minimum <strong>300 DPI</strong> for best print quality</li>
                            <li>Image size: Minimum <strong>1000x1000 pixels</strong></li>
                            <li>Our team will review your design before production</li>
                        </ul>
                    </div>

                    <form action="{{ route('customer.design.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Select Product Type --}}
                        <div class="mb-4">
                            <label for="product_id" class="form-label fw-bold small" style="color: var(--dark-text)">Select Base Product <span class="text-danger">*</span></label>
                            <select class="form-select border-light-subtle shadow-sm @error('product_id') is-invalid @enderror" 
                                    id="product_id" name="product_id" required style="border-radius: 10px; height: 45px; background-color: white;">
                                <option value="">-- Choose Product Type --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small text-muted">Select the apparel type you want to print your design on.</div>
                        </div>

                        {{-- Upload File --}}
                        <div class="mb-4">
                            <label for="design_file" class="form-label fw-bold small" style="color: var(--dark-text)">Upload Your Design <span class="text-danger">*</span></label>
                            <input type="file" class="form-control border-light-subtle shadow-sm @error('design_file') is-invalid @enderror" 
                                   id="design_file" name="design_file" accept="image/*" required style="border-radius: 10px; background-color: white;">
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted small">Maximum file size: 5MB</small>
                                <small id="file-size-check" class="fw-bold"></small>
                            </div>
                        </div>

                        {{-- Design Preview Box: Tetap Putih Bersih (#FFFFFF) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small" style="color: var(--dark-text)">Design Preview</label>
                            <div id="preview-container" class="border rounded-4 p-3 text-center shadow-sm" 
                                 style="min-height: 300px; display: none; border-style: dashed !important; border-color: #93BFCF !important; background-color: #FFFFFF;">
                                <img id="preview-image" src="" class="img-fluid rounded" style="max-height: 400px; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.08));">
                                <p class="mt-2 mb-0 small text-muted" id="preview-filename" style="font-style: italic;"></p>
                            </div>
                            <div id="preview-placeholder" class="border rounded-4 p-5 text-center text-muted shadow-sm" 
                                 style="border-style: dashed !important; border-color: var(--accent) !important; background-color: #FFFFFF;">
                                <i class="fas fa-image fa-3x mb-3 opacity-25" style="color: #93BFCF"></i>
                                <p class="mb-0">Your design preview will appear here</p>
                            </div>
                        </div>

                        {{-- Notes & Instructions dari Gambar --}}
                        <div class="mb-4">
                            <label for="design_notes" class="form-label fw-bold small" style="color: var(--dark-text)">Design Notes & Instructions</label>
                            <textarea class="form-control border-light-subtle shadow-sm @error('design_notes') is-invalid @enderror" 
                                      id="design_notes" name="design_notes" rows="4" 
                                      placeholder="Example: Put the logo on the front chest, size 10cm wide."
                                      style="border-radius: 12px; background-color: white;">{{ old('design_notes') }}</textarea>
                        </div>

                        {{-- Alert Note dari Gambar --}}
                        <div class="alert border-0 small mb-4 shadow-sm" style="background-color: #fff3e0; color: #856404; border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle me-2"></i> 
                            <strong>Note:</strong> Designs are reviewed manually. We will contact you if the image quality is too low for printing.
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between gap-3 mt-5">
                            <a href="{{ route('customer.products.index') }}" class="btn btn-light px-5 border fw-bold text-muted rounded-pill shadow-sm">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow rounded-pill" style="background-color: var(--primary); border-color: var(--primary);">
                                <i class="fas fa-paper-plane me-2"></i>Submit Design
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header border-0 py-3" style="background-color: var(--primary); color: white;">
                    <h5 class="mb-0 fw-bold small text-uppercase"><i class="fas fa-lightbulb me-2"></i>Design Tips</h5>
                </div>
                <div class="card-body p-4 bg-white">
                    <div class="mb-3">
                        <h6 class="fw-bold small mb-1" style="color: var(--dark-text)">High-Quality Designs</h6>
                        <p class="small text-muted mb-0">Use high-resolution images for crisp, clear prints. Blurry designs won't look good!</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold small mb-1" style="color: var(--dark-text)">Color Mode</h6>
                        <p class="small text-muted mb-0">Use RGB color mode for digital designs. We'll convert to appropriate print colors.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold small mb-1" style="color: var(--dark-text)">Safe Area</h6>
                        <p class="small text-muted mb-0">Keep important elements away from edges. Leave at least 0.5 inch margin.</p>
                    </div>
                    <div class="mb-0">
                        <h6 class="fw-bold small mb-1" style="color: var(--dark-text)">Copyright</h6>
                        <p class="small text-muted mb-0">Ensure you have the rights to use the uploaded artwork.</p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 15px; background-color: white;">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold mb-3" style="color: var(--dark-text)"><i class="fas fa-question-circle me-2" style="color: var(--primary)"></i>Need Help?</h6>
                    <p class="small text-muted mb-4">Our design team is ready to assist you with any questions about your custom apparel.</p>
                    <a href="{{ route('customer.chat.index') }}" class="btn btn-outline-primary w-100 fw-bold rounded-pill" style="border-color: var(--primary); color: var(--primary);">
                        <i class="fas fa-comments me-2"></i>Chat with Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Script tetap sama untuk fungsionalitas preview
document.getElementById('design_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = document.getElementById('preview-container');
    const previewPlaceholder = document.getElementById('preview-placeholder');
    const previewImage = document.getElementById('preview-image');
    const fileNameText = document.getElementById('preview-filename');
    const sizeCheck = document.getElementById('file-size-check');

    if (file) {
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        if (file.size > 5 * 1024 * 1024) {
            alert('File too large! Maximum size is 5MB.');
            this.value = '';
            previewContainer.style.display = 'none';
            previewPlaceholder.style.display = 'block';
            return;
        }
        sizeCheck.textContent = fileSizeMB + ' MB';
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            fileNameText.textContent = 'File: ' + file.name;
            previewContainer.style.display = 'block';
            previewPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection