<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FillterUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * get all users
     *
     * @param FillterUserRequest $request 
     *
     * @return response  of the status of operation : users  
     */
    public function index(FillterUserRequest $request)
    {
        $user_name = $request->input('user_name');
        $role = $request->input('role');

        $fillter = ['user_name' => $user_name, 'role' => $role];
        $users = $this->userService->allUsers($fillter, false);
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' =>  $users
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * create a new uaser
     *
     * @param StoreUserRequest $request 
     *
     * @return response  of the status of operation : user and message
     */
    public function store(StoreUserRequest $request)
    {
        $userData = $request->validateWithCasts()->toArray();

        $user = $this->userService->createUser($userData);

        return response()->json([
            'status' => 'success',
            'message' => 'تم انشاء المستخدم بنجاح',
            'data' => [
                'user' =>  $user,
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * show a specified user
     *
     * @param User $user 
     *
     * @return response  of the status of operation : user 
     */
    public function show(User $user)
    {
        $data = $this->userService->oneUser($user);

        return response()->json([
            'status' => 'success',
            'data' => [
                ...$data
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * update a specified task
     * @param UpdateUserRequest $request
     * @param User $user
     *
     * @return response  of the status of operation : user and message 
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $userData = $request->validateWithCasts()->toArray();

        $user = $this->userService->updateUser($userData, $user);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المستخدم بنجاح',
            'data' => [
                'user' =>  $user
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */


    /**
     * delete a specified user
     * @param User $user 
     *
     * @return response  of the status of operation 
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return response()->json(status: 204);
    }
    /**
     * get all deleted users
     * @param FillterUserRequest $request
     * @return response  of the status of operation : users  
     */
    public function allDeletedUsers(FillterUserRequest $request)
    {
        $user_name = $request->input('user_name');
        $role = $request->input('role');
        $fillter = ['user_name' => $user_name, 'role' => $role];
        $users = $this->userService->allUsers($fillter, true);
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' =>  $users
            ],
        ], 200);
    }

    /**
     * restore a  user
     *
     * @param int $user_id 
     *
     * @return response  of the status of operation : user
     */
    public function restoreUser($user_id)
    {
        $user = $this->userService->restoreUser($user_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' =>  $user
            ],
        ], 200);
    }

    /**
     * force delete a user
     * 
     * @param int $user_id 
     *
     * @return response  of the status of operation 
     */

    public function forceDeleteUser($user_id)
    {
        $this->userService->forceDeleteUser($user_id);
        return response()->json(status: 204);
    }
}