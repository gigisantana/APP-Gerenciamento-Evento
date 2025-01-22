@extends('layouts.app')
@section('content')
    <div class="mx-auto px-2 sm:px-6 lg:px-8 py-4 flex m-2 justify-around">
        <div class="px-4 basis-2/4">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ocorreu um erro:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('atividade.store', ['id' => $evento->id]) }}" method="POST">
                @csrf
            
                <h1 class="text-2xl font-bold text-lime-700 mb-4">Criar Nova Atividade</h1>

                <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                <div class="m-4">
                    <x-input-label for="nome" value="Nome da atividade:" />
                    <x-text-input id="nome" type="text" name="nome" />
                    <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                </div>
                <div class="m-4">
                    <x-input-label for="data" value="Data:" />
                    <x-date-input id="data" type="date" name="data" />
                    <x-input-error :messages="$errors->get('data')" class="mt-2" />
                </div>
                <div class="flex space-x-8"> 
                    <div class="m-4">
                        <x-input-label for="hora_inicio" value="Horário de início:" />
                        <input id="hora_inicio" type="time" name="hora_inicio" class="border-lime-300 focus:border-lime-500 focus:ring-lime-500 rounded-md shadow-sm" />
                        <x-input-error :messages="$errors->get('hora_inicio')" class="mt-2" />
                    </div>
                    <div class="m-4">
                        <x-input-label for="hora_fim" value="Horário de término:" />
                        <input id="hora_fim" type="time" name="hora_fim" class="border-lime-300 focus:border-lime-500 focus:ring-lime-500 rounded-md shadow-sm"/>
                        <x-input-error :messages="$errors->get('hora_fim')" class="mt-2" />
                    </div>
                </div>
                <div class="mx-4 w-1/2">
                    <x-input-label for="descricao" value="Descrição da atividade:" />
                    <x-textarea-input id="descricao" rows="5" class="mt-2 placeholder:text-sm placeholder:text-gray-400" type="text" name="descricao" placeholder="Como será a atividade?" :value="old('descricao')" required />
                    <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                </div>

                <div class="flex">
                    <div class="m-4">
                        <x-input-label for="bloco" value="Bloco:" />
                        <select name="bloco" id="bloco" class="border-lime-300 focus:border-lime-500 focus:ring-lime-500 rounded-md shadow-sm" required>
                            <option value="">Selecione o bloco</option>
                            @foreach ($locais->unique('bloco') as $local)
                                <option value="{{ $local->bloco }}">{{ $local->bloco }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="m-4">
                        <x-input-label for="local_id" value="Espaço:" />
                        <select name="local_id" id="espaco" class="border-lime-300 focus:border-lime-500 focus:ring-lime-500 rounded-md shadow-sm" required>
                            <option value="">Selecione o espaço</option>
                        </select>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const blocoSelect = document.getElementById('bloco');
                            const espacoSelect = document.getElementById('espaco');
                            const locais = @json($locais);
                            
                            function carregarEspacos(blocoSelecionado) {
                                espacoSelect.innerHTML = '<option value="">Selecione o espaço</option>';
                                
                                const espacosFiltrados = locais.filter(local => local.bloco === blocoSelecionado);
                                
                                espacosFiltrados.forEach(local => {
                                    const option = document.createElement('option');
                                    option.value = local.bloco + '-' + local.espaco;
                                    option.textContent = local.espaco;
                                    espacoSelect.appendChild(option);
                                });
                            }
                            // Carrega os espaços ao selecionar um bloco
                            blocoSelect.addEventListener('change', function () {
                                carregarEspacos(this.value);
                            });
                        });
                    </script>
                </div>

                <div class=" flex justify-center items-center">
                    <button type="submit" class="bg-lime-500 text-white text-lg px-4 py-2 rounded-md hover:bg-lime-600">Criar Atividade</button>
                </div>                
            </form>
        </div>
        <div class="flex flex-col px-4 basis-2/4 justify-top">
            <div class="bg-lime-100 text-lime-700 text-center p-4 rounded-md shadow-md w-4/5">
                <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
                <p class="mt-2 text-lg">{{ $evento->descricao }}</p>
                <p class="mt-4">
                    <strong>Início:</strong> {{ $evento->data_inicio->format('d/m/Y') }}                      
                    <strong>Fim:</strong> {{ $evento->data_fim->format('d/m/Y') }}                  
                </p>               
            </div>

            <div class="flex flex-col justify-start m-6 self-start ml-8 w-full">
                <h1 class="text-2xl font-medium text-lime-700">Vincular Organizador</h2>
                <form action="{{ route('registro.vincular', $evento->id) }}" method="POST">
                    @csrf
                    <div class="mx-3 my-4 justify-start items-center space-x-3">
                        <x-input-label for="email" class="mx-3" value="Email do organizador: "/>
                        <x-text-input type="email" id="email" class="w-1/2 placeholder:text-sm placeholder:text-gray-400" name="email" required placeholder="Digite o email do organizador"/>
                    </div>
                    <div class="mx-10 my-6">
                        <button type="submit" class="bg-lime-500 text-white text-md px-2 py-2 rounded-md hover:bg-lime-600">Vincular Organizador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection