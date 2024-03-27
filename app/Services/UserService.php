<?php

namespace App\Services;

use App\Exceptions\AuthenticationException;
use App\Http\Resources\Collections\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Place;
use App\Models\Role;
use App\Models\User;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{
    use ResponseHandler;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function signUp(Request $request): JsonResponse
    {
        $role = Role::find($request->get('role_id'));
        $place = Place::find($request->get('place_id'));

        $password = $request->get('password');
        $name = $request->get('name');
        $email = $request->get('email');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        $user->roles()->attach($role);

        $user->places()->attach($place);

        return $this->success([
            'message' => 'Пользователь успешно создан и может использовать указанные данные для входа'
        ]);
    }

    /**
     * @throws AuthenticationException
     */
    public function signIn(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $auth = Auth::user();
            $token = $auth->createToken('TiresApp')->plainTextToken;

            return $this->success([
                'token' => $token,
                'message' => $auth->name . ', вы успешно вошли в систему.',
                'user' => new UserResource($auth->load(['roles', 'places']))
            ]);

        } else {
            $exception = new AuthenticationException();
            return $this->error($exception, null, 401);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $name = $request->user()->name;
        $request->user()->tokens()->delete();
        return $this->success([
            'message' => $name . ', вы успешно вышли из системы.'
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function users(): JsonResponse
    {
        if (\request()->query->has('edit')) {
            $users = User::select(['id', 'name', 'email'])
                ->with(['history', 'places', 'roles'])
                ->get();
        } else {
            $users = User::select(['id', 'name', 'email'])
                ->whereNot('id', \request()->user()->id)
                ->get();
        }

        return $this->success(new UserCollection($users));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        try {
            $name = $request->get('name');
            $email = $request->get('email');
            $role_id = $request->get('role_id');

            $user->update([
                'name' => $name,
                'email' => $email
            ]);

            $user->roles()->sync([$role_id]);

            return $this->success(['message' => 'Данные успешно обновлены']);
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }
}
