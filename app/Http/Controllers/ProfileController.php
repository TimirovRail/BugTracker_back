<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function adminProfile()
    {
        return response()->json([
            'role' => 'admin',
            'permissions' => ['manage_users', 'manage_roles', 'view_reports'],
            'message' => 'Добро пожаловать, Администратор!',
        ]);
    }

    public function developerProfile()
    {
        return response()->json([
            'role' => 'developer',
            'permissions' => ['create_bugs', 'update_bugs', 'comment_bugs'],
            'message' => 'Добро пожаловать, Разработчик!',
        ]);
    }

    public function testerProfile()
    {
        return response()->json([
            'role' => 'tester',
            'permissions' => ['report_bugs', 'comment_bugs', 'verify_fixes'],
            'message' => 'Добро пожаловать, Тестировщик!',
        ]);
    }

    public function managerProfile()
    {
        return response()->json([
            'role' => 'manager',
            'permissions' => ['view_reports', 'assign_bugs', 'manage_projects'],
            'message' => 'Добро пожаловать, Менеджер проекта!',
        ]);
    }
}