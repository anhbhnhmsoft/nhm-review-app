@props([
    'label',
    'filter',
    'id' => null,
])

<span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
    {{ $label }}
    <button 
        wire:click="clearFilter('{{ $filter }}'{{ $id !== null ? ', '.$id : '' }})" 
        class="ml-1"
    >×</button>
</span>
