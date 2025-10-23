<h2>Daftar Kategori Menu</h2>

@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<ul>
    @foreach ($categories as $category)
        <li>
            {{ $category->name }}

            {{-- Hapus --}}
            <form action="{{ route('menu-categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>

            {{-- Edit --}}
            <a href="{{ route('menu-categories.edit', $category->id) }}">Edit</a>
        </li>
    @endforeach
</ul>

<h3>Tambah Kategori</h3>
<form method="POST" action="{{ route('menu-categories.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Nama Kategori">
    <button type="submit">Tambah</button>
</form>

<a href="{{ route('menus.index') }}" class="btn">
    List Menu
</a>
