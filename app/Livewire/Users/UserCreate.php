<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Flux\Flux;
use Livewire\Component;

class UserCreate extends Component
{
    public $name;
    public $email;
    public $phone;
    public $educational_sector;
    public $password;
    public $password_confirmation;
    public $role = null; // Default role ID, assuming 1 is the user role

    protected $rules = [
        'name'                  => ['required', 'string', 'max:255', 'unique:users'],
        'email'                 => ['required', 'email', 'max:50', 'unique:users'],
        'phone'                 => ['nullable','string', 'max:12'],
        'educational_sector'    => ['nullable', 'string', 'max:255'],
        'password'              => ['required', 'min:8', 'confirmed'],
        'role'                  => ['required', 'exists:roles,id'], // Assuming role is passed as ID
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
        'phone.string' => 'The phone must be a string.',
        'phone.max' => 'The phone must not exceed 12 characters.',
        'educational_sector.string' => 'The educational sector must be a string.',
        'educational_sector.max' => 'The educational sector must not exceed 255 characters.',
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
        if (auth()->user()->hasRole('superadmin', 'admin')){
            $accept_roles = [];
        } else {
            $accept_roles = ['3', '4', '5'];
        }
        $educationalSectors = config('schools.educational_sectors');

        $roles = Role::whereNotIn('id', $accept_roles)->get();
        return view('livewire.users.user-create', compact('roles', 'educationalSectors'));
    }
}
