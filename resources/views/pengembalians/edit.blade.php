@extends('layouts.app')

@section('title', 'Edit Pengembalian')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Edit Data Pengembalian</div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengembalians.update', $pengembalian->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_pengembalian" class="form-control"
                        value="{{ old('tanggal_pengembalian', $pengembalian->tanggal_pengembalian) }}" required>
                </div>

                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
