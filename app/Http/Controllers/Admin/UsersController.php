<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    private string $routeResourceName = 'users';

    public function __construct()
    {
        $this->middleware('can:view users list')->only('index');
        $this->middleware('can:create user')->only(['create', 'store']);
        $this->middleware('can:edit user')->only(['edit', 'update']);
        $this->middleware('can:delete user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = UserResource::collection(
            User::when($request->name, fn(EloquentBuilder $builder, $value) => $builder->where('name','like',"%{$value}%"))
            ->when($request->email, fn(EloquentBuilder $builder, $value) => $builder->where('email','like',"%{$value}%"))
            ->when($request->roleId, fn(EloquentBuilder $builder, $value) => $builder->
                whereHas('roles', fn(EloquentBuilder $builder) => $builder->where('roles.id', $value)))
            
            ->latest('id')->with(['roles'])->paginate(10));
        $headers = [
            [
                'label' => 'Name',
                'name' => 'name'
            ],
            [
                'label' => 'Email',
                'name' => 'email'
            ],
            [
                'label' => 'Role',
                'name' => 'role'
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
        $title = 'Users';
        $can = [
            'create' => $request->user()->can('create user'),
        ];
        $roles = RoleResource::collection(Role::all());
        return Inertia::render('Users/Index', compact('items', 'headers','filters', 'routeResourceName', 'title', 'can', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $edit = false;
        $title = 'Add User';
        $routeResourceName = $this->routeResourceName;
        $roles = RoleResource::collection(Role::all());
        return Inertia::render('Users/Create', compact('edit', 'title', 'routeResourceName', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UsersRequest $request)
    {   
        $user = User::create($request->safe()->only(['name','email','password']));
        $user->assignRole($request->roleId);
        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'User created successfully');
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
    public function edit(User $user)
    {

        $user->load(['permissions','roles']);
        
        $item = new UserResource(($user));
        $edit = true;
        $title = 'Edit user';
        $routeResourceName = $this->routeResourceName;
        $roles = RoleResource::collection(Role::all());
        $permissions = PermissionResource::collection(Permission::get(['id','name']));
        return Inertia::render('Users/Create', compact('item', 'edit', 'title', 'routeResourceName','permissions', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UsersRequest $request, User $user)
    {
        $user->update($request->validated());
        $routeResourceName = $this->routeResourceName;
        return redirect()->route("admin.{$this->routeResourceName}.index", compact('routeResourceName'))->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
