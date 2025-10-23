{{-- Asumsikan Anda punya layout utama bernama 'layouts.admin' --}}

@extends('layouts.admin_layout')



@section('content')
    <div class="container">

        <h2>Pengaturan Akun</h2>



        {{-- Menampilkan pesan sukses jika ada --}}

        @if (session('success'))
            <div class="alert alert-success">

                {{ session('success') }}

            </div>
        @endif



        <form action="{{ route('admin.pengaturan.update') }}" method="POST">

            @csrf {{-- Token keamanan Laravel --}}

            @method('PUT') {{-- Method untuk update --}}



            <div class="form-group mb-3">

                <label for="name">Nama</label>

                {{-- Nama hanya ditampilkan, tidak bisa diubah di form ini --}}

                <input type="text" id="name" class="form-control" value="{{ $admin->name }}" disabled>

            </div>



            <div class="form-group mb-3">

                <label for="username">Username</label>

                <input type="text" id="username" name="username"
                    class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username', $admin->username) }}" required>

                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>



            <hr>

            <p class="text-muted">Kosongkan jika tidak ingin mengubah password.</p>



            <div class="form-group mb-3">

                <label for="password">Password Baru</label>

                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>



            <div class="form-group mb-3">

                <label for="password_confirmation">Konfirmasi Password Baru</label>

                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">

            </div>



            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>

        </form>

    </div>
@endsection
