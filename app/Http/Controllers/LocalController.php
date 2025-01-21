<?php

namespace App\Http\Controllers;

use App\Models\Locais;
use App\Models\User;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    public function getEspacos($bloco)
    {
        $espacos = Locais::where('bloco', $bloco)->get();

        return response()->json($espacos); // Retorna os espaços em formato JSON
    }
    

}
