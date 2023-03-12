<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionsRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    private string $routeResourceName = 'permissions';

    public function __construct()
    {
        $this->middleware('can:view premissions list')->only('index');
        $this->middleware('can:create premission')->only(['create', 'store']);
        $this->middleware('can:edit premission')->only(['edit', 'update']);
        $this->middleware('can:delete premission')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = PermissionResource::collection(Permission::when($request->name, fn(EloquentBuilder $builder, $value) => $builder->where('name','like',"%{$value}%") )->latest('id')->paginate(10));
        $headers = [
            [
                'label' => 'Name',
                'name' => 'name'
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
        $title = 'Permissions';
        $can = [
            'create' => $request->user()->can('create permission'),
        ];
        return Inertia::render('Permissions/Index', compact('items', 'headers','filters', 'routeResourceName', 'title', 'can'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $edit = false;
        $title = 'Add Permission';
        $routeResourceName = $this->routeResourceName;
        return Inertia::render('Permissions/Create', compact('edit', 'title', 'routeResourceName'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionsRequest $request)
    {
        Permission::create($request->validated());
        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'Permission created successfully');
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
    public function edit(Permission $permission)
    {
        $item = new PermissionResource(($permission));
        $edit = true;
        $title = 'Edit permission';
        
        return Inertia::render('Permissions/Create', compact('item', 'edit', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionsRequest $request, Permission $permission)
    {
        $permission->update($request->validated());
        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->back()->with('success', 'Permission deleted successfully');
    }
}
