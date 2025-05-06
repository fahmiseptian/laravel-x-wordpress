<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function register(array $data)
    {
        return self::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'status'   => 'active',
        ]);
    }

    public static function checkStatus($identifier)
    {
        return self::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->select('id', 'email', 'username', 'status')
            ->first();
    }

    public static function editUser($id)
    {
        return self::find($id);
    }

    public static function updateUser($id, array $data)
    {
        $user = self::findOrFail($id);

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return $user;
    }

    public static function updateStatus($id, $status)
    {
        $user = self::findOrFail($id);
        $user->status = $status;
        $user->save();
        return $user;
    }

    public static function getDetail($id)
    {
        return self::find($id);
    }

    public static function changePassword($id, $oldPassword, $newPassword)
    {
        $user = self::find($id);

        if (!$user || !Hash::check($oldPassword, $user->password)) {
            return false;
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return true;
    }
}
