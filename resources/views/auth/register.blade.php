<x-guest-layout>
    
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" >
        @csrf

         <div>
            <x-input-label for="profile_image" :value="__('Your Image')" />
            <x-text-input id="profile_image" class="block mt-1 w-full" type="file" name="profile_image" :value="old('profile_image')"  autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
        </div>
        <br>
        
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"  autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"  autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        
        <div class="mt-4">
            <x-input-label for="email" :value="__('Phone No.')" />
            <x-text-input id="phone_no" class="block mt-1 w-full" type="number" name="phone_no" minLnegth="1" maxLnegth="10"  :value="old('phone_no')"   />
            <x-input-error :messages="$errors->get('phone_no')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="dob" :value="__('Date of Birth.')" />
            <x-text-input id="dob" class="block mt-1 w-full" type="date" name="date_of_birth" minLnegth="1" maxLnegth="10"  :value="old('date_of_birth')"   />
            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
        </div>
        
          <div class="mt-4">
            <x-input-label for="skills" :value="__('Skills')" /> (Please add your all skills with comma.)
            <x-text-input id="skills" class="block mt-1 w-full" type="text" name="skills"  :value="old('phone_no')"  autocomplete="" placeholder="eg. SEO,SMO etc" />
            <x-input-error :messages="$errors->get('skills')" class="mt-2" />
        </div>                              

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                             autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"  autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

