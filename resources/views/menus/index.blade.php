<h2>Daftar Menu</h2>

@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<ul>
    @foreach ($menus as $menu)
        <li>
            {{ $menu->name }} - Rp{{ number_format($menu->price) }}
            ({{ $menu->menu_category->name ?? 'Tidak Ada Kategori' }})

            {{-- Hapus --}}
            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>

            {{-- Edit --}}
            <a href="{{ route('menus.edit', $menu->id) }}">Edit</a>
        </li>
    @endforeach
</ul>

<h3>Tambah Menu</h3>
<form method="POST" action="{{ route('menus.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Nama Menu"><br>
    <input type="number" step="0.01" name="price" placeholder="Harga"><br>

    <div class="div">
        <select name="menu_category_id">
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select><br>
        <a href="{{ route('menu-categories.index') }}" class="btn">
            + List Kategori
        </a>
    </div>

    <button type="submit">Tambah</button>
</form>
