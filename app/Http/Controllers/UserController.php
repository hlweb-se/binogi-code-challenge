<?php

namespace App\Http\Controllers;

use App\Mappers\UserMapper;
use App\Models\User\User;
use App\Repositories\UserRepository;
use App\Support\Requests\UserStoreRequest;
use App\Support\Requests\UserUpdateRequest;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Request;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository, private UserMapper $userMapper)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     tags={"Users"},
     *     summary="Show user data",
     *     description="Fetch all data for given user and return it as a json object.",
     *     @OA\Parameter(
     *          name="user",
     *          in="path",
     *          description="ID of user",
     *          required=true,
     *          example=1,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="User Details",
     *         @OA\JsonContent(ref="#/components/schemas/UserMapper"),
     *     ),
     *     @OA\Response(response=404, description="User or endpoint not found."),
     * )
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return \Response::json(
            $this->userMapper->single($user),
            200,
            []
        );
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Create user",
     *     description="Create a new user based on given data",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created",
     *         @OA\JsonContent(ref="#/components/schemas/UserMapper"),
     *     ),
     *     @OA\Response(response=400, description="User cannot be created"),
     *     @OA\Response(response=422, description="Failed validation of given params"),
     * )
     *
     * @param UserStoreRequest $request
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        // Update: Add Nickname and trim all given data to reduce the risk of user type-o
        $user = $this->userRepository->create([
            'nickname' => trim($request->get('nickname')),
            'name'     => trim($request->get('name')),
            'email'    => trim($request->get('email')),
            'password' => Hash::make(trim($request->get('password'))),
        ]);

        return \Response::json($this->userMapper->single($user));
    }


    /**
     * @OA\Put(
     *     path="/api/users/{user}",
     *     tags={"Users"},
     *     summary="Update user",
     *     description="Update a given user with the provided data",
     *     @OA\Parameter(
     *          name="user",
     *          in="path",
     *          description="ID of user",
     *          required=true,
     *          example=1,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User after the update",
     *         @OA\JsonContent(ref="#/components/schemas/UserMapper"),
     *     ),
     *     @OA\Response(response=404, description="User or endpoint not found."),
     *     @OA\Response(response=422, description="Failed validation of given params"),
     * )
     *
     * @param UserUpdateRequest $request
     * @param User              $user
     *
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        // Fix: Avoid overwriting existing data if not providing all parameters.
        $data = [
            'nickname' => trim($request->input('nickname')) ?: $user->nickname,
            'name'     => trim($request->input('name')) ?: $user->name,
            'email'    => trim($request->input('email')) ?: $user->email,
            'password' => Hash::make(trim($request->input('password')) ?: $user->password),
        ];

        $user->fill($data)->save();

        return \Response::json($this->userMapper->single($user));
    }
}
