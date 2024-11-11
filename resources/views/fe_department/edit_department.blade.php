@extends('layouts.app ')

@section('content')
<div class="container">
    <h1>Sửa Phòng Ban</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('departments.update', $department->id) }}" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="form-group">
            <label for="name">Tên phòng ban</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $department->name) }}" required>
        </div>

        <div class="form-group">
            <label for="parent_id">Phòng ban cha</label>
            <select name="parent_id" class="form-control" id="parent_id">
                <option value="">Không có</option>
                @foreach ($departments as $dep)
                    <!-- Kiểm tra xem phòng ban này có phải là cha của chính nó không -->
                    @if ($dep->id != $department->id) 
                        <option value="{{ $dep->id }}" {{ $dep->id == $department->parent_id ? 'selected' : '' }}>
                            {{ $dep->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control" id="status">
                <option value="1" {{ $department->status ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ !$department->status ? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('departments') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
