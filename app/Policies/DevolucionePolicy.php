<?php

namespace App\Policies;

use App\Devolucione;
use App\User;
use App\Venta;
use App\ProductoReferencia;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevolucionePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any devoluciones.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the devolucione.
     *
     * @param  \App\User  $user
     * @param  \App\Devolucione  $devolucione
     * @return mixed
     */
    public function view(User $user, Devolucione $devolucione)
    {
        //
    }

    /**
     * Determine whether the user can create devoluciones.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user,  Devolucione $devolucione, Venta $venta, ProductoReferencia $producto)
    {
        // return $venta->devolucione->productoReferencia->contains('id',  $producto);
    }

    /**
     * Determine whether the user can update the devolucione.
     *
     * @param  \App\User  $user
     * @param  \App\Devolucione  $devolucione
     * @return mixed
     */
    public function update(User $user, Devolucione $devolucione)
    {
        //
    }

    /**
     * Determine whether the user can delete the devolucione.
     *
     * @param  \App\User  $user
     * @param  \App\Devolucione  $devolucione
     * @return mixed
     */
    public function delete(User $user, Devolucione $devolucione)
    {
        //
    }

    /**
     * Determine whether the user can restore the devolucione.
     *
     * @param  \App\User  $user
     * @param  \App\Devolucione  $devolucione
     * @return mixed
     */
    public function restore(User $user, Devolucione $devolucione)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the devolucione.
     *
     * @param  \App\User  $user
     * @param  \App\Devolucione  $devolucione
     * @return mixed
     */
    public function forceDelete(User $user, Devolucione $devolucione)
    {
        //
    }
}
