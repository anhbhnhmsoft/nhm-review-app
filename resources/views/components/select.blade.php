@props([
    'title' => 'Bộ lọc',
    'name' => 'select',
    'type' => 'radio',
    'options' => [],
    'selected' => null,
    'model' => null,
])
<div class="collapse collapse-arrow">
    <input type="checkbox" checked />
    <div class="collapse-title text-base font-semibold flex items-center justify-between">
        <span>{{ $title }}</span>
    </div>
    <div class="collapse-content">
        <div class="form-control max-h-48 overflow-y-auto">
            @if($type === 'radio')
                @foreach (array_slice($options, 0, 7) as $option)
                    <label class="flex cursor-pointer justify-start gap-3 mb-2.5">
                        <input type="radio" name="{{ $model ?? $name }}" class="radio checked:text-[#52ab5c]" wire:model="{{ $model }}" value="{{ ltrim($option, '!') }}"/>
                        <span class="label-text">{{ ltrim($option, '!') }}</span>
                    </label>
                @endforeach
            @elseif($type === 'checkbox')
                @foreach (array_slice($options, 0, 7) as $option)
                    <label class="flex cursor-pointer justify-start gap-3 mb-2.5">
                        <input type="checkbox" name="opening" class="w-5 h-5 checkbox rounded-none checked:bg-[#52ab5c] checked:text-white" />
                        <span class="label-text">{{ $option }}</span>
                    </label>
                @endforeach
            @endif
        </div>
    </div>
</div>
