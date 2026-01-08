<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class UserEdit extends Component
{
    public $userId;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $role = null; // Default role ID, assuming 1 is the user role

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $this->userId],
            'email' => ['required', 'email', 'max:50', 'unique:users,email,' . $this->userId],
            'phone' => ['string', 'max:12'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Allow password to be nullable
            'role' => ['required', 'exists:roles,id'],
        ];
    }

    protected $messages = [
        'name.required' => 'The name is required.',
        'name.max' => 'The name must not exceed 255 characters.',
        'name.string' => 'The name must be a string.',
        'email.required' => 'The email is required.',
        'email.email' => 'The email must be a valid email address.',
        'email.max' => 'The email must not exceed 50 characters.',
        'phone.string' => 'The phone must be a string.',
        'phone.max' => 'The phone must not exceed 12 characters.',
        'password.min' => 'The password must be at least 8 characters.',
        'password.confirmed' => 'The password confirmation does not match.',
        'role.required' => 'The role is required.',
        'role.exists' => 'The role does not exist.',
    ];


    #[On('openEditModal')]
    public function openEditModal($user)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $user = $user['user'];

        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->phone = $user['phone'];
        $this->userId = $user['id'];
        $this->role = $user['roles'][0]['id'] ?? null;
        Flux::modal('edit-user')->show();
    }

    public function updateUser()
    {
        $this->validate();

        if ($this->password) {
            $this->password = bcrypt($this->password);
        }

        if ($user = User::find($this->userId)) {
            $user->update([
                'name'     => $this->name,
                'email'    => $this->email,
                'phone'    => $this->phone,
                'password' => $this->password ?? $user->password,
            ]);

            $user->roles()->sync($this->role); // Sync the role with the user

            $this->dispatch('reloadUsers');
            $this->dispatch('showSuccessAlert', message: 'تم تحديث البيانات بنجاح');
            Flux::modal('edit-user')->close();
        }
    }

    public function render()
    {
         $roles = Role::whereNotIn('id', [1])->get();

        return view('livewire.users.user-edit', compact('roles'));
    }
}
