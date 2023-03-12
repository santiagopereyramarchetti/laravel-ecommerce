<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RolesRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    private string $routeResourceName = 'roles';

    public function __construct()
    {
        $this->middleware('can:view roles list')->only('index');
        $this->middleware('can:create role')->only(['create', 'store']);
        $this->middleware('can:edit role')->only(['edit', 'update']);
        $this->middleware('can:delete role')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = RoleResource::collection(Role::when($request->name, fn(EloquentBuilder $builder, $value) => $builder->where('name','like',"%{$value}%") )->latest('id')->paginate(10));
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
        $title = 'Roles';
        $can = [
            'create' => $request->user()->can('create role'),
        ];
        return Inertia::render('Roles/Index', compact('items', 'headers','filters', 'routeResourceName', 'title', 'can'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $edit = false;
        $title = 'Add Role';
        $routeResourceName = $this->routeResourceName;
        return Inertia::render('Roles/Create', compact('edit', 'title', 'routeResourceName'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RolesRequest $request)
    {
        Role::create($request->validated());
        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'Role created successfully');
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
    public function edit(Role $role)
    {

        $role->load('permissions');

        $item = new RoleResource(($role));
        $edit = true;
        $title = 'Edit role';
        $routeResourceName = $this->routeResourceName;
        $permissions = PermissionResource::collection(Permission::get(['id','name']));
        return Inertia::render('Roles/Create', compact('item', 'edit', 'title', 'routeResourceName','permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RolesRequest $request, Role $role)
    {
        $role->update($request->validated());
        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully');
    }
}
