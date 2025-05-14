<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\VendorResource;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\VendorStoreRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VendorsImport;
use App\Exports\VendorsExport;
use Validator;
use Illuminate\Validation\Rule;


class VendorController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:supplier-list|supplier-create|supplier-edit|supplier-delete|supplier-show
          ', ['only' => ['index', 'show']]);
         $this->middleware('permission:supplier-list', ['only' => ['index']]);
         $this->middleware('permission:supplier-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:supplier-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
         $this->middleware('permission:supplier-show', ['only' => ['show']]);
     }


    public function index()
    {
        return $this->handleException(function () {
            $vendors = VendorResource::collection(Vendor::all());

            return view('back.vendors.index', compact('vendors'));
        });
    }

    public function filterVendors(Request $req)
    {
        $query = Vendor::with('user');

        $filters = $req->all();

        if (isset($filters['name'])) {
            $name = $filters['name'];
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
        if (isset($filters['email'])) {
            $email = $filters['email'];
            $query->whereHas('user', function ($query) use ($email) {
                $query->where('email', $email);
            });
        }
        if (isset($filters['phone'])) {
            $contact_no = $filters['phone'];
            $query->whereHas('user', function ($query) use ($contact_no) {
                $query->where('contact_no', $contact_no);
            });
        }


        $vendors = $query->get();

        return view('back.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate( [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'contact_no' => 'required',
            'country' => 'required',
            'city' =>'required',
            'tax_number' => 'required',
            'address' => 'required',
            'country_code' => 'required',
            'state_code' => 'required',
            'postal_code' => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'contact_no.required' => 'The phone number field is required.',
            'country.required' => 'The country field is required.',
            'city.required' => 'The city field is required.',
            'tax_number.required' => 'The tax number field is required.',
            'address.required' => 'The address field is required.',
        ]);

        $data = $request->all(); 
   
        $data['password'] = Hash::make('password');

        $role = 'Vendor';
        if (!Role::where('name', $role)->exists()) {
            return redirect()->back()->with('error', 'Role does not exist.');
        }

        $data['type'] = "Vendor";
        // dd($data);
        $user = User::create($data);
        $user->assignRole($role);
        $data['created_by'] = auth()->id();
        $user->vendor()->create($data);

        // $vendor = Vendor::create($data);
        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        $vendor->load('user');
        return $this->handleException(function () use ($vendor) {
            $vendor = new VendorResource($vendor);

            return view('back.vendors.edit', compact('vendor'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
    

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $vendor->user->id,
            'password' => 'nullable|string|min:8',
            'address' => 'nullable|string|max:455',
            'contact_no' => 'nullable|numeric',
            'company_name' => 'nullable',
            'status' => 'nullable',
            'country' => 'nullable',
            'city' => 'nullable',
            'tax_number' => 'nullable',
            'country_code' => 'nullable',
            'state_code' => 'nullable',
            'postal_code' => 'nullable',

        ]);

        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $vendor->user->update($data);
        $data['updated_at'] = auth()->id();
        $vendor->update($data);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->user->delete();
        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor deleted successfully');
    }

    public function change_status(Vendor $vendor)
    {

        if ($vendor->status == 1) {
            $vendor->status = 0;
            $message = trans('InActive Successfully');
        } else {
            $vendor->status = 1;
            $message = trans('Active Successfully');
        }
        $vendor->save();
        return response()->json($message);
    }
    public function change_blacklistStatus(Vendor $vendor)
    {

        if ($vendor->blacklist == 1) {
            $vendor->blacklist = 0;
            $message = trans('Unblock Successfully!');
        } else {
            $vendor->blacklist = 1;
            $message = trans('Block Successfully!');
        }
        $vendor->save();
        return response()->json($message);
    }


    public function blacklist()
    {
        $vendors = VendorResource::collection(Vendor::where('blacklist', '1')->get());
        // $vendors = Vendor::where('blacklist','1')->get();
        return view('back.vendors.blacklist', compact('vendors'));
    }

    public function deleteVendors(Request $req)
    {
        // dd($req->all());
        if ($req->ids) {
            foreach ($req->ids as $id) {
                $vendor = Vendor::with('user')->find($id);
                $vendor->user->delete();
                $vendor->delete();
            }
            return response()->json(['status' => 200, 'message' => 'Vendor deleted successfully!']);
        }
    }
    public function exportVendors(Request $request)
    {

        return Excel::download(new VendorsExport, 'Vendors.xlsx');
    }
    public function importView(Request $request)
    {
        return view('vendors.import');
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ]);
            $import = new VendorsImport;
            Excel::import($import, $request->file('file')->store('files'));


            if ($import->newVendorsCount() == 0) {
                return redirect()->back()->with('error', 'No new vendors were added');
            }

            // return redirect()->back()->with('success', $import->newVendorsCount() . ' new products added successfully');

            // Excel::import(new VendorsImport, $request->file('file'));

            return redirect()->route('vendors.index')
                ->with('success', $import->newVendorsCount() . ' New Vendors imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $error = [];
            foreach ($failures as $failure) {
                $error[] = 'Row ' . $failure->row() . ' ' . $failure->errors()[0];
            }

            return redirect()->route('vendors.index')
                ->with('error', implode('<br>', $error));
        } catch (QueryException $e) {
            // Check if it's a duplicate entry error
            if ($e->errorInfo[1] === 1062) {
                return redirect()->route('vendors.index')
                    ->with('error', 'Duplicate entry. This product already exists.');
            } else {
                // Handle other database errors as needed
                return redirect()->route('vendors.index')
                    ->with('error', 'An error occurred during import.');
            }
        }
    }
}
