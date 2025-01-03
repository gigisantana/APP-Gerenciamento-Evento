@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ">
    <h2 class="text-2xl text-lime-700 font-semibold">Editar Perfil</h2>
    <div class="flex m-2 justify-around ">
        <div class="px-4 basis-2/4">
            @include('profile.partials.update-profile-information-form', ['user' => $user])
        </div>
        <div class="px-4 basis-2/4">
            @include('profile.partials.update-password-form', ['user' => $user])
        </div>
    </div>

</div>
@endsection