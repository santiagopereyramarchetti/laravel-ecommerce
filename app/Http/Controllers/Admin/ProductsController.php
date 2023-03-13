<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductsRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsController extends Controller
{
    private string $routeResourceName = 'products';

    public function __construct()
    {
        $this->middleware('can:view products list')->only('index');
        $this->middleware('can:create product')->only(['create', 'store']);
        $this->middleware('can:edit product')->only(['edit', 'update']);
        $this->middleware('can:delete product')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = ProductResource::collection(
            Product::when($request->name, fn(EloquentBuilder $builder, $value) => $builder->where('name','like',"%{$value}%"))
            ->when($request->categoryId, 
                fn(EloquentBuilder $builder, $value) => $builder->whereHas('categories',
                    fn(EloquentBuilder $builder) => $builder->where('categories.id', $value)
                )
            )
            ->when($request->subCategoryId, 
                fn(EloquentBuilder $builder, $value) => $builder->whereHas('categories',
                    fn(EloquentBuilder $builder) => $builder->where('categories.id', $value)
                )
            )
            ->when($request->active != null, fn(EloquentBuilder $builder, $value) => $builder
                ->when($request->active, 
                    fn(EloquentBuilder $builder) => $builder->active(),
                    fn(EloquentBuilder $builder) => $builder->inActive(),
                )
            )
            ->when($request->featured != null, fn(EloquentBuilder $builder, $value) => $builder
                ->where('featured', $request->featured)
            )
            ->when($request->showOnSlider != null, fn(EloquentBuilder $builder, $value) => $builder
                ->where('show_on_slider', $request->showOnSlider)
            )
            ->latest('id')
            ->paginate(10));
        $headers = [
            [
                'label' => 'Name',
                'name' => 'name'
            ],
            [
                'label' => 'Cost Price',
                'name' => 'cost_price'
            ],
            [
                'label' => 'Selling Price',
                'name' => 'price'
            ],
            [
                'label' => 'On Slider',
                'name' => 'show_on_slider'
            ],
            [
                'label' => 'Featured',
                'name' => 'featured'
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
        $title = 'Products';
        $can = [
            'create' => $request->user()->can('create product'),
        ];
        $categories = CategoryResource::collection(Category::root()->with('children')->get());
        return Inertia::render('Products/Index', compact('items', 'headers','filters', 'routeResourceName', 'title', 'can', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $edit = false;
        $title = 'Add Product';
        $routeResourceName = $this->routeResourceName;
        $categories = CategoryResource::collection(Category::root()->with('children')->get());
        return Inertia::render('Products/Create', compact('edit', 'title', 'routeResourceName', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductsRequest $request)
    {   
        $product = Product::create($request->saveData());

        $product->categories()->attach($request->categoryIds());

        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'Product created successfully');
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
    public function edit(Product $product)
    {
        
        $item = new ProductResource(($product->load('categories')));
        $edit = true;
        $title = 'Edit product';
        $routeResourceName = $this->routeResourceName;
        $categories = CategoryResource::collection(Category::root()->with('children')->get());
        return Inertia::render('Products/Create', compact('item', 'edit', 'title', 'routeResourceName', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductsRequest $request, Product $product)
    {
        $product->update($request->saveData());

        $product->categories()->sync($request->categoryIds());

        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }
}
