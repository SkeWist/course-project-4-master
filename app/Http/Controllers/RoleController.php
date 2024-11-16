<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index() // Получение всех ролей
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    public function show($id) // Получение роли по ID
    {
        $role = Role::findOrFail($id);
        return response()->json($role, 200);
    }

    public function store(Request $request) // Создание новой роли
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function update(Request $request, $id) // Обновление роли
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->all());
        return response()->json($role, 200);
    }

    public function destroy($id) // Удаление роли
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'Роль удалена.'], 200);
    }
}
