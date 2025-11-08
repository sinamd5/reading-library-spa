<?php declare(strict_types=1);


namespace App\Livewire;


use App\Enums\LessonType;
use App\Enums\SortDirection;
use App\Enums\SortField;
use App\Models\Category;
use App\Models\Lesson;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class LessonsBrowser extends Component
{
    use WithPagination;
// State (public so Livewire binds them)
    public string $q = '';
    /** @var array<int> */
    public array $selectedCategories = [];
    public $is_free = null; // bool|null
    public ?string $type = null; // enum value or null
    public ?int $min_sections = null;


    public string $sort = 'created_at';
    public string $dir = 'desc';
    public int $perPage = 12;


    public function render()
    {
        return view('livewire.lessons-browser', [
            'lessons' => $this->query(),
            'lessonTypeLabels' => LessonType::labels(),
        ]);
    }
    protected function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'selectedCategories' => ['array'],
            'selectedCategories.*' => ['integer', 'min:1'],
            'is_free' => ['nullable', 'boolean'],
            'type' => ['nullable', 'in:' . implode(',', LessonType::values())],
            'min_sections' => ['nullable', 'integer', 'min:0'],
            'sort' => ['required', 'in:' . implode(',', SortField::values())],
            'dir' => ['required', 'in:' . implode(',', SortDirection::values())],
            'perPage' => ['required', 'integer', 'in:12,24,48'],
        ];
    }


    public function updated($prop): void
    {
// Validate each time a bound prop changes; keep UX tight but safe
        $this->validateOnly($prop);
    }



    public function updating($name): void
    {
        if (in_array($name, [
            'q','selectedCategories','is_free','type','min_sections','sort','dir','perPage'
        ], true)) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function categories()
    {
        return Category::query()->orderBy('title')->get(['id', 'title']);
    }


    public function query(): Paginator
    {
// sanitize enums (fail-safe defaults)
        $sortField = in_array($this->sort, SortField::values(), true)
            ? SortField::from($this->sort)
            : SortField::CreatedAt;
        $sortDir = in_array($this->dir, SortDirection::values(), true)
            ? SortDirection::from($this->dir)
            : SortDirection::Desc;
        $type = ($this->type && in_array($this->type, LessonType::values(), true))
            ? LessonType::from($this->type)
            : null;


        $catIds = array_values(array_filter($this->selectedCategories, fn($v) => is_numeric($v)));


        return Lesson::query()
            ->searchTitle($this->q)
            ->filterCategories($catIds)
            ->isFree($this->is_free)
            ->ofType($type)
            ->minSections($this->min_sections)
            ->sortBy($sortField, $sortDir)
            ->with(['categories:id,title'])
            ->simplePaginate($this->perPage)
            ->withQueryString();
    }
}
