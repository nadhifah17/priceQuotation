<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RolePermissionController extends Controller
{
    /**
     * Function to render permission list
     */
    public function permissions()
    {
        $pageTitle = setPageTitle(__('view.permissions'));
        $title = __('view.permissions');
        return view('adminLte.pages.setting.permissions.index', compact('pageTitle', 'title'));
    }

    /**
     * Function to show all data permission to datatablse
     * @return DataTables
     */
    public function ajaxPermission()
    {
        $data = Permission::all();
        return DataTables::of($data)
            ->addColumn('action', function($d) {
                return '<button class="btn btn-sm bg-primary-warning" type="button" onclick="editItem('. $d->id .')">'. __('view.edit') .'</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Function to store permission
     * @param string name
     * @return Response
     */
    public function storePermission(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|unique:permissions,name'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors()->all()], 500);
            }

            $name = $request->name;
            Permission::create(['name' => $name]);
            return response()->json(['message' => 'Success save permission']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to show current permission based on gived ID
     * @param int id
     * @return Response
     */
    public function editPermission($id)
    {
        try {
            $data = Permission::findById($id);
            $url_update = route('setting.permissions.update', $id);

            return response()->json([
                'url' => $url_update,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to update current permission
     * @param ind id
     * @param string name
     * @return Response
     */
    public function updatePermission(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|unique:permissions,name'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors()->all()], 500);
            }

            return response()->json(['message' => 'Berhasil Memperbarui Permission']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to render role list
     */
    public function roles()
    {
        $pageTitle = setPageTitle(__('view.roles'));
        $title = __('view.roles');
        return view('adminLte.pages.setting.roles.index', compact('pageTitle', 'title'));
    }

    /**
     * Function to show all data roles to datatablse
     * @return DataTables
     */
    public function ajaxRoles()
    {
        $data = Role::all();
        return DataTables::of($data)
            ->addColumn('action', function($d) {
                return '<a class="btn btn-sm bg-primary-warning" href="'. route('setting.roles.show', $d->id) .'">'. __('view.show') .'</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Function to get role detail
     * @param int id
     * @return Renderable
     */
    public function showRoles($id)
    {
        $data = Role::findById($id);
        $permissions = $data->permissions;
        $pageTitle = setPageTitle(__('view.detail_role'));
        $title = __('view.detail_role');
        $all_permissions = Permission::all();
        $all_permissions = collect($all_permissions)->map(function($permission) use ($permissions) {
            $permission->active = false;
            collect($permissions)->map(function($item) use ($permission) {
                if ($item->id == $permission->id) {
                    $permission->active = true;
                }
            });

            return $permission;
        })->values();
        // return [
        //     'role' => $permissions,
        //     'permission' => $all_permissions
        // ];
        return view('adminLte.pages.setting.roles.show', compact('data', 'pageTitle', 'title', 'all_permissions', 'permissions'));
    }

    /**
     * Function to update role
     * @param array permissions
     * @param string name
     */
    public function updateRoles(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'permissions.*' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors()->all()], 500);
            }

            $role = Role::findById($id);
            $role->name = $request->name;
            $current_permission = $role->permissions;
            $role->save();

            // detach current permissions
            foreach($current_permission as $p) {
                $s = Permission::findById($p->id);
                $role->revokePermissionTo($s);
            }

            // attach new permission
            $permission = $request->permissions;
            for($a = 0; $a < count($permission); $a++) {
                $item = Permission::findById($permission[$a]);
                $role->givePermissionTo($item);
            }

            DB::commit();
            return response()->json(['message' => 'Berhasil Memperbarui Role', 'url' => route('setting.roles')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to store role
     * @param string name
     * @return Response
     */
    public function storeRole(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors()->all()], 500);
            }

            $name = $request->name;
            Role::create(['name' => $name]);
            return response()->json(['message' => 'Success save role']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to delete roles
     * @param int id
     * @return Repsonse
     */
    public function destroyRole($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::findById($id);
            // detach all permission
            $detach = $this->detachPermission($role);
            if (isset($detach['error'])) {
                return response()->json(['message' => $detach['message']], 500);
            }
            $role->delete();

            DB::commit();
            return response()->json(['message' => 'Berhasil Menghapus Role']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to detach permission from role
     * @param collection $role
     * @return void
     */
    public function detachPermission($role)
    {
        try {
            $permissions = $role->permissions;
            if (count($permissions) > 0) {
                foreach ($permissions as $permission) {
                    $item = Permission::findById($permission->id);
                    $role->revokePermissionTo($item);
                }
            }

            return;
        } catch (\Throwable $th) {
            return [
                'error' => true,
                'message' => $th->getMessage()
            ];
        }
    }
}
