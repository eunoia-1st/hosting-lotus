<h2>Edit Kategori</h2>

<form method="POST" action="{{ route('menu-categories.update', $menuCategory->id) }}">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $menuCategory->name }}">
    <button type="submit">Simpan</button>
</form>

<a href="{{ route('menu-categories.index') }}">Kembali</a>
