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
    <input type="checkbox" checked/>
    <div class="collapse-title text-base font-semibold flex items-center justify-between">
        <span>{{ $title }}</span>
    </div>
    <div class="collapse-content">
        <div class="form-control {{ $search == true ? 'max-h-[355px]' : 'max-h-48' }} overflow-y-auto"
             @if ($search) x-data="{
                    searchTerm: '',
                    normalize(s) {
                        return (s ?? '')
                          .toString()
                          .normalize('NFD')
                          .replace(/[\u0300-\u036f]/g,'')
                          .toLowerCase()
                          .replace(/\s+/g,' ')
                          .trim();
                    },
                    match(term, text) {
                        const t = this.normalize(term);
                        if (!t) return true;
                        return this.normalize(text).includes(t);
                    }
            }" @endif>
            @if ($search)
                <div class="mb-3 sticky top-0 bg-white z-10 pb-2">
                    <input type="text" x-model.debounce.300ms="searchTerm" placeholder="Tìm kiếm ..."
                           class="input w-full h-9 text-sm border-gray-100 bg-gray-50 focus:bg-white focus:border-[#52ab5c] transition-colors"/>
                </div>
            @endif
            @if ($type === 'radio')
                    @php
                        // slug nhóm để tạo id duy nhất
                        $group = str_replace('.', '_', $model); // ví dụ: filters.opening_now -> filters_opening_now
                    @endphp

                    @foreach ($options as $value => $label)
                        @php
                            $val = ltrim($value, '!');  // nếu bạn dùng '!' để đánh dấu not
                            $text = ltrim($label, '!');
                            $id = $group . '__' . $loop->index; // id duy nhất, đơn giản
                        @endphp

                        <label for="{{ $id }}"
                               class="flex cursor-pointer items-start gap-3 mb-2.5 py-1 hover:bg-gray-50 rounded px-1"
                               @if ($search) x-show="match(searchTerm, @js($text))" @endif
                               wire:key="radio-{{ $group }}-{{ $val }}">
                            <input
                                id="{{ $id }}"
                                type="radio"
                                class="radio checked:text-[#52ab5c] mt-0.5 flex-shrink-0 peer"
                                wire:model.live="{{ $model }}"
                                value="{{ $val }}"
                            />
                            <span class="label-text text-sm leading-relaxed flex-1 min-w-0 peer-checked:font-medium">
                                {{ $text }}
                            </span>
                        </label>
                    @endforeach
            @elseif ($type === 'checkbox')
                @foreach ($options as $id => $name)
                    <label class="flex cursor-pointer items-start gap-3 mb-2.5 py-1 hover:bg-gray-50 rounded px-1"
                           @if ($search)x-show="match(searchTerm, @js($name))"@endif>
                        <input
                            type="checkbox"
                            value="{{ $id }}"
                            wire:model.live="{{ $model }}" {{-- ví dụ: filters.utilities --}}
                            wire:key="opt-{{ $id }}"
                            class="w-5 h-5 checkbox rounded-none checked:bg-[#52ab5c] checked:text-white mt-0.5 flex-shrink-0"
                        >
                        <span class="label-text text-sm leading-relaxed flex-1 min-w-0">{{ $name }}</span>
                    </label>
                @endforeach
            @endif
        </div>
    </div>
</div>
