<x-auth-layout title="Log in to Your Account" footer="Don’t have an account? <a href='/register' class='text-primary fw-semibold'>Register</a>">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <x-form.form-input type="email" name="email" label="Email or phone number" placeholder="Enter email or phone number"/>
        <x-form.form-input type="password" name="password" label="Password" placeholder="Enter password">
            <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" style="cursor:pointer;">Show</span>
        </x-form.form-input>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">
                Remember me
            </label>
        </div>
        <button type="submit" class="btn btn-custom w-100 py-2 rounded">Login</button>
    </form> 
    <a href="/forgot-password" class="small text-primary text-end border-bottom">Forgot your Password?</a>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.querySelector('.toggle-password');
            const passwordInput = document.querySelector('input[name="password"]');
            
            toggle.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                toggle.textContent = type === 'password' ? 'Show' : 'Hide';
            });
        });
    </script>
</x-auth-layout>
