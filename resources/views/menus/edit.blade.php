<h2>Edit Menu</h2>

<form method="POST" action="{{ route('menus.update', $menu->id) }}">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $menu->name }}"><br>
    <input type="number" step="0.01" name="price" value="{{ $menu->price }}"><br>

    <select name="menu_category_id">
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ $menu->menu_category_id == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select><br>

    <button type="submit">Simpan</button>
</form>

<a href="{{ route('menus.index') }}">Kembali</a>
