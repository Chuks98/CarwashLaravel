<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WashHistory;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        try {
            // ✅ Validate basic fields (but NOT unique yet, we’ll handle manually)
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname'  => 'required|string|max:255',
                'phone'     => 'required|string|max:20',
                'email'     => 'required|email',
                'password'  => 'required|string|min:6',
            ]);

            $email = $validated['email'];

            // ✅ Check if any admin already exists
            if (Admin::count() > 0) {
                info("❌ Attempt to create a second admin account");
                return response()->json([
                    'message' => 'There is already an admin. Please register as a user.'
                ], 400);
            }

            // ✅ Check if email already exists in Admin table
            if (Admin::where('email', $email)->exists()) {
                info("❌ Registration failed: Email already exists in Admin table → {$email}");
                return response()->json([
                    'message' => 'This email is already registered as an admin.'
                ], 400);
            }

            // ✅ Check if email already exists in User table
            if (User::where('email', $email)->exists()) {
                info("❌ Registration failed: Email already exists in User table → {$email}");
                return response()->json([
                    'message' => 'This email is already registered as a user.'
                ], 400);
            }

            // ✅ Hash the password
            $hashedPassword = Hash::make($validated['password']);

            // ✅ Save admin in MySQL
            $admin = Admin::create([
                'firstname' => $validated['firstname'],
                'lastname'  => $validated['lastname'],
                'phone'     => $validated['phone'],
                'email'     => $validated['email'],
                'password'  => $hashedPassword,
                'role'      => 'admin',
            ]);

            info("✅ Admin registered successfully → ID: {$admin->id}, Email: {$admin->email}");

            return response()->json([
                'message' => 'Admin registered successfully'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            \Log::warning('⚠️ Validation failed: ' . json_encode($errors));

            return response()->json([
                'message' => implode(' | ', collect($errors)->flatten()->toArray()), // single readable string
                'errors'  => $errors
            ], 422);
        } catch (\Exception $e) {
            // ✅ Catch any other unexpected errors
            \Log::error('❌ Admin registration error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Internal server error'
            ], 500);
        }
    }





    // Get all registered users
    public function getAllUsers(Request $request)
    {
        try {
            // ✅ 1. Get logged-in admin session
            $loggedAdmin = session('user');
            $role        = session('role');        // should be 'admin'

            if (!$loggedAdmin || $role !== 'admin') {
                info('❌ Unauthorized attempt to update admin profile - no valid session');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // ✅ Pagination parameters
            $page = $request->query('page', 1);
            $limit = $request->query('limit', 5);

            // ✅ Filters
            $search = $request->query('search', '');
            $status = $request->query('status', '');

            // ✅ Start building query
            $query = User::query();

            // 🔍 Search filter (firstname, lastname, email, phone)
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('firstname', 'LIKE', "%$search%")
                      ->orWhere('lastname', 'LIKE', "%$search%")
                      ->orWhere('email', 'LIKE', "%$search%")
                      ->orWhere('phone', 'LIKE', "%$search%");
                });
            }

            // ✅ Status filter (active, inactive, etc.)
            if (!empty($status)) {
                $query->where('status', $status);
            }

            // ✅ Sorting:
            // - Active users first (statusOrder)
            // - Then by creation date descending
            $query->orderByRaw("CASE WHEN status = 'active' THEN 1 ELSE 0 END DESC")
                  ->orderBy('created_at', 'DESC');

            // ✅ Pagination with limit
            $users = $query->paginate($limit, ['*'], 'page', $page);

            // ✅ Remove password before returning
            $users->getCollection()->transform(function ($user) {
                unset($user->password);
                return $user;
            });

            return response()->json([
                'data' => $users->items(),
                'currentPage' => $users->currentPage(),
                'totalPages' => $users->lastPage(),
                'total' => $users->total()
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ getAllUsers error: '.$e->getMessage());
            return response()->json(['message' => 'Failed to retrieve users'], 500);
        }
    }




    public function getById($id)
    {
        try {
            // ✅ Find admin by ID (MySQL)
            $admin = Admin::find($id);

            // ✅ If not found
            if (!$admin) {
                return response()->json(['message' => 'Admin not found'], 404);
            }

            // ✅ Return JSON
            return response()->json($admin);

        } catch (\Exception $e) {
            // ✅ Handle any DB error
            \Log::error('❌ Get admin by ID error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to retrieve admin'], 500);
        }
    }





    public function updateAdmin(Request $request)
    {
        try {
            // ✅ 1. Get logged-in admin session
            $loggedAdmin = session('user');  // set during login
            $role        = session('role');        // should be 'admin'

            if (!$loggedAdmin || $role !== 'admin') {
                info('❌ Unauthorized attempt to update admin profile - no valid session');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // ✅ 2. Find fresh Admin model
            $admin = Admin::find($loggedAdmin['id']);
            if (!$admin) {
                info('❌ Session contains invalid admin ID: ' . $loggedAdmin['id']);
                return response()->json(['error' => 'Admin not found'], 404);
            }

            // ✅ 3. Validate request
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname'  => 'required|string|max:255',
                'phone'     => 'required|string|max:20',
                'email'     => 'required|email|max:255',
            ]);

            info('✅ Validation passed for updating admin ID: ' . $admin->id);

            // ✅ 4. Ensure email not used by any user
            $existingUser = User::where('email', $validated['email'])->first();
            if ($existingUser) {
                info('❌ Email already used by a user: ' . $validated['email']);
                return response()->json(['error' => 'Email is already used by a user'], 400);
            }

            // ✅ 5. Ensure email not used by another admin (excluding current)
            $existingAdmin = Admin::where('email', $validated['email'])
                                ->where('id', '!=', $admin->id)
                                ->first();

            if ($existingAdmin) {
                info('❌ Email already used by another admin: ' . $validated['email']);
                return response()->json(['error' => 'Email is already used by another admin'], 400);
            }

            // ✅ 6. Update admin profile
            $admin->update([
                'firstname' => $validated['firstname'],
                'lastname'  => $validated['lastname'],
                'phone'     => $validated['phone'],
                'email'     => $validated['email'],
            ]);

            // ✅ 7. Refresh session with updated admin data
            session(['user' => $admin->toArray(), 'role' => 'admin']);

            info('✅ Admin profile updated successfully for ID: ' . $admin->id);

            return response()->json([
                'message' => 'Profile updated successfully',
                'success' => true,
                'admin'   => $admin
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Catch validation errors explicitly
            info('❌ Admin profile validation failed: ' . json_encode($e->errors()));
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Catch any unexpected errors
            info('❌ Unexpected error while updating admin profile: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update profile'], 500);
        }
    }







    public function addWashHistory(Request $request)
    {
        // ✅ Validate incoming request (just like req.body)
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'carName' => 'required|string|max:255',
            'carModel' => 'required|string|max:255',
            'washedBy' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            // ✅ Save wash history to MySQL
            WashHistory::create($validated);

            info('Wash record added');
            return response()->json(['message' => 'Wash record added'], 201);

        } catch (\Exception $e) {
            \Log::error('Add wash history error: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to add record'], 500);
        }
    }






    public function getAllWashHistory(Request $request)
    {
        try {
            // ✅ Get logged-in user & role from session
            $loggedUser = session('user');
            $role       = session('role'); // 'admin' or 'user'

            if (!$loggedUser || $role !== 'admin') {
                info('❌ Access denied - Non-admin tried to fetch wash history');
                return response()->json(['error' => 'Access denied'], 403);
            }

            info("✅ Admin (ID: {$loggedUser['id']}) fetching wash history");

            // ✅ Pagination params
            $page   = (int) $request->query('page', 1);
            $limit  = (int) $request->query('limit', 5);
            $search = trim($request->query('search', ''));

            // ✅ Build query
            $query = WashHistory::query();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('firstname', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('carName', 'LIKE', "%{$search}%")
                    ->orWhere('carModel', 'LIKE', "%{$search}%")
                    ->orWhere('washedBy', 'LIKE', "%{$search}%")
                    ->orWhere('notes', 'LIKE', "%{$search}%");
                });
            }

            // ✅ Sort by latest created_at
            $query->orderBy('created_at', 'desc');

            // ✅ Get total count before pagination
            $total = $query->count();

            // ✅ Apply pagination
            $data = $query->skip(($page - 1) * $limit)
                        ->take($limit)
                        ->get();

            info("✅ Wash history fetched successfully | Total: {$total}, Page: {$page}");

            return response()->json([
                'data'        => $data,
                'currentPage' => $page,
                'totalPages'  => ceil($total / $limit),
                'totalItems'  => $total
            ]);

        } catch (\Exception $e) {
            info('❌ Error fetching wash history: ' . $e->getMessage());

            return response()->json([
                'message'   => 'Something went wrong while fetching wash history',
                'error'   => 'Something went wrong while fetching wash history',
                'details' => $e->getMessage()
            ], 500);
        }
    }








    public function updateWashHistory(Request $request)
    {
        try {
            // ✅ Validate incoming request
            $validated = $request->validate([
                'washId'    => 'required|integer|exists:wash_histories,id',
                'firstname' => 'required|string|max:255',
                'email'     => 'required|email',
                'carName'   => 'required|string|max:255',
                'carModel'  => 'required|string|max:255',
                'washedBy'  => 'required|string|max:255',
                'notes'     => 'nullable|string'
            ]);

            // ✅ Get logged-in user from session
            $loggedUser = session('user');
            $role       = session('role');

            if (!$loggedUser || $role !== 'admin') {
                info('❌ Unauthorized attempt to update wash history. No valid admin session.');
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            // ✅ Find wash history record
            $washHistory = WashHistory::find($validated['washId']);
            if (!$washHistory) {
                info("❌ Wash history with ID {$validated['washId']} not found.");
                return response()->json(['error' => 'Wash history not found'], 404);
            }

            // ✅ Update fields
            $washHistory->firstname = $validated['firstname'];
            $washHistory->email     = $validated['email'];
            $washHistory->carName   = $validated['carName'];
            $washHistory->carModel  = $validated['carModel'];
            $washHistory->washedBy  = $validated['washedBy'];
            $washHistory->notes     = $validated['notes'] ?? $washHistory->notes;

            $washHistory->save();

            info("✅ Wash history ID {$washHistory->id} updated successfully by admin ID {$loggedUser['id']}");

            return response()->json([
                'message' => 'Wash history updated successfully',
                'data'    => $washHistory
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors
            info('❌ Validation failed while updating wash history: ' . json_encode($e->errors()));
            return response()->json([
                'error'   => 'Validation failed',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Any unexpected error
            info('❌ Unexpected error while updating wash history: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }








    public function deleteWashHistory(Request $request, $id)
    {
        try {
            // ✅ Get logged-in user & role from session
            $loggedUser = session('user');   // from login session
            $role       = session('role');   // 'admin' or 'user'

            if (!$loggedUser || $role !== 'admin') {
                info('❌ Unauthorized delete attempt. No session or not admin.');
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            // ✅ Validate ID
            if (!$id) {
                info('❌ Delete attempt failed: No wash ID provided.');
                return response()->json(['error' => 'Wash ID is required'], 400);
            }

            // ✅ Find the wash history record
            $washHistory = \App\Models\WashHistory::find($id);

            if (!$washHistory) {
                info("❌ Wash record with ID {$id} not found for delete.");
                return response()->json(['error' => 'Wash record not found'], 404);
            }

            // ✅ Delete the record
            $washHistory->delete();

            info("✅ Wash record ID {$id} deleted successfully by admin ID {$loggedUser['id']}.");

            return response()->json(['message' => 'Wash record deleted successfully']);

        } catch (\Exception $e) {
            // ✅ Catch unexpected errors
            info("❌ Error while deleting wash record ID {$id}: " . $e->getMessage());

            return response()->json([
                'error'   => 'Something went wrong while deleting the wash record',
                'details' => $e->getMessage()
            ], 500);
        }
    }





    // ✅ Change Password
    public function changePassword(Request $request)
    {
        // ✅ 1. Validate request input
        $request->validate([
            'currentPassword' => 'required|string',
            'newPassword'     => 'required|string|min:6',
        ]);

        // ✅ 2. Fetch currently logged-in admin from session
        $sessionUser = session('user');
        if (!$sessionUser || !isset($sessionUser['email'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Admin session not found. Please log in again.'
            ], 401);
        }

        $admin = Admin::where('email', $sessionUser['email'])->first();
        if (!$admin) {
            Log::warning("❌ Admin not found in DB for email: {$sessionUser['email']}");
            return response()->json([
                'status'  => 'error',
                'message' => 'Admin account not found.'
            ], 404);
        }

        // ✅ 3. Verify current password
        if (!Hash::check($request->currentPassword, $admin->password)) {
            Log::warning("❌ Incorrect current password attempt for admin: {$admin->email}");
            return response()->json([
                'status'  => 'error',
                'message' => 'Your current password is incorrect.'
            ], 403);
        }

        // ✅ 4. Hash & update new password
        $admin->password = Hash::make($request->newPassword);
        $admin->save();

        Log::info("✅ Password changed successfully for admin: {$admin->email}");

        return response()->json([
            'status'  => 'success',
            'message' => 'Password updated successfully.'
        ]);
    }






    // Fetch all users subscription histories
    public function fetchSubscriptionHistory(Request $request)
    {
        try {
            // ✅ Get logged-in user from session
            $user = session('user');

            // ✅ Only allow admin access
            if (!$user || $user['role'] !== 'admin') {
                return response()->json(['error' => 'Access denied to fetch transaction history'], 403);
            }

            $page  = $request->query('page', 1);
            $limit = $request->query('limit', 5);
            $search = strtolower(trim($request->query('search', '')));

            // ✅ Base query with join
            $query = Subscription::with('user')

                // ✅ First order active > everything else
                ->orderByRaw("CASE WHEN status = 'active' THEN 1 ELSE 2 END ASC")

                // ✅ Then order by created_at (latest first)
                ->orderBy('created_at', 'desc');

            // ✅ Filtering if search exists
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('plan', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('firstname', 'like', "%$search%")
                            ->orWhere('lastname', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });

                    if (is_numeric($search)) {
                        $q->orWhere('price', $search);
                    }
                });
            }

            // ✅ Paginate results
            $subscriptions = $query->paginate($limit, ['*'], 'page', $page);

            // ✅ Format data
            $formatted = $subscriptions->map(function ($sub) {
                return [
                    'id'              => $sub->id,
                    'plan'            => $sub->plan,
                    'status'          => $sub->status,
                    'price'           => $sub->price,
                    'startDate'       => $sub->startDate,
                    'nextBillingDate' => $sub->nextBillingDate,
                    'firstname'       => $sub->user->firstname ?? '',
                    'lastname'        => $sub->user->lastname ?? '',
                    'email'           => $sub->user->email ?? '',
                ];
            });

            return response()->json([
                'data'        => $formatted,
                'currentPage' => $subscriptions->currentPage(),
                'totalPages'  => $subscriptions->lastPage(),
                'totalItems'  => $subscriptions->total()
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ Error fetching subscription history: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch subscription history'], 500);
        }
    }


}
