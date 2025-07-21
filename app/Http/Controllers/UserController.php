<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\WashHistory;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
// use App\Mail\SubscriptionSuccessTemplate; // if needed for email
// use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            // ✅ Validate basic fields first (without unique checks)
            $validated = $request->validate([
                'firstname'   => 'required|string|max:255',
                'lastname'    => 'required|string|max:255',
                'phone'       => 'required|string|max:20',
                'email'       => 'required|email',
                'password'    => 'required|string|min:6',
                'address'     => 'required|string',
                'carName'     => 'required|string',
                'carModel'    => 'required|string',
                'plateNumber' => 'required|string',
            ]);

            $email = $validated['email'];

            // ✅ Check if email exists in Admin table
            if (Admin::where('email', $email)->exists()) {
                info("❌ Registration failed: Email already exists in Admin table → {$email}");
                return response()->json([
                    'message' => 'This email is already registered as an admin.'
                ], 400);
            }

            // ✅ Check if email exists in User table
            if (User::where('email', $email)->exists()) {
                info("❌ Registration failed: Email already exists in User table → {$email}");
                return response()->json([
                    'message' => 'This email is already registered as a user.'
                ], 400);
            }

            // ✅ Create new user with hashed password
            $user = User::create([
                'firstname'   => $validated['firstname'],
                'lastname'    => $validated['lastname'],
                'phone'       => $validated['phone'],
                'email'       => $validated['email'],
                'password'    => Hash::make($validated['password']),
                'role'        => 'user',
                'address'     => $validated['address'],
                'carName'     => $validated['carName'],
                'carModel'    => $validated['carModel'],
                'plateNumber' => $validated['plateNumber'],
            ]);

            info("✅ User registered successfully → ID: {$user->id}, Email: {$user->email}");

            return response()->json([
                'message' => 'User registered successfully',
                'user'    => $user
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ Detailed validation errors for frontend
            $errors = $e->errors();
            \Log::warning('⚠️ Validation failed: ' . json_encode($errors));

            return response()->json([
                'message' => implode(' | ', collect($errors)->flatten()->toArray()), // single readable string
                'errors'  => $errors
            ], 422);

        } catch (\Exception $e) {
            // ✅ Catch unexpected errors
            \Log::error('❌ User registration error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Internal server error'
            ], 500);
        }
    }







    // ✅ Change Password
    public function changePassword(Request $request)
    {
        // ✅ Validate input with clear rules & custom messages
        $request->validate([
            'currentPassword' => 'required|string',
            'newPassword'     => 'required|string|min:6',
        ], [
            'currentPassword.required' => 'Current password is required.',
            'newPassword.required'     => 'New password is required.',
            'newPassword.min'          => 'New password must be at least 6 characters long.',
        ]);

        // ✅ Get logged-in user from session
        $sessionUser = session('user');
        if (!$sessionUser || !isset($sessionUser['id'])) {
            return response()->json([
                'message' => 'You are not logged in. Please log in again.'
            ], 401);
        }

        // ✅ Fetch user from DB
        $user = User::find($sessionUser['id']);
        if (!$user) {
            Log::warning("⚠️ changePassword: Session user not found in DB. ID: " . $sessionUser['id']);
            return response()->json(['message' => 'User not found. Please contact support.'], 404);
        }

        // ✅ Verify current password
        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json([
                'message' => 'The current password you entered is incorrect.'
            ], 403);
        }

        // ✅ Save new password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        Log::info("✅ Password changed successfully for user: {$user->email}");

        return response()->json([
            'message' => 'Password updated successfully!'
        ], 200);

    }









    // ✅ Get User By ID
    public function getById($id)
    {
        try {
            // Find user by ID, automatically hides password due to $hidden in model
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            return response()->json($user, 200);

        } catch (\Exception $e) {
            \Log::error('❌ Get user by ID error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch user'], 500);
        }
    }








    // Update User
    public function updateUser(Request $request)
    {
        try {
            // ✅ Get currently logged-in user from session
            $loggedUser = session('user');

            if (!$loggedUser) {
                info('❌ Unauthorized attempt to update profile - no logged-in session');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // ✅ Find the user model
            $user = User::find($loggedUser['id']);
            if (!$user) {
                info("❌ Session has invalid user ID: {$loggedUser['id']}");
                return response()->json(['error' => 'Session user not found'], 404);
            }

            // ✅ Validate incoming request
            $validated = $request->validate([
                'firstname'   => 'required|string|max:255',
                'lastname'    => 'required|string|max:255',
                'phone'       => 'required|string|max:20',
                'email'       => 'required|email|max:255',
                'address'     => 'required|string|max:255',
                'carName'     => 'required|string|max:255',
                'carModel'    => 'required|string|max:255',
                'plateNumber' => 'required|string|max:50',
            ]);

            info('✅ Validation passed for updating user ID: ' . $user->id);

            // ✅ Check if email is used by another user (exclude current user)
            $existingUser = User::where('email', $validated['email'])
                                ->where('id', '!=', $user->id)
                                ->first();
            if ($existingUser) {
                info('❌ Email already used by another user: ' . $validated['email']);
                return response()->json(['error' => 'Email is already used by another user'], 400);
            }

            // ✅ Check if email is used by any admin
            $existingAdmin = Admin::where('email', $validated['email'])->first();
            if ($existingAdmin) {
                info('❌ Email already used by an admin: ' . $validated['email']);
                return response()->json(['error' => 'Email is already used by an admin'], 400);
            }

            // ✅ Update user model
            $user->update([
                'firstname'   => $validated['firstname'],
                'lastname'    => $validated['lastname'],
                'phone'       => $validated['phone'],
                'email'       => $validated['email'],
                'address'     => $validated['address'],
                'carName'     => $validated['carName'],
                'carModel'    => $validated['carModel'],
                'plateNumber' => $validated['plateNumber'],
            ]);

            // ✅ Refresh session with updated data
            session(['user' => $user->toArray()]);

            info("✅ Profile updated successfully for user ID: {$user->id}");

            return response()->json([
                'message' => 'Profile updated successfully',
                'success' => true
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors explicitly handled
            info('❌ Validation failed: ' . json_encode($e->errors()));
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Any unexpected errors
            info('❌ Unexpected error while updating profile: ' . $e->getMessage());
            return response()->json([
                'error' => 'Something went wrong while updating profile',
                'details' => $e->getMessage()
            ], 500);
        }
    }








    // Toggle auto billing
    public function toggleAutoBilling(Request $request)
    {
        info('⚠️ fdofdfnkjdsfnk');
        try {
            $sessionUser = session('user');

            if (!$sessionUser) {
                \Log::warning('⚠️ No session user found when toggling auto billing');
                return response()->json([
                    'success' => false,
                    'message' => 'User not logged in'
                ]);
            }

            $user = User::find($sessionUser['id']);
            if (!$user) {
                \Log::warning("⚠️ User ID {$sessionUser['id']} from session not found in DB");
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // ✅ Check if frontend sent a specific status (true/false)
            $requestedStatus = $request->input('status');

            if (!is_bool($requestedStatus)) {
                // If invalid, just toggle
                $requestedStatus = !$user->auto_billing;
            }

            // ✅ Update DB with new status
            $user->autoBilling = $requestedStatus;
            $user->save();

            // ✅ Update session
            $sessionUser['auto_billing'] = $requestedStatus;
            session(['user' => $sessionUser]);

            return response()->json([
                'success' => true,
                'message' => $requestedStatus
                    ? '✅ Auto billing has been ENABLED. You will now be charged automatically monthly.'
                    : '❌ Auto billing has been DISABLED. You will need to renew manually.',
                'autoBilling' => $requestedStatus
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ Toggle auto billing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error. Please try again later.'
            ], 500);
        }
    }









    // Fetch user current Subscription
    public function fetchMyCurrentSubscription()
    {
        try {
            // ✅ Get currently logged-in user from session
            $user = session('user');

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            // ✅ Fetch latest active subscription for this user
            $subscription = Subscription::where('userId', $user['id'])
                ->where('status', 'active')
                ->orderBy('startDate', 'desc')
                ->first();

            if (!$subscription) {
                return response()->json([
                    'message' => 'No active subscription found.'
                ], 404);
            }

            return response()->json($subscription, 200);

        } catch (\Exception $e) {
            \Log::error('❌ Error fetching subscription: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server error fetching subscription'
            ], 500);
        }
    }








    // Initiate paystack payment
    public function initiatePayment(Request $request)
    {
        info('Got here at least');
        try {
            $plan = $request->input('plan');

            // Get the logged-in user
            $user = session('user');
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Plan prices (you can also load from config or DB)
            $planPrices = [
                'basic'   => env('BASIC_PRICE'),
                'premium' => env('PREMIUM_PRICE'),
                'complex' => env('COMPLEX_PRICE'),
            ];

            // Validate plan
            if (!isset($planPrices[$plan])) {
                return response()->json(['message' => 'Invalid plan selected'], 400);
            }

            // Prepare Paystack request payload
            $payload = [
                'email' => $user['email'],
                'amount' => $planPrices[$plan] * 100, // amount in kobo
                'metadata' => [
                    'plan' => $plan,
                    'userId' => $user['id'],
                    'custom_fields' => [
                        [
                            'display_name' => 'Plan',
                            'variable_name' => 'plan',
                            'value' => $plan
                        ],
                        [
                            'display_name' => 'Company',
                            'variable_name' => 'company',
                            'value' => 'Voeautocare'
                        ]
                    ]
                ],
                'callback_url' => env('BASE_URL') . '/user/payment/callback'
            ];

            // Call Paystack API
            $paystackRes = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', $payload);

            if (!$paystackRes->successful()) {
                return response()->json(['message' => 'Paystack initialization failed'], 500);
            }

            $checkoutUrl = $paystackRes->json()['data']['authorization_url'];

            return response()->json(['checkoutUrl' => $checkoutUrl]);

        } catch (\Exception $e) {
            \Log::error('Payment initiation error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to initiate payment. Try again later.'], 500);
        }
    }









    // Handle webhook from Paystack. Normally we dey expect webhook as we did nothing to request for itW
    public function handleWebhook(Request $request)
    {
        $raw = $request->getContent();
        $signature = $request->header('x-paystack-signature');

        // Verify Paystack signature
        $expectedSignature = hash_hmac('sha512', $raw, env('PAYSTACK_SECRET_KEY'));
        if (!$signature || $signature !== $expectedSignature) {
            Log::warning('❌ Invalid Paystack signature! Possible spoofed request.');
            return response()->json(['message' => 'Invalid signature'], 400);
        }


        // Raw payload and JSON decode
        $event = json_decode($raw, true);
        info($event);

        if (!$event) {
            Log::warning('⚠️ Invalid JSON payload in webhook');
            return response()->json(['message' => 'Invalid JSON'], 400);
        }

        $reference = data_get($event, 'data.reference');
        $metadata = data_get($event, 'data.metadata', []);
        $paidAt = data_get($event, 'data.paid_at');
        $eventName = data_get($event, 'event');

        // Validate required data
        if (!$reference || empty($metadata['userId']) || empty($metadata['plan'])) {
            Log::warning('⚠️ Missing required metadata or reference');
            return response()->json(['message' => 'Missing required data'], 400);
        }

        $userId = $metadata['userId'];
        $plan = $metadata['plan'];
        $previousReference = $metadata['previousReference'] ?? null;
        $autoRenew = $metadata['autoRenew'] ?? false;

        // Plan prices from env (cast to float)
        $planPrices = [
            'basic' => floatval(env('BASIC_PRICE', 0)),
            'premium' => floatval(env('PREMIUM_PRICE', 0)),
            'complex' => floatval(env('COMPLEX_PRICE', 0)),
        ];

        if (!isset($planPrices[$plan])) {
            Log::warning('⚠️ Invalid plan received in webhook: ' . $plan);
            return response()->json(['message' => 'Invalid plan'], 400);
        }

        $price = $planPrices[$plan];

        // Prevent duplicate processing (unique reference)
        if (Subscription::where('reference', $reference)->exists()) {
            Log::info("⚠️ Duplicate webhook: {$reference} already processed");
            return response()->json(['message' => 'Duplicate'], 200);
        }

        $user = User::find($userId);
        if (!$user) {
            Log::warning("⚠️ User not found for webhook reference: {$reference}");
            return response()->json(['message' => 'User not found'], 404);
        }

        switch ($eventName) {
            case 'charge.success':
                $auth = data_get($event, 'data.authorization', []);
                $customer = data_get($event, 'data.customer', []);

                $authorizationCode = $auth['authorization_code'] ?? null;
                $cardType = $auth['card_type'] ?? null;
                $last4 = $auth['last4'] ?? null;
                $expMonth = $auth['exp_month'] ?? null;
                $expYear = $auth['exp_year'] ?? null;
                $customerEmail = $customer['email'] ?? null;
                $customerCode = $customer['customer_code'] ?? null;

                // Calculate next billing date (+30 days)
                $nextBillingDate = Carbon::now()->addDays(30)->toDateTimeString();

                DB::beginTransaction();
                try {
                    // Mark previous subscription lost if auto-renew
                    if ($autoRenew && $previousReference) {
                        Subscription::where('reference', $previousReference)
                            ->update(['status' => 'lost']);
                    } else {
                        // Manual purchase → mark any active sub as lost
                        Subscription::where('userId', $userId)
                            ->where('status', 'active')
                            ->update(['status' => 'lost']);
                    }

                    // Create new subscription record
                    $newSub = Subscription::create([
                        'userId' => $userId,
                        'plan' => $plan,
                        'price' => $price,
                        'status' => 'active',
                        'startDate' => $paidAt ? Carbon::parse($paidAt)->toDateTimeString() : Carbon::now()->toDateTimeString(),  
                        'nextBillingDate' => $nextBillingDate,
                        'reference' => $reference,
                    ]);

                    // Update user billing info
                    $user->update([
                        'status' => 'active',
                        'authorizationCode' => $authorizationCode,
                        'cardType' => $cardType,
                        'last4' => $last4,
                        'expMonth' => $expMonth,
                        'expYear' => $expYear,
                        'customerCode' => $customerCode,
                    ]);

                    DB::commit();

                    // Send confirmation email (dispatch job or send directly)
                    // Assuming you have a Mailable class: SubscriptionSuccessMail
                    // Mail::to($user->email)->send(new \App\Mail\SubscriptionSuccessMail(
                    //     $user->firstname, $plan, $nextBillingDate
                    // ));

                    Log::info("✅ Subscription created & email sent for user: {$user->email}, plan: {$plan}");
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("❌ Subscription creation failed: {$e->getMessage()}");
                    return response()->json(['message' => 'Subscription creation failed'], 500);
                }

                break;

            case 'charge.failed':
            case 'charge.abandoned':
            case 'charge.reversed':
                $fallbackStatus = explode('.', $eventName)[1] ?? 'failed';

                try {
                    Subscription::create([
                        'userId' => $userId,
                        'plan' => $plan,
                        'price' => $price,
                        'status' => $fallbackStatus,
                        'startDate' => Carbon::now(),
                        'reference' => $reference,
                    ]);

                    // Optional: send failure email (SubscriptionFailedMail)
                    // Mail::to($user->email)->send(new \App\Mail\SubscriptionFailedMail(
                    //     $user->firstname, $plan
                    // ));

                    Log::warning("⚠️ Subscription marked as {$fallbackStatus} for user {$user->email}");
                } catch (\Exception $e) {
                    Log::error("❌ Failed to log failed subscription: {$e->getMessage()}");
                }

                break;

            default:
                Log::info("ℹ️ Ignored event: {$eventName}");
                break;
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }








    // Now instead of using the callback data given to us, we use the callback chance to send to paystack to verify from their end.
    public function handlePaymentCallback(Request $request)
    {
        $reference = $request->query('reference');

        try {
            // Verify transaction with Paystack API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            if ($response->successful()) {
                $status = data_get($response->json(), 'data.status');

                // Redirect regardless of timing
                if ($status === 'success') {
                    return redirect()->route('dashboard.page', ['page' => 'subscription-processing', 'reference' => $reference]);
                } else {
                   return redirect()->route('dashboard.page', ['page' => 'subscription-processing', 'reference' => $reference]);

                    // Or redirect to failed page:
                    // return redirect()->route('dashboard.subscriptionFailed');
                }
            } else {
                Log::error('Paystack verification failed: ' . $response->body());
                return redirect()->route('dashboard.page', ['page' => 'subscription-failed']);
            }
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return redirect()->route('dashboard.page', ['page' => 'subscription-failed']);
        }
    }








    // Checking if webhook has stored records in the database so as to say ALL good.
    public function checkSubscriptionStatus($reference)
    {
        try {
            $subscription = Subscription::where('reference', $reference)->first();

            if (!$subscription) {
                return response()->json(['status' => 'inactive']);
            }

            // If subscription is active, you may want to send a confirmation email
            if ($subscription->status === 'active') {
                $user = session('user'); // ✅ fetch user from session

                if ($user) {
                    // Example if you later want to send mail
                    // Mail::to($user['email'])->send(new SubscriptionSuccessMail($user['firstname'], $subscription->plan));
                }
            }

            return response()->json([
                'status' => $subscription->status,
                'plan' => $subscription->plan,
                'nextBillingDate' => $subscription->nextBillingDate, // make sure DB column matches
            ]);

        } catch (\Exception $e) {
            \Log::error('Error checking subscription: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Server error'
            ], 500);
        }
    }









    // User intentionally cancels subscription
    public function cancelSubscription(Request $request) {
        try {
            // ✅ Get logged-in user from session
            $sessionUser = session('user');

            if (!$sessionUser) {
                return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
            }

            $userId = $sessionUser['id']; // since session is usually an array

            // ✅ Find latest active subscription for this user
            $subscription = Subscription::where('userId', $userId)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$subscription) {
                Log::warning("No active subscription found to cancel for user ID: {$userId}");
                return response()->json(['message' => 'No active subscription to cancel'], 404);
            }

            // ✅ Mark subscription as canceled
            $subscription->status = 'canceled';
            $subscription->save();

            // ✅ Also update user's status
            $user = User::find($userId);
            if ($user) {
                $user->status = 'inactive';
                $user->save();
            }

            Log::info("✅ Subscription {$subscription->id} canceled for user ID: {$userId}");

            return response()->json(['message' => 'Subscription canceled successfully!']);

        } catch (\Exception $e) {
            Log::error("❌ Cancel subscription error: " . $e->getMessage());
            return response()->json(['error' => 'Server error. Please try again.'], 500);
        }
    }










    // Fetch Subscription History
    public function fetchSubscriptionHistory(Request $request)
    {
        try {
            // ✅ Get logged-in user from session
            $user = session('user');
            if (!$user || !isset($user['id'])) {
                return response()->json(['error' => 'User not logged in'], 401);
            }
            $userId = $user['id']; // Get user ID from session

            $page  = (int) $request->query('page', 1);
            $limit = (int) $request->query('limit', 5);
            $search = strtolower(trim($request->query('search', '')));

            // ✅ Base query: subscriptions belonging to logged-in user
            $query = Subscription::with('user:id,firstname')
                ->where('userId', $userId)   // <-- make sure your DB column is `userId`
                ->orderBy('created_at', 'desc');

            // ✅ If search term provided, filter
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(plan) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(status) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->whereRaw('LOWER(firstname) LIKE ?', ["%{$search}%"]);
                    });

                    // ✅ Numeric price exact match if search is numeric
                    if (is_numeric($search)) {
                        $q->orWhere('price', '=', $search);
                    }
                });
            }

            // ✅ Pagination
            $subscriptions = $query->paginate($limit, ['*'], 'page', $page);

            // ✅ Map results
            $history = $subscriptions->map(function ($sub) {
                return [
                    'id'              => $sub->id,
                    'firstname'       => $sub->user->firstname ?? '',
                    'plan'            => $sub->plan,
                    'status'          => $sub->status,
                    'price'           => $sub->price,
                    'startDate'       => $sub->startDate,
                    'nextBillingDate' => $sub->nextBillingDate,
                    'created_at'      => $sub->created_at,
                ];
            });

            return response()->json([
                'data'        => $history,
                'currentPage' => $subscriptions->currentPage(),
                'totalPages'  => $subscriptions->lastPage(),
                'totalItems'  => $subscriptions->total(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching subscription history: '.$e->getMessage());
            return response()->json(['error' => 'Failed to fetch history'], 500);
        }
    }









    // Fetch wash history
    public function getUserWashHistory(Request $request)
    {
        try {
            // ✅ Get logged-in user from session
            $loggedUser = session('user');

            if (!$loggedUser || !isset($loggedUser['email'])) {
                info('❌ Unauthorized attempt to fetch wash history - no valid session');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $email = $loggedUser['email'];
            info("✅ Fetching wash history for user email: {$email}");

            // ✅ Pagination & search parameters
            $page   = $request->query('page', 1);
            $limit  = $request->query('limit', 10);
            $search = trim($request->query('search', ''));

            // ✅ Base query: only fetch history belonging to this user
            $query = WashHistory::where('email', $email);

            // ✅ Apply search filters if provided
            if ($search !== '') {
                info("🔍 Applying search filter: {$search}");
                $query->where(function ($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('carName', 'like', "%{$search}%")
                    ->orWhere('carModel', 'like', "%{$search}%")
                    ->orWhere('washedBy', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
                });
            }

            // ✅ Get paginated results sorted by newest first
            $history = $query->orderBy('created_at', 'desc')
                            ->paginate($limit, ['*'], 'page', $page);

            info("✅ Found {$history->total()} wash history records for {$email}");

            // ✅ Prepare JSON response
            return response()->json([
                'data'         => $history->items(),
                'totalPages'   => $history->lastPage(),
                'currentPage'  => $history->currentPage(),
            ]);

        } catch (\Exception $e) {
            info('❌ Error fetching wash history: ' . $e->getMessage());
            return response()->json([
                'error' => 'Something went wrong while fetching wash history',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}