<div class="reset-password-container">
    <h2 class="text-2xl font-bold text-center mb-4">Сброс пароля</h2>

    <form action="{{ url('password/reset') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="block text-gray-700">Новый пароль</label>
            <input type="password" name="password" class="input-field" required placeholder="Введите новый пароль">
        </div>

        <div class="mb-3">
            <label class="block text-gray-700">Подтверждение пароля</label>
            <input type="password" name="password_confirmation" class="input-field" required
                placeholder="Подтвердите пароль">
        </div>

        <button type="submit" class="btn-primary">Сбросить пароль</button>

    </form>
</div>
<style scoped>
    .reset-password-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .input-field {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .btn-primary {
        width: 100%;
        padding: 10px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: #0056b3;
    }
</style>