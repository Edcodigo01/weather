<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use App\Http\Requests\FiltersUserRequest;
use App\Jobs\CheckWeatherUsersJob;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Validator;

class UserController extends Controller
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index(FiltersUserRequest $request)
    {
        // Realizar la consulta en el repositorio correspondiente
        $items = $this->userRepository->getAll($request);

        return Inertia::render('Landing', [
            'items' => $items,
            'total' => $items->total(),
            "filters" => (object) [ // si no existen en FiltersUserRequest se les añade un valor predefinido
                'per_page' => $request->per_page,
                'sort_by' => $request->sort_by,
                'sort_order' => $request->sort_order
            ]
        ]);
    }

    public function get($id)
    {
        $user = $this->userRepository->get($id);

        if (!$user)
            return response()->json(["message" => "No existe un usuario con el id: " . $id], 404);

        return response()->json($user);

    }

    // ---BORRAR----------------
    public function store() // comentar
    {

        $users = User::all("id");

        foreach ($users as $user) {
            CheckWeatherUsersJob::dispatch($user);
        }

        return "Probando jobs";
    }
}
