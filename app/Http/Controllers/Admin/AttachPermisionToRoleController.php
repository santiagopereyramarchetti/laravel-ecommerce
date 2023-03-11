<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AttachPermisionToRoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:edit role');
    }

    public function __invoke(Request $request)
    {
        $permission = Permission::find($request->permissionId);
        
        $permission->assignRole($request->roleId);

        return redirect()->back()->with('success','Permission attached successfully');
    }
}
