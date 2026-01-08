<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Flux;

class UserCreate extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = null; // Default role ID, assuming 1 is the user role

    protected $rules = [
        'name'      => ['required', 'string', 'max:255', 'unique:users'],
        'email'     => ['required', 'email', 'max:50', 'unique:users'],
        'password'  => ['required', 'min:8', 'confirmed'],
        'role'      => ['required', 'exists:roles,id'], // Assuming role is passed as ID
    ];

    protected $messages = [
        'name.required' => 'The name is required.',
        'name.unique' => 'The name has already been taken.',
        'name.max' => 'The name must not exceed 255 characters.',
        'name.string' => 'The name must be a string.',
        'email.required' => 'The email is required and unique.',
        'email.email' => 'The email must be a valid email address.',
        'email.unique' => 'The email has already been taken.',
        'email.max' => 'The email must not exceed 50 characters.',
        'password.required' => 'The password is required.',
        'password.min' => 'The password must be at least 8 characters.',
        'password.confirmed' => 'The password confirmation does not match.',
        'role.required' => 'The role is required.',
        'role.exists' => 'The role not exist.',
    ];

    public function submit()
    {
        $validatedData = $this->validate();
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);
        $user->addRole($validatedData['role']); // Assuming 4 is the role ID for 'user'

        $this->reset();
        $this->dispatch('reloadUsers');
        $this->dispatch('showSuccessAlert', message: 'تم حفظ البيانات بنجاح');
        Flux::modal('create-user')->close();

    }

    public function render()
    {
        $roles = Role::whereNotIn('id', [1])->get();
        return view('livewire.users.user-create', compact('roles'));
    }
}
