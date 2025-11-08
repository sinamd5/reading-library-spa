<div class="mx-auto max-w-7xl p-6">
    <h1 class="text-2xl font-semibold mb-4">Lessons Library</h1>

    {{-- Controls --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="md:col-span-3 flex flex-wrap items-center gap-3">
            <input type="text" placeholder="Search title..." wire:model.live.debounce.350ms="q"
                   class="border rounded px-3 py-2 w-full md:w-72" />

            <select wire:model.live="type" class="border rounded px-3 py-2">
                <option value="">All types</option>
                @foreach($lessonTypeLabels as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>

            <select wire:model.live="is_free" class="border rounded px-3 py-2">
                <option value="">All prices</option>
                <option value="1">Free</option>
                <option value="0">Paid</option>
            </select>

            <input type="number" min="0" placeholder="Min sections" wire:model.live="min_sections"
                   class="border rounded px-3 py-2 w-36" />

            <select wire:model.live="sort" class="border rounded px-3 py-2">
                <option value="created_at">Newest</option>
                <option value="price">Price</option>
                <option value="sections_count">Sections count</option>
            </select>

            <button type="button"
                    wire:click="$set('dir', '{{ $dir === 'asc' ? 'desc' : 'asc' }}')"
                    class="border rounded px-3 py-2">
                Dir: {{ strtoupper($dir) }}
            </button>

            <select wire:model.live="perPage" class="border rounded px-3 py-2">
                <option value="12">12</option>
                <option value="24">24</option>
                <option value="48">48</option>
            </select>
        </div>

        <div class="md:col-span-1">
            <div class="border rounded p-3">
                <div class="font-semibold mb-2">Categories</div>
                <div class="max-h-56 overflow-auto space-y-1">
                    @foreach($this->categories as $cat)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" value="{{ $cat->id }}" wire:model.live="selectedCategories" />
                            {{ $cat->title }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    @if($lessons->count() === 0)
        <div class="p-4 border rounded bg-gray-50">No results.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($lessons as $lesson)
                <div class="border rounded p-4">
                    <div class="font-semibold mb-1">{{ $lesson->title }}</div>
                    <div class="text-sm text-gray-700 mb-1">Type: {{ ucfirst($lesson->type->value) }}</div>
                    <div class="text-sm text-gray-700 mb-1">
                        Sections: {{ $lesson->sections_count ?? $lesson->sections()->count() }}
                    </div>
                    <div class="text-sm font-medium mb-2">
                        @if((float)$lesson->price === 0.0)
                            Free
                        @else
                            ${{ number_format((float)$lesson->price, 2) }}
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-1 text-xs text-gray-600">
                        @php($cats = $lesson->categories->take(3))
                        @foreach($cats as $c)
                            <span class="px-2 py-0.5 border rounded">{{ $c->title }}</span>
                        @endforeach
                        @php($extra = $lesson->categories->count() - $cats->count())
                        @if($extra > 0)
                            <span class="px-2 py-0.5 border rounded">+{{ $extra }} more</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-between">
            @if($lessons->onFirstPage())
                <span class="opacity-50 border rounded px-3 py-2">Prev</span>
            @else
                <button wire:click="previousPage" class="border rounded px-3 py-2">Prev</button>
            @endif

            @if($lessons->hasMorePages())
                <button wire:click="nextPage" class="border rounded px-3 py-2">Next</button>
            @else
                <span class="opacity-50 border rounded px-3 py-2">Next</span>
            @endif
        </div>
    @endif
</div>
