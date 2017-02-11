<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(){
        $user=User::find(auth()->user()->id);
        $pageTitle = 'Редактирование профиля пользователя';
        $handler = route('profile.post');
        $method = 'PATCH';
        $this->addActionButton('Назад', route('index'), 'btn btn-default', 'fa-arrow-left');

        $data = array_merge(['component' => 'app-update-profile'], compact('pageTitle', 'method', 'handler', 'user'));
        return view('users.update', $data);

    }
    public function profile_post(ProfileRequest $request){
        $user=auth()->user();
        $data = $request->only([
            'first_name',
            'last_name',
            'middle_name',
        ]);
        if(auth()->user()->email!=$request->get('email')){

            $user->email=$request->get('email');
        }
        if($request->get('password')){

            $this->validate($request, [
                'old_password' => 'required',
            ],[], [
                'old_password' => 'Старый Пароль'
            ]);
            if(\Hash::check($request->get('old_password'), auth()->user()->password)==false){
                $this->addToastr('error', 'Введенный старый пароль не совпадает с текущим', 'Ошибка');
                return [
                    'data' => [
                        'redirect' => route('profile'),
                    ],
                ];

            }
            else{
                $user->password=\Hash::make($request->get('password'));
            }
        }
        $user = $user->fill($data);
        if($user->wasImportantChanged()){
            foreach ($user->registeredFlats as $flat){
                $flat->active=false;
                $flat->save();
            }
        }
        $path = $this->saveImageReturnPath($user, $request);
        if ($path !== false) {
            $user->avatar_url = $path;
        }
        $user->save();


        $this->addToastr('success', 'Профиль обновлен', 'Успех');
        return [
            'data' => [
                'redirect' => route('profile'),
            ],
        ];

    }
    private function saveImageReturnPath(User $user, Request $request)
    {
        if ($request->input('avatar') !== null) {
            $data_arr = explode(',', $request->input('avatar'), 2);
            if (array_get($data_arr, 1, null) === null) {
                return false;
            }

            $oldPath = $user->avatar_url;
            $image = Image::make(base64_decode($data_arr[1]));
            $filename = $user->id . '-' . str_random(3);
            $path = '/img/avatars/' . $filename . "." . $image->extension;

            $image->getWidth() / $image->getHeight() > 1 ? $image->widen(200) : $image->heighten(200);

            $image
                ->crop(200, 200)
                ->save(public_path($path));

            if ($oldPath !== null) {
                @unlink(public_path($oldPath));
            }

            return $path;
        }

        return null;
    }
}
