<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\Sector;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Mpdf\Tag\S;

class UserEdit extends Component
{
    public $userId;
    public $name;
    public $nastional_id;
    public $email;
    public $phone;
    public $sector_id;
    public $password;
    public $password_confirmation;
    public $role = null; // Default role ID, assuming 1 is the user role

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $this->userId],
            'nastional_id' => ['required', 'string', 'digits:10', 'unique:users,nastional_id,' . $this->userId],
            'email' => ['required', 'email', 'max:50', 'unique:users,email,' . $this->userId],
            'phone' => ['nullable','string', 'max:12'],
            'sector_id' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Allow password to be nullable
            'role' => ['required', 'exists:roles,id'],
        ];
    }

    protected $messages = [
        'name.required' => 'The name is required.',
        'name.max' => 'The name must not exceed 255 characters.',
        'name.string' => 'The name must be a string.',
        'name.unique' => 'The name has already been taken.',
        'nastional_id.required' => 'The national ID is required.',
        'nastional_id.string' => 'The national ID must be a string.',
        'nastional_id.unique' => 'The national ID has already been taken.',
        'nastional_id.digits' => 'The national ID must be exactly 10 digits.',
        'email.required' => 'The email is required.',
        'email.email' => 'The email must be a valid email address.',
        'email.max' => 'The email must not exceed 50 characters.',
        'email.unique' => 'The email has already been taken.',
        'phone.string' => 'The phone must be a string.',
        'phone.max' => 'The phone must not exceed 12 characters.',
        'sector_id.string' => 'The sector ID must be a string.',
        'sector_id.max' => 'The sector ID must not exceed 255 characters.',
        'password.string' => 'The password must be a string.',
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
        $this->nastional_id = $user['nastional_id'];
        $this->email = $user['email'];
        $this->phone = $user['phone'] ?? null;
        $this->sector_id = $user['sector_id'] ?? null;
        $this->password = null;
        $this->password_confirmation = null;
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

        $user = User::find($this->userId);
        if ($user) {
            $user->update([
                'name'     => $this->name,
                'nastional_id' => $this->nastional_id,
                'email'    => $this->email,
                'phone'    => $this->phone,
                'sector_id' => $this->sector_id,
                'password' => $this->password ?? $user->password,
            ]);

            $user->roles()->sync($this->role); // Sync the role with the user

            $this->dispatch('reloadUsers');
            $this->dispatch('showSuccessAlert', message: 'تم تحديث البيانات بنجاح');
            Flux::modal('edit-user')->close();
        } else {
            // Handle the case where the user is not found
            $this->dispatch('showErrorAlert', message: 'User not found.');
        }
    }

    public function render()
    {

        if (Auth::user()->hasRole('superadmin', 'admin')){
            $accept_roles = [];
        } else {
            $accept_roles = ['3', '4', '5'];
        }

        $sectors = Sector::all();

        $roles = Role::whereNotIn('id', $accept_roles)->get();

        return view('livewire.users.user-edit', compact('roles', 'sectors'));
    }
}
