<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoriesRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CategoriesController extends Controller
{
    private string $routeResourceName = 'categories';

    public function __construct()
    {
        $this->middleware('can:view categories list')->only('index');
        $this->middleware('can:create category')->only(['create', 'store']);
        $this->middleware('can:edit category')->only(['edit', 'update']);
        $this->middleware('can:delete category')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = CategoryResource::collection(
            Category::when($request->name, fn(EloquentBuilder $builder, $value) => $builder->where('name','like',"%{$value}%"))
            ->when($request->parentId, 
                fn(EloquentBuilder $builder, $value) => $builder->where('parent_id', $value),
                fn(EloquentBuilder $builder, $value) => $builder->root()
            )
            ->when($request->active != null, fn(EloquentBuilder $builder, $value) => $builder
                ->when($request->active, 
                    fn(EloquentBuilder $builder) => $builder->active(),
                    fn(EloquentBuilder $builder) => $builder->inActive(),
                )
            )
            ->latest('id')
            ->withCount(['children'])->paginate(100));
        $headers = [
            [
                'label' => 'Name',
                'name' => 'name'
            ],
            [
                'label' => 'Children',
                'name' => 'children_count'
            ],
            [
                'label' => 'Active',
                'name' => 'active'
            ],
            [
                'label' => 'Created At',
                'name' => 'created_at'
            ],
            [
                'label' => 'Actions',
                'name' => 'actions'
            ]
        ];
        $filters = (object) $request->all();
        $routeResourceName = $this->routeResourceName;
        $title = 'Categories';
        $can = [
            'create' => $request->user()->can('create category'),
        ];
        $rootCategories = CategoryResource::collection(Category::root()->get());
        return Inertia::render('Categories/Index', compact('items', 'headers','filters', 'routeResourceName', 'title', 'can', 'rootCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $edit = false;
        $title = 'Add Category';
        $routeResourceName = $this->routeResourceName;
        $rootCategories = CategoryResource::collection(Category::root()->get());
        return Inertia::render('Categories/Create', compact('edit', 'title', 'routeResourceName', 'rootCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriesRequest $request)
    {   
        $data = $request->safe()->only(['name','slug','active']);
        $data['parent_id'] = $request->parentId;
        $category = Category::create($data);
        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        
        $item = new CategoryResource(($category));
        $edit = true;
        $title = 'Edit category';
        $routeResourceName = $this->routeResourceName;
        $rootCategories = CategoryResource::collection(Category::root()->where('id', '!=', $category->id)->get());
        return Inertia::render('Categories/Create', compact('item', 'edit', 'title', 'routeResourceName', 'rootCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriesRequest $request, Category $category)
    {
        $data = $request->safe()->only(['name','slug','active']);
        $data['parent_id'] = $request->parentId;
        $category->update($data);
        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}
