

<form action="{{ isset($god) ? route('gods.update', $god) : route('gods.store') }}" method="POST" enctype="multipart/form-data" onsubmit="this.querySelector('button[type=submit]').disabled = true">
    @csrf

    @isset($god)
        @method('PUT')
    @endisset

    <div class="row g-3">
        @foreach ([
            'name' => 'Имя',
            'badge' => 'Категория',
            'sphere' => 'Сфера влияния',
            'symbols' => 'Символы',
            'parents' => 'Родители',
            'siblings' => 'Братья и сёстры',
            'spouse' => 'Супруг / супруга',
            'children' => 'Дети',
            'temple' => 'Храм',
            'myth' => 'Миф'
        ] as $field => $label)
            <div class="col-md-6">
                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                <input
                    type="text"
                    class="form-control @error($field) is-invalid @enderror"
                    id="{{ $field }}"
                    name="{{ $field }}"
                    value="{{ old($field, $god->$field ?? '') }}"
                    @if(in_array($field, ['name', 'badge', 'sphere'])) required @endif
                >
                @error($field)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <div class="col-12">
            <label for="description" class="form-label">Описание</label>
            <textarea
                class="form-control @error('description') is-invalid @enderror"
                id="description"
                name="description"
                rows="3"
                required
            >{{ old('description', $god->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="image" class="form-label">Изображение</label>
            @isset($god)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $god->image_path) }}"
                        alt="{{ $god->name }}"
                        width="120"
                        class="rounded">
                    <div class="form-text">Текущее изображение. Выберите новое, если хотите заменить.</div>
                </div>
            @endisset
            <input
                type="file"
                class="form-control @error('image') is-invalid @enderror"
                id="image"
                name="image"
                accept="image/*"
                @if(!isset($god)) required @endif
            >
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                {{ isset($god) ? 'Сохранить' : 'Добавить' }}
            </button>
            <a href="{{ route('gods.index') }}" class="btn btn-secondary">Отмена</a>
        </div>
    </div>
</form>
