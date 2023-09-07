<?php

namespace App\Http\Controllers;

use App\Enums\RoleTypeEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::paginate(25);

        if (!is_null($query = $request->query('search', null))) {
            $users = User::where('email', 'like', '%' . $query . '%')
                ->orWhere('name', 'like', '%' . $query . '%')
                ->paginate(25);
        }

        return view('users.index', [
            'data' => [
                'users' => $users
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'search_query' => $request->query('search', null),
                'total_users' => User::count(),
                'total_admin_users' => User::where('role', RoleTypeEnum::Admin)->count(),
                'chart' => [
                    'users' => User::select(
                        DB::raw('COUNT(id) AS total_user'),
                        DB::raw('DATE(created_at) AS date_of_created_at')
                    )->groupBy('date_of_created_at')->get()
                ]
            ]),
            ...$this->withUser($request)
        ]);
    }
}
