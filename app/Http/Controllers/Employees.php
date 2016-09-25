<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Repositories\Contracts\UserRepository;
use App\Role;
use Hash;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Requests;

class Employees extends Controller
{
    public function __construct()
    {
        view()->share('active_users', 'active');
    }

    public function index()
    {
        $currentUser = auth()->user();
        abort_if($currentUser->isUser(), 403);

        $employees = User::where('company_id', $currentUser->company_id)->paginate(20);
        $pageTitle = 'Сотрудники';
        $this->addActionButton('Создать сотрудника', '/employees/create', 'btn btn-info', 'fa-plus');
        return view('employees.index', compact('employees', 'pageTitle'));
    }

    public function create()
    {
        abort_if(auth()->user()->isUser(), 403);

        $handler = route('employees.store');
        $method = 'POST';
        $pageTitle = 'Добавление сотрудника';
        $employee = new User();

        $this->addActionButton('Назад', route('employees.index'), 'btn btn-default', 'fa-arrow-left');

        $data = array_merge(['component' => 'app-employees'], compact('pageTitle', 'method', 'handler', 'employee'));
        return view('employees.manage', $data);
    }

    public function edit(User $employee)
    {
        $currentUser = auth()->user();
        abort_if($currentUser->isUser(), 403);
        abort_if((int)$currentUser->company_id !== (int)$employee->company_id, 403);

        $pageTitle = 'Редактирование сотрудника';
        $handler = route('employees.update', $employee);
        $method = 'PATCH';
        $this->addActionButton('Назад', route('employees.show', $employee), 'btn btn-default', 'fa-arrow-left');

        $data = array_merge(['component' => 'app-employees'], compact('pageTitle', 'method', 'handler', 'employee'));

        return view('employees.manage', $data);
    }

    public function store(Requests\UserManage $request)
    {
        $currentUser = auth()->user();
        abort_if($currentUser->isUser(), 403);


        return $this->manage(new User, $request);
    }

    public function update(User $employee, Requests\UserManage $request)
    {
        $currentUser = auth()->user();
        abort_if($currentUser->isUser(), 403);
        abort_if((int)$currentUser->company_id !== (int)$employee->company_id, 403);

        return $this->manage($employee, $request);
    }

    public function stubAvatar($email)
    {
        return redirect(\Gravatar::src($email ?: 'guest', 200));
    }

    /**
     * @param User $employee
     * @param Requests\UserManage $request
     * @return null|string
     */
    private function saveImageReturnPath(User $employee, Requests\UserManage $request)
    {
        if ($request->input('avatar') !== null) {
            $data_arr = explode(',', $request->input('avatar'), 2);
            if (array_get($data_arr, 1, null) === null) {
                return false;
            }

            $oldPath = $employee->avatar_url;
            $image = Image::make(base64_decode($data_arr[1]));
            $filename = $employee->id . '-' . str_random(3);
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

    /**
     * @param User $employee
     * @param Requests\UserManage $request
     * @return array
     */
    protected function manage(User $employee, Requests\UserManage $request)
    {
        $currentUser = auth()->user();
        $data = $request->only([
            'first_name',
            'last_name',
            'middle_name',
            'email',
            'city',
        ]);

        $employee = $employee->fill($data);

        $created = $employee->exists;

        if (!$created) {
            $employee->role_id = Role::where('keyword', 'manager')->first()->id;
            $employee->company_id = $currentUser->company_id;
            $employee->save();
//            $employee->registered();
        }

        $path = $this->saveImageReturnPath($employee, $request);
        if ($path !== false) {
            $employee->avatar_url = $path;
        }

        $employeeContacts = $employee->contacts->keyBy('id');
        $ids = $employeeContacts->pluck('id');
        $savedContacts = collect($request->input('contacts', []));
        $savedIds = $savedContacts->pluck('id');
        $idsToRemove = $ids->diff($savedIds);

        $savedContacts->each(function ($contact) use ($employeeContacts, $employee) {
            if (array_get($contact, 'id', null)=== null) {
                $dbContact = new Contact([
                    'type' => $contact['type'],
                ]);
            } else {
                $dbContact = $employeeContacts->get(array_get($contact, 'id', null));
            }
            $dbContact->value = $contact['value'];
            $employee->contacts()->save($dbContact);
        });

        $idsToRemove->each(function ($contact) use ($employeeContacts, $employee) {
            $dbContact = $employeeContacts->get($contact, null);
            if ($dbContact == null) {
                return;
            }
            if ($dbContact->contactable_id != $employee->id) {
                return;
            }
            $dbContact->delete();
        });

        $employee->save();

        $this->addToastr('success', $created ? 'Пользователь сохранен' : 'Пользователь создан', 'Успех');
        return [
            'data' => [
                'redirect' => route('employees.index'),
            ],
        ];
    }
}
