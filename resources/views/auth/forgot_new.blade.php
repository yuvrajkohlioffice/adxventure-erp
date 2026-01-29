<x-auth-layout title="Forgot your password?" footer="<a href='/login' class='fw-semibold text-decoration-none'>⬅️ Back to Login</a>">
    <small class="text-muted">Please provide your email address, and we’ll send you a password reset link so you can create a new one</small>
  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <p></p>
    <x-form.form-input type="email" name="email" label="Email" placeholder="Enter your registered email"/>
    <button type="submit" class="btn btn-custom w-100 py-2 text-light fw-bold rounded">Send Email</button>
  </form>
</x-auth-layout>