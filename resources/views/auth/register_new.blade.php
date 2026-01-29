<x-auth-layout title="Create your new account" footer="Already have an account? <a href='/login' class='text-primary fw-semibold'>Login</a>">
  <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
    @csrf

   <div class="mb-3 text-start">
  <label class="form-label d-block fw-semibold">Profile Image</label>

  <div class="text-center">
    <div class="position-relative d-inline-block">
      <img id="previewImage" 
           src="{{ asset('logo.png') }}" 
           alt="Preview" 
           class="rounded-circle border shadow-sm" 
           style="width: 110px; height: 110px; object-fit: cover;">

      <label for="imageInput" 
             class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2" 
             style="cursor:pointer; border:2px solid white;">
        <i class="bi bi-camera-fill"></i>
      </label>
    </div>

    <input type="file" id="imageInput" name="image" accept="image/*" class="d-none">
  </div>
</div>
    <x-form.form-input type="text" name="name" label="Full Name" placeholder="Enter full name"/>
    <x-form.form-input type="email" name="email" label="Email" placeholder="Enter email id"/>
    <x-form.form-input type="number" name="phone" label="Phone number" placeholder="Enter phone number"/>
    <x-form.form-input type="date" name="dob" label="Date of Birth" placeholder="Select date of birth"/>
    <x-form.form-input type="password" name="password" label="Password" placeholder="Enter password">
        <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" style="cursor:pointer;">Show</span>
    </x-form.form-input>
        <x-form.form-input type="password" name="password_confirmation" label="Confirm Password" placeholder="Enter Confirm Password">
        <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" style="cursor:pointer;">Show</span>
    </x-form.form-input>

    <button type="submit" class="btn btn-custom w-100 py-2">Register</button>
  </form>
    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewImage').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
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
