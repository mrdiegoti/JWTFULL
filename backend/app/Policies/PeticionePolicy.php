<?php

namespace App\Policies;

use App\Models\Peticione;
use App\Models\User;

class PeticionePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before(User $user, string $ability)
    {
        if( $user->role_id==1){
            return true;
        }
    }
    public function cambiarEstado(User $user, Peticione $peticione): bool
    {
        if($user->role_id==2 & $user->id==$peticione->user_id) {
            return true;
        }
        return false;
    }

    public function update(User $user, Peticione $peticione): bool
    {
        if($user->role_id==2 & $user->id==$peticione->user_id) {
            return true;
        }
        return false;
    }

    public function delete(User $user, Peticione $peticione): bool
    {
        if($user->role_id==2 & $user->id==$peticione->user_id) {
            return true;
        }
        return false;
    }

    public function firmar(User $user, Peticione $peticione): bool
    {
        if(!$peticione->firmas()->where('user_id', $user->id)->exists()){
            return true;
        }return false;
    }



}
