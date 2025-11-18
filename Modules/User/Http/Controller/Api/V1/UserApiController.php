<?php

namespace Modules\User\Http\Controller\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Action\User\CreateUserAction;
use Modules\User\Action\User\DeleteUserAction;
use Modules\User\Action\User\SearchUserAction;
use Modules\User\Action\User\UpdateUserAction;
use Modules\User\Contract\UserServiceInterface;
use Modules\User\Http\Request\Api\V1\StoreUserApiRequest;
use Modules\User\Http\Request\Api\V1\UpdateUserApiRequest;
use Modules\User\Http\Resource\Api\V1\UserApiResource;
use Throwable;

class UserApiController extends Controller
{
    public function __construct(
        protected SearchUserAction $searchUserAction,
        protected CreateUserAction $createUserAction,
        protected UpdateUserAction $updateUserAction,
        protected DeleteUserAction $deleteUserAction,
        protected UserServiceInterface $userService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->searchUserAction->handle($request);

            return UserApiResource::collection($users);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserApiRequest $request)
    {
        try {
            $user = $this->createUserAction->handle($request);

            return new UserApiResource($user);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->get($id);

            return new UserApiResource($user);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserApiRequest $request, string $id)
    {
        try {
            $user = $this->updateUserAction->handle($request, $id);

            return new UserApiResource($user);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $userName = $this->deleteUserAction->handle($id);

            return response()->json($userName);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}
