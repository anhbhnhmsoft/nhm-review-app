@props([
    'title' => 'Bộ lọc',
    'name' => 'select',
    'type' => 'radio',
    'options' => [],
    'selected' => null,
    'model' => null,
    'search' => false,
])
<div class="collapse collapse-arrow">
    <input type="checkbox" checked />
    <div class="collapse-title text-base font-semibold flex items-center justify-between">
        <span>{{ $title }}</span>
    </div>
    <div class="collapse-content">
        <div class="form-control {{ $search == true ? 'max-h-[355px]' : 'max-h-48' }} overflow-y-auto"
            @if ($search) x-data="{ searchTerm: '' }" @endif>
            @if ($search)
                <div class="mb-3 sticky top-0 bg-white z-10 pb-2">
                    <input type="text" x-model="searchTerm" placeholder="Tìm kiếm ..."
                        class="input w-full h-9 text-sm border-gray-100 bg-gray-50 focus:bg-white focus:border-[#52ab5c] transition-colors" />
                </div>
            @endif
            @if ($type === 'radio')
                @foreach ($options as $value => $name)
                    <label class="flex cursor-pointer items-start gap-3 mb-2.5 py-1 hover:bg-gray-50 rounded px-1"
                        @if ($search) x-show="searchTerm === '' || '{{ strtolower($name) }}'.includes(searchTerm.toLowerCase())" @endif>
                        <input type="radio" name="{{ $model ?? $name }}" class="radio checked:text-[#52ab5c] mt-0.5 flex-shrink-0"
                            wire:model.live="{{ $model }}" value="{{ ltrim($value, '!') }}" />
                        <span class="label-text text-sm leading-relaxed flex-1 min-w-0">{{ ltrim($name, '!') }}</span>
                    </label>
                @endforeach
            @elseif($type === 'checkbox')
                @foreach ($options as $id => $name)
                    <label class="flex cursor-pointer items-start gap-3 mb-2.5 py-1 hover:bg-gray-50 rounded px-1"
                        @if ($search) x-show="searchTerm === '' || '{{ strtolower($name) }}'.includes(searchTerm.toLowerCase())" @endif>
                        <input type="checkbox" name="{{ $model ?? $name }}[]"
                            class="w-5 h-5 checkbox rounded-none checked:bg-[#52ab5c] checked:text-white mt-0.5 flex-shrink-0"
                            wire:model.live="{{ $model }}" value="{{ $id }}" />
                        <span class="label-text text-sm leading-relaxed flex-1 min-w-0">{{ $name }}</span>
                    </label>
                @endforeach
            @endif
        </div>
    </div>
</div>
