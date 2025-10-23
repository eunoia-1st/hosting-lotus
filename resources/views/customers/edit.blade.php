@extends('layouts.admin_layout')

@section('title', 'Edit Customer')
@section('page-title', "Edit Customer: {$customer->name}")

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Edit Customer</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $customer->name) }}" placeholder="Masukkan nama customer" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="phone" class="form-label fw-semibold">No. HP</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                            value="{{ old('phone', $customer->phone) }}" placeholder="Masukkan nomor HP">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', $customer->email) }}" placeholder="Masukkan email">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="address" class="form-label fw-semibold">Alamat</label>
                        <input type="text" name="address" id="address" class="form-control"
                            value="{{ old('address', $customer->address) }}" placeholder="Masukkan alamat">
                    </div>

                    <div class="col-12 d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            💾 Simpan
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
