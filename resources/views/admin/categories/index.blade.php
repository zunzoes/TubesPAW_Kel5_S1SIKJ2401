@extends('layouts.admin')

@section('title', 'Categories - Apparify')
@section('page-title', 'Categories Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 style="color: #6096B4;"><i class="fas fa-list"></i> All Categories</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn text-white" style="background-color: #6096B4;">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="background-color: white; border-radius: 15px;">
        <div class="card-body">
            @if(isset($categories) && $categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr style="background-color: #EEE9DA; color: #6096B4;">
                                <th width="5%" class="ps-3">#</th>
                                <th width="25%">Name</th>
                                <th width="25%">Slug</th>
                                <th width="30%">Description</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                                <tr>
                                    <td class="ps-3">{{ $index + 1 }}</td>
                                    <td><strong style="color: #2C3333;">{{ $category->name }}</strong></td>
                                    <td><code style="color: #6096B4; background-color: #FCF8EE; padding: 2px 5px; border-radius: 4px;">{{ $category->slug }}</code></td>
                                    <td class="text-muted">{{ Str::limit($category->description, 50) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm text-white" style="background-color: #93BFCF;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure want to delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-white" style="background-color: #BDCDD6;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($categories, 'links'))
                    <div class="mt-3">
                        {{ $categories->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-list fa-3x mb-3" style="color: #BDCDD6;"></i>
                    <p class="text-muted">No categories found</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn text-white" style="background-color: #6096B4;">
                        <i class="fas fa-plus"></i> Create First Category
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Penyesuaian Pagination agar senada dengan palet */
    .pagination .page-link {
        color: #6096B4;
    }
    .pagination .page-item.active .page-link {
        background-color: #6096B4;
        border-color: #6096B4;
    }
</style>
@endsection