<div class="modal fade" id="addVariantModal" tabindex="-1" aria-labelledby="addVariantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary" id="addVariantModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Variant
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Size <span class="text-danger">*</span></label>
                        <select class="form-select border-0 bg-light" name="size" required>
                            <option value="">Select Size</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold small">Color Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-0 bg-light" name="color" placeholder="e.g. Jet Black" required>
                        </div>
                        <div class="col-md-4 mb-3 text-center">
                            <label class="form-label fw-bold small">Pick Color</label>
                            <input type="color" class="form-control form-control-color border-0 bg-light w-100" name="color_code" value="#000000" style="height: 38px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-0 bg-light" name="stock" value="0" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">Additional Price (Rp)</label>
                            <input type="number" class="form-control border-0 bg-light" name="additional_price" value="0" min="0">
                            <small class="text-muted" style="font-size: 0.7rem;">Harga tambahan dari harga dasar produk.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Add Variant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>