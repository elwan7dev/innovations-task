<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateproductRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = User::paginate();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email ?? null,
            'phone' => $request->phone ?? null,
            'address' => $request->address ?? null,
            'password' => Hash::make($request->password)
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'item saved successfully...!',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'message' => 'item retrieved successfully...!',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $isUpdated = $user->update([
            'name' => $request->name,
            'email' => $request->email ?? null,
            'phone' => $request->phone ?? null,
            'address' => $request->address ?? null,
            'password' => $request->password
        ]);
        $user->syncRoles($request->role);
        return response()->json([
            'success' => (bool)$isUpdated,
            'message' => $isUpdated ? 'item updated successfully...!' : 'something wrong..!',
            'data' => new UserResource($user)
        ], $isUpdated ? 200 : 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $deleted = $user->delete();
        return response()->json([
            'success' => (bool) $deleted,
            'message' => $deleted ? 'item deleted successfully...!' : 'something wrong..!',
        ], $deleted ? 200 : 404);
    }
}
