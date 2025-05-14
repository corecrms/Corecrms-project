<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CustomerResource;
use App\Models\Tier;
use App\Http\Controllers\BaseController;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsImport;
use App\Exports\ClientsExport;
use App\Models\CreditActivity;
use Validator;
use Illuminate\Validation\Rule;

class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:customer-list|customer-create|customer-edit|customer-delete|customer-show
          ', ['only' => ['index', 'show']]);
         $this->middleware('permission:customer-list', ['only' => ['index']]);
         $this->middleware('permission:customer-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:customer-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
         $this->middleware('permission:customer-show', ['only' => ['show']]);
     }


    public function index()
    {
        $clientRole = Role::where('name', 'Client')->first();

        $users = $clientRole ? $clientRole->users : collect();

        $customers = CustomerResource::collection(Customer::all());
        // dd($customers);
        $tiers = Tier::all();

        return view('back.customers.index', compact('customers', 'users','tiers'));
    }

    public function filterCustomers(Request $req)
    {
        $clientRole = Role::where('name', 'Client')->first();

        // $query = $clientRole ? $clientRole->users : collect();
        $query = $clientRole ? $clientRole->users : collect();

        if($clientRole->users()){

            $query = $clientRole->users();

            $filters = $req->all();

            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }
            if (isset($filters['email'])) {
                $query->where('email', $filters['email'] );

            }
            if (isset($filters['phone'])) {
                $query->where('contact_no', $filters['phone'] );

            }

            $users = $query->get();

            $customers = CustomerResource::collection(Customer::all());

            $tiers = Tier::all();
            return view('back.customers.index', compact('customers', 'users','tiers'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tiers = Tier::all();
        return view('back.customers.create',compact('tiers'));
    }

    /**
     * Store a newly status in storage.
     *
     */
    public function changeStatus(Request $request)
    {
        $user_id = $request->user_id;
        $stats = $request->status;
        $customer = Customer::where('user_id', $user_id)->first();

        if($customer->status==1){
            $customer->status=0;
            $message= trans('InActive Successfully');
        }
        else{
            $customer->status=1;
            $message= trans('Active Successfully');
        }
        $customer->save();
        return response()->json($message);
    }

    public function changeBlacklistStatus(Request $request)
    {
        $user_id = $request->user_id;
        $stats = $request->status;
        $customer = Customer::where('user_id', $user_id)->first();

        if($customer->blacklist==1){
            $customer->blacklist=0;
            $message= trans('Unblock Successfully');
        }
        else{
            $customer->blacklist=1;
            $message= trans('Block Successfully');
        }
        $customer->save();
        return response()->json($message);
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
            'phone_no' => 'required',
            'country' => 'required',
            'city' =>'required',
            'tax_number' => 'nullable',
            'address' => 'required',
            'country_code' => 'required',
            'state_code' => 'required',
            'postal_code' => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'phone_no.required' => 'The phone number field is required.',
            'country.required' => 'The country field is required.',
            'city.required' => 'The city field is required.',
            'address.required' => 'The address field is required.',
        ]);

        // Use CustomerResource to format the response
        // $data = new CustomerResource($request->all());

        // dd($data);
        // Create a new user instance
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->contact_no = $request->input('phone_no');
        $user->address = $request->input('address');
        $user->password = Hash::make($request->input('email'));
        $user->type = "Customer";
        $user->country_code = $request->input('country_code');
        $user->state_code = $request->input('state_code');
        $user->postal_code = $request->input('postal_code');
        $user->address_line_2 = $request->input('address_line_2');

        if ($user->save()) {
            // Create a customer associated with the user
            $customer = new Customer();
            $customer->user_id = $user->id; // Assuming user_id is the foreign key in the Customer table
            $customer->tier_id = $request->tier_id ?? null;
            $customer->country = $request->country;
            $customer->city = $request->city;
            $customer->tax_number = $request->tax_number ?? null;
            $customer->business_name = $request->business_name ?? null;
            $customer->status = 0; // Set the default status to inactive
            $customer->save();

            $user->assignRole('Client');
            $customerResource = new CustomerResource($customer);

            return redirect()->route('customers.index')
                ->with('success', 'Customer created successfully')
                ->with('customerResource', $customerResource);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create user');
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
        $customer = Customer::where('user_id', $id)->with('user')->with('tiers')->first();

        $tiers = Tier::all();

        // Use the CustomerResource to format the response
        $customerResource = new CustomerResource($customer);

        return view('back.customers.edit', compact('customerResource', 'user','tiers'));
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
            'contact_no' => 'required',
            'country' => 'required',
            'city' =>'required',
            'address' => 'required',
            'country_code' => 'required',
            'state_code' => 'required',
            'postal_code' => 'required',
        ]);

        $input = $request->all();

        // if (!empty($input['password'])) {
        //     $input['password'] = Hash::make($input['password']);
        // }
        // else {
        //     $input = Arr::except($input, array('password'));
        // }

        $user = User::find($id);
        $user->update($input);
        $customer = Customer::where('user_id',$user->id)->first();

        $customer->tier_id = $request->tier_id ?? null;
        $customer->country = $request->country;
        $customer->city = $request->city;
        $customer->tax_number = $request->tax_number;
        $customer->business_name = $request->business_name;
        $customer->save();

        // Retrieve the updated user with the associated customer
        $updatedUser = User::with('customer')->find($id);

        // Use CustomerResource to format the response
        $customerResource = new CustomerResource($updatedUser->customer);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully')
            ->with('customerResource', $customerResource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        Customer::where('user_id', $id)->delete();
        User::destroy($id);

        // You can create a dummy customer object with the user data for the response
        $dummyCustomer = new CustomerResource(new Customer(['user' => User::find($id)]));

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }


    public function blacklist(){

        $users = CustomerResource::collection(Customer::where('blacklist','1')->get());
        // dd($users);
        return view('back.customers.blacklist',compact('users'));
    }


    public function deleteCustomers(Request $req){

        if($req->ids){
            Customer::whereIn('user_id',$req->ids)->delete();
            User::whereIn('id',$req->ids)->delete();
            return response()->json(['status' => 200,'message' => 'customer deleted successfully!']);

        }
    }
    public function exportCustomers(Request $request){

        return Excel::download(new ClientsExport, 'Clients.xlsx');
    }
    public function importView(Request $request){
        return view('clients.import');
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ]);
            $import = new ClientsImport;
            Excel::import($import, $request->file('file')->store('files'));


            if ($import->newCustomersCount() == 0) {
                return redirect()->back()->with('error', 'No new customers were added may be  imported customers already exists');
            }

            // return redirect()->back()->with('success', $import->newCustomersCount() . ' new products added successfully');

            // Excel::import(new CustomersImport, $request->file('file'));

            return redirect()->route('customers.index')
                ->with('success', $import->newCustomersCount() . ' New Customers imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $error = [];
            foreach ($failures as $failure) {
                $error[] = 'Row ' . $failure->row() . ' ' . $failure->errors()[0];
            }

            return redirect()->route('customers.index')
                ->with('error', implode('<br>', $error));
        } catch (QueryException $e) {
            // Check if it's a duplicate entry error
            if ($e->errorInfo[1] === 1062) {
                return redirect()->route('customers.index')
                    ->with('error', 'Duplicate entry. This product already exists.');
            } else {
                // Handle other database errors as needed
                return redirect()->route('customers.index')
                    ->with('error', 'An error occurred during import.');
            }
        }
    }

    public function addBalance(Request $request,$id)
    {
        $request->validate([
            'add_balance' => 'required|integer'
        ]);
        $customer = Customer::find($id);
        $customer->balance += $request->add_balance;
        $customer->save();

        CreditActivity::create([
            'customer_id' => $id,
            'action' => 'Modified',
            'credit_balance' => $customer->balance,
            'added_deducted' => $request->add_balance,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Balance added successfully!');

    }

    public function addCard(Request $request,$id){
        $request->validate([
            'card_brand' => 'required|string',
            'card_last_four' => 'required|numeric|digits:4',
            'card_exp_month' => 'required|numeric|digits:2',
            'card_exp_year' => 'required|numeric|digits:4',
        ],[
            'card_brand.required' => 'The card brand field is required.',
            'card_last_four.required' => 'The card last four field is required.',
            'card_last_four.numeric' => 'The card last four must be a number.',
            'card_last_four.digits' => 'The card last four must be 4 digits.',
            'card_exp_month.required' => 'The card expiry month field is required.',
            'card_exp_month.numeric' => 'The card expiry month must be a number.',
            'card_exp_month.digits' => 'The card expiry month must be 2 digits.',
            'card_exp_year.required' => 'The card expiry year field is required.',
            'card_exp_year.numeric' => 'The card expiry year must be a number.',
            'card_exp_year.digits' => 'The card expiry year must be 4 digits.',
        ]);

        $data = $request->all();

        $user = User::find($id);
        $user->savedCards()->create($data);

        return redirect()->back()->with('success', 'Card has been added successfully!');
    }

}
