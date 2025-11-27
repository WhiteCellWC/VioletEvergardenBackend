<?php

namespace Modules\User\Http\Controller\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\User\Action\User\CreateUserAction;
use Modules\User\Action\User\DeleteUserAction;
use Modules\User\Action\User\SearchUserAction;
use Modules\User\Action\User\UpdateUserAction;
use Modules\User\Contract\UserServiceInterface;
use Modules\User\Http\Resource\Backend\UserBackendResource;
use Throwable;

class UserController extends Controller
{
    public function __construct(
        protected SearchUserAction $searchUserAction,
        protected CreateUserAction $createUserAction,
        protected UpdateUserAction $updateUserAction,
        protected DeleteUserAction $deleteUserAction,
        protected UserServiceInterface $userService
    ) {}

    public const parentPath = 'User';

    public const indexPath = self::parentPath . '/Index';

    public const editPath = self::parentPath . '/Edit';

    public const createPath = self::parentPath . '/Create';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->searchUserAction->handle($request);

            return Inertia::render(self::indexPath, [
                'users' => UserBackendResource::collection($users)
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
