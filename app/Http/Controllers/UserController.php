<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = setPageTitle(__('view.users'));
        $title = __('view.users');
        $roles = Role::all();
        return view('adminLte.pages.setting.users.index', compact('pageTitle', 'title', 'roles'));
    }

    /**
     * Function to get data for datatable
     * @return DataTables
     */
    public function ajax()
    {
        $data = User::all();
        return DataTables::of($data)
            ->addColumn('action', function($d) {
                return '<button class="btn btn-sm bg-primary-warning" type="button" onclick="editItem('. $d->id .')">'. __('view.edit') .'</button>
                    <button class="btn btn-sm bg-primary-danger" type="button" onclick="deleteItem('. $d->id .')">'. __('view.delete') .'</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'role' => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->save();

            $role = Role::findById($request->role);
            $user->assignRole($role);

            return response()->json(['message' => 'Berhasil Menambah User']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::with('role')->find($id);
            $url = route('users.update', $id);
            return response()->json(['message' => 'Success get data', 'data' => $user, 'url' => $url]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email',
                'role' => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()->all()], 500);
            }

            $user = User::find($id);
            $user->email = $request->email;
            $current_role = $user->role;

            // detach role
            $c = Role::findById($current_role);
            $user->removeRole($c);

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->save();

            // assign role
            $role = Role::findById($request->role);
            $user->assignRole($role);

            return response()->json(['message' => 'Berhasil Memperbarui Data User']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $role = Role::findById($user->role);
            $user->removeRole($role);
            $user->delete();

            return response()->json(['message' => 'Berhasil Menghapus Data User']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
