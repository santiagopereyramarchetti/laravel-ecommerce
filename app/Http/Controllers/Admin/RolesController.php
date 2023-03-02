<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RolesRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    private string $routeResourceName = 'roles';
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
        return Inertia::render('Roles/Index', compact('items', 'headers','filters', 'routeResourceName', 'title'));
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
        return redirect()->route('admin.roles.index', compact('routeResourceName'))->with('success', 'Role created successfully');
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
        $item = new RoleResource(($role));
        $edit = true;
        $title = 'Edit role';
        $routeResourceName = $this->routeResourceName;
        return Inertia::render('Roles/Create', compact('item', 'edit', 'title', 'routeResourceName'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RolesRequest $request, Role $role)
    {
        $role->update($request->validated());
        $routeResourceName = $this->routeResourceName;
        return redirect()->route('admin.roles.index', compact('routeResourceName'))->with('success', 'Role updated successfully');
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
