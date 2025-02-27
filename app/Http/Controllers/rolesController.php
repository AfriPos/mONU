<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class rolesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * Shows all roles except Developer role since it is a special role
     * that is not meant to be edited or deleted.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $roles = Role::where('name', '!=', 'Developer')->get();
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Create the role
        $role = Role::create(['name' => $validated['name']]);

        // Sync permissions by their names
        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);
        // Update the role
        $role->update(['name' => $validated['name']]);

        // Sync permissions by their names
        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->name !== 'Developer') {
            // Check if there are users with this role
            if ($role->users()->count() > 0) {
                return redirect()->route('roles.index')->with('error', 'Cannot delete role. There are users assigned this role. Please change their roles first.');
            }
            
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        }
        return redirect()->route('roles.index')->with('error', 'Cannot delete Developer role.');
    }
}
