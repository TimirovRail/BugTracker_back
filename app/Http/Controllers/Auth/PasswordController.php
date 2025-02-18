<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    // Отправка ссылки на восстановление пароля
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'));

        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Ссылка для сброса пароля отправлена на ваш email']);
        }

        return response()->json(['message' => 'Ошибка при отправке ссылки'], 500);
    }

    // Сброс пароля
    public function resetPassword(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ]);

        // Если валидация не прошла
        if ($validator->fails()) {
            \Log::error('Validation failed', $validator->errors()->toArray());  // Логируем ошибку валидации
            return response()->json(['message' => 'Неверные данные.'], 422);
        }

        \Log::info('Password reset request', $request->only('email', 'password'));  // Логируем запрос

        // Сброс пароля
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => bcrypt($request->password),
                ])->save();
            }
        );

        \Log::info('Password reset response', ['response' => $response]);  // Логируем ответ от сброса пароля

        // Проверка успешности сброса
        if ($response == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Пароль успешно сброшен.'], 200);
        }

        \Log::error('Password reset failed', ['response' => $response]);  // Логируем неудачу
        return response()->json(['message' => 'Ошибка при сбросе пароля.'], 500);
    }

}
