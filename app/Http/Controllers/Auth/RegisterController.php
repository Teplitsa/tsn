<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Requests\NewCompany;
use App\Role;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return \App\Models\User::create([
            'first_name' => $data['first_name'],
            'last_name'  => '',
            'city'       => '',
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'role_id'    => Role::where('keyword', 'tenant')->first()->id,
        ]);
    }


    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        $this->addToastr('success',  'Вы зарегистрировались!' , 'Успех');
        $this->guard()->login($user);

        $house=$request->get('house');
        if(isset($house)){
            return ['redirect' => 'flats/attach?house='.$house];
        }
        return ['redirect' => $this->redirectTo];
    }

    public function newCompany()
    {
        return view('auth.new_company');
    }

    public function inn($inn)
    {

        if (strlen($inn) !== 10) {
            $this->addToastr('error', 'Введите ИНН юридического лица', 'Неверный ИНН');
            return [];
        }
        $client = new Client();
        try {
            $companies = $client->get('https://xn--c1aubj.xn--80asehdb/интеграция/компании/?инн=' . $inn)->getBody()->getContents();
        } catch (\Exception $e) {
            $this->addToastr('error', '1. Реестр ЕГРЮЛ временно недоступен' . $e->getMessage(),
                'Ошибка получения данных');
            return [];
        }

        $companies = json_decode($companies, true);
        if (count($companies) === 0) {
            $this->addToastr('error', 'Компания не найдена', 'Неверный ИНН');
            return [];
        }

        $companyId = $companies[0]['id'];

        try {
            $fullInfo = $client->get('https://xn--c1aubj.xn--80asehdb/интеграция/компании/' . $companyId . '/')
                ->getBody()->getContents();
            $fullInfo = json_decode($fullInfo, true);
        } catch (\Exception $e) {
            $this->addToastr('error', 'Реестр ЕГРЮЛ временно недоступен', 'Ошибка получения данных');
            return [];
        }

        if (isset($fullInfo['closeInfo'])) {
            $this->addToastr('error', 'Данное юридическое лицо исключено из ЕГРЮЛ', 'Данная организация закрыта');
            return [];
        }

        if ((int)$fullInfo['okopf']['code'] !== 20716) {
            $this->addToastr('error', 'Данное юридическое лицо не ТСЖ', 'Данная организация запрещена для регистрации');
            return [];
        }

        try {
            $persons = $client->get('https://xn--c1aubj.xn--80asehdb/интеграция/компании/' . $companyId . '/сотрудники/')
                ->getBody()->getContents();
            $persons = json_decode($persons, true);
        } catch (\Exception $e) {
            $this->addToastr('error', 'Реестр ЕГРЮЛ недоступен', 'Ошибка получения данных');
            return [];
        }

        $this->addToastr('success', 'Данные из ЕГРЮЛ успешно загружены', 'Данные загружены');
        return [
            'inn'         => $persons[0]['company']['inn'],
            'title'        => $persons[0]['company']['name'],
            'kpp'         => $persons[0]['company']['kpp'],
            'ogrn'        => $persons[0]['company']['ogrn'],
            'first_name'  => title_case(mb_strtolower($persons[0]['person']['firstName'])),
            'last_name'   => title_case(mb_strtolower($persons[0]['person']['surName'])),
            'middle_name' => title_case(mb_strtolower($persons[0]['person']['middleName'])),
        ];
    }

    public function newCompanyHandle(NewCompany $request)
    {
        $response = null;
        \DB::transaction(function () use (&$response, $request) {
            $response = $this->register($request);
            /** @var User $user */
            $user = auth()->user();
            $company = new Company($request->only([
                'inn',
                'kpp',
                'ogrn',
            ]));
            $company->name=$request->input('title');
            $company->save();
            $user->fill($request->only(['last_name', 'middle_name']));
            $user->company()->associate($company);
            $user->role()->associate(Role::where('keyword', 'manager')->first()->id);
            $user->save();
        });


        return $response;
    }
}
