@extends('layouts.admin')

@section('title', 'Edit Category - Apparify')
@section('page-title', 'Edit Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Category Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required
                                   placeholder="e.g. T-Shirts, Hoodies, Jackets">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" value="{{ $category->slug }}" disabled>
                            <small class="text-muted">Slug is auto-generated and cannot be edited</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4"
                                      placeholder="Brief description about this category">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle"></i> Category Info</h6>
                    <ul class="small mb-0">
                        <li><strong>Created:</strong> {{ $category->created_at->format('d M Y H:i') }}</li>
                        <li><strong>Last Updated:</strong> {{ $category->updated_at->format('d M Y H:i') }}</li>
                        <li><strong>Products:</strong> {{ $category->products->count() ?? 0 }} items</li>
                    </ul>
                </div>
            </div>

            <div class="card bg-danger text-white mt-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h6>
                    <p class="small mb-3">Deleting this category will also remove all associated products.</p>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure? This will delete all products in this category!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light btn-sm w-100">
                            <i class="fas fa-trash"></i> Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection