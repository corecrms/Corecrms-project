<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::with('assignWarehouse')->orderBy('id', 'DESC')->get();
        
        $roles = Role::whereIn('name', ['Admin', 'Manager','Cashier'])->pluck('name', 'name')->all();
        $warehouses = Warehouse::latest()->get();
        // dd($roles);
        return view('back.users.index', compact('data', 'roles','warehouses'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function filterUsers(Request $req)
    {
        $query = User::orderBy('id', 'DESC');
        $roles = Role::pluck('name', 'name')->all();


        $filters = $req->all();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (isset($filters['email'])) {
            $query->where('email', $filters['email']);
        }
        if (isset($filters['phone'])) {
            $query->where('contact_no', $filters['phone']);
        }

        $data = $query->get();

        return view('back.users.index', compact('data', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('back.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'warehouse_id' => 'nullable',
        ]);

        $input = $request->all();
        $role = $request->input('roles');
        $input['created_by'] = $input['updated_by'] = auth()->user()->id;
        $input['type'] = $role;

        if (!Role::where('name', $role)->exists()) {
            return redirect()->back()->with('error', 'Role does not exist.');
        }
        $input['password'] = Hash::make($input['password']);
        // dd($input);
        $user = User::create($input);
        $user->assignRole($role);
        // if role = vendor or customer then assign role to user
        if ($role == 'Vendor') {
            $user->vendor()->create($input);
        } elseif ($role == 'Customer') {
            $user->customer()->create($input);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('back.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('back.users.edit', compact('user', 'roles', 'userRole'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();

        $data['updated_by'] = auth()->user()->id;

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->roles()->detach(); // Detach the roles associated with the user

        // Delete the user only if there are no related records in other tables
        try {
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete the user. There are related records in other tables.');
        }

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function deleteUsers(Request $req)
    {
        if ($req->ids) {
            foreach ($req->ids as $id) {
                $user = User::find($id);
                $user->roles()->detach();
                $user->delete();
            }
            return response()->json(['status' => 200, 'message' => 'User deleted successfully!']);
        }
    }
}
