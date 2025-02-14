@extends('layouts.divider')
@section('content')
<style>
    .bloco-didático {
        border-color: #FF4040;
        box-shadow: 10px 10px 10px #FF4040;
    }

    .bloco-administrativo {
        border-color: #8906FB;
        box-shadow: 10px 10px 10px #8906FB;
    }

    .bloco-de-laboratórios {
        border-color: #37F8FF;
        box-shadow: 10px 10px 10px #37F8FF;
    }

    .bloco-de-esportes {
        border-color: #F7FF15;
        box-shadow: 10px 10px 10px #F7FF15;
    }
    
    .bloco-central-4 {
        border-color: #0DA117;
        box-shadow: 10px 10px 10px #0DA117;
    }

    
</style>

    <div class="container mx-auto px-4">
        <a href="{{ route('home') }}" class="text-lime-600 hover:underline">
            ← Voltar para página de eventos
        </a>
        @if ($errors->any())
            <div class="alert alert-danger bg-red-300 justify-center text-center text-red-900 px-4 py-1.5 my-4 mr-2 rounded-md">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Cabeçalho do Evento -->
        <div class="flex justify-between">
            @if($userRole === 1) {{-- Coordenador --}}
                <div class="">
                    <button class="bg-lime-500 hover:bg-lime-600 justify-center text-center text-white px-4 py-1.5 my-4 mr-2 rounded-md" 
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'vincular-event-modal-{{ $evento->id }}' }))">
                        Vincular Organizador
                    </button>
                    <x-modal name="vincular-event-modal-{{ $evento->id }}" maxWidth="md">
                        <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                            <h1 class="text-2xl font-medium text-lime-700">Vincular Organizador</h1>
                            <form action="{{ route('registro.vincular', $evento->id) }}" method="POST">
                                @csrf
                                <div class="my-4">
                                    <x-input-label for="email" class="block mb-1" value="Email do organizador:" />
                                    <x-text-input type="email" id="email" name="email" required 
                                        class="w-full border border-gray-300 rounded-md p-2 placeholder:text-sm placeholder:text-gray-400"
                                        placeholder="Digite o email do organizador"/>
                                </div>
                
                                <div class="flex justify-between mt-4">
                                    <x-secondary-button x-on:click="$dispatch('close-modal', 'delete-event-modal-{{ $evento->id }}')">
                                        Cancelar
                                    </x-secondary-button>
                                    <button type="submit" class="bg-lime-500 text-white px-4 py-2 rounded-md hover:bg-lime-600">
                                        Vincular
                                    </button>
                                </div>
                            </form>
                        </div>
                    </x-modal>             
                    <a href="{{ route('evento.edit', ['id' => $evento->id]) }}" class="justify-center bg-lime-500 text-white text-center px-4 py-2 my-4 mr-2 rounded-md hover:bg-lime-600">
                        Editar Evento
                    </a>
                    <button class="bg-red-500 hover:bg-red-600 justify-center text-center text-white px-4 py-1.5 my-4 mr-2 rounded-md" 
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-event-modal-{{ $evento->id }}' }))">
                        Excluir Evento
                    </button>
                    <x-modal name="delete-event-modal-{{ $evento->id }}" maxWidth="md">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900">
                                Tem certeza de que deseja excluir este evento?
                            </h2>
                
                            <p class="mt-1 text-sm text-gray-600">
                                Esta ação é irreversível e resultará na exclusão permanente do evento.
                            </p>
                
                            <div class="mt-6 flex justify-end space-x-4">
                                <x-secondary-button x-on:click="$dispatch('close-modal', 'delete-event-modal-{{ $evento->id }}')">
                                    Cancelar
                                </x-secondary-button>
                                <form method="POST" action="{{ route('evento.destroy', $evento->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>
                                        Confirmar Exclusão
                                    </x-danger-button>
                                </form>
                            </div>
                        </div>
                    </x-modal>
                </div>
            @endif    
        </div>

        <div class="bg-lime-100 text-lime-700 text-center p-6 rounded-md shadow-md mb-2">
            <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
            <p class="mt-2 text-lg">{{ $evento->descricao }}</p>
            <p class="mt-4">
                <strong>Data:</strong> 
                @if ($evento->data_inicio && $evento->data_fim)
                    {{ $evento->data_inicio->format('d/m/Y') }} - {{ $evento->data_fim->format('d/m/Y') }}
                @elseif ($evento->data_inicio)
                    {{ $evento->data_inicio->format('d/m/Y') }} - Não definido
                @elseif ($evento->data_fim)
                    Não definido - {{ $evento->data_fim->format('d/m/Y') }}
                @else
                    Não definido
                @endif
            </p>
            <p class="text-sm">
                <strong>Coordenador:</strong>
                @if($evento->coordenador && $evento->coordenador->user)
                    {{ $evento->coordenador->user->nome }} {{ $evento->coordenador->user->sobrenome }}
                @else
                    Não definido
                @endif
            </p>
        </div>

        <div>
            @if(($userRole === 1 || $userRole === 2)) {{-- Coordenador ou Organizador --}}
                <div class="my-4">
                    <a href="{{ route('atividade.create', ['id' => $evento->id]) }}" class="justify-center bg-lime-500 text-white text-center px-4 py-2 my-4 mr-2 rounded-md hover:bg-lime-600">
                        Criar Atividade
                    </a>
                </div>
            @endif
        </div>

        <!-- Atividades do Evento -->
        <div>
            <h2 class="text-2xl font-semibold text-lime-700 mb-4">Atividades</h2>
            @if($evento->atividades->count())
                <div class="flex flex-col gap-6">
                    @foreach ($evento->atividades as $atividade)
                        <div class="atividade border border-lime-200 rounded-md p-4 shadow-md transition-all duration-300" data-bloco="{{ $atividade->local ? $atividade->local->bloco : '' }}">
                            <h3 class="text-lg font-bold text-lime-700">{{ $atividade->nome }}</h3>
                            <p class="text-sm text-gray-600">{{ $atividade->descricao }}</p>
                            <p class="mt-2 text-sm text-gray-600">
                                <strong>Data:</strong>
                                @if ($atividade->data)
                                    {{ $atividade->data->format('d/m/Y') }}
                                @else
                                    Não definido
                                @endif
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Horário:</strong>
                                @if ($atividade->hora_inicio && $atividade->hora_fim)
                                    {{ $atividade->hora_inicio->format('H:i') }} - {{ $atividade->hora_fim->format('H:i') }}
                                @elseif ($atividade->hora_inicio)
                                    {{ $atividade->hora_inicio->format('H:i') }} - Não definido
                                @elseif ($atividade->hora_fim)
                                    Não definido - {{ $atividade->hora_fim->format('H:i') }}
                                @else
                                    Não definido
                                @endif
                            </p>
                            <div class="">
                                <p class="text-sm text-gray-600">
                                    <strong>Local:</strong>
                                    @if($atividade->local)
                                        {{ $atividade->local->espaco }} ({{ $atividade->local->bloco }})
                                    @else
                                        Não definido
                                    @endif
                                </p>
                            </div>
                            <p class="text-sm text-gray-600">
                                <strong>Organizadores:</strong>
                                @foreach ($atividade->organizadores as $organizador)
                                    <span>{{ $organizador->user->nome }} {{ $organizador->user->sobrenome }}</span>
                                    @if (!$loop->last)
                                    · 
                                    @endif
                                @endforeach
                            </p>
                            <div class="flex justify-center items-center">
                            @auth
                                @php
                                    $registro = $atividade->registro->firstWhere('user_id', auth()->id());
                                    $userRole = $registro->role->id ?? null;                                    
                                @endphp
                                

                                @if ($registro && $userRole === 3)
                                    <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 mt-4 rounded-md">
                                        Inscrito!
                                    </button>
                                @elseif(!$registro)
                                    <form action="{{ route('registro.inscrever', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}" method="POST" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                        <input type="hidden" name="atividade_id" value="{{ $atividade->id }}">
                                        <input type="hidden" name="role_id" value="3"> <!-- Inscrito -->
                                        <button type="submit" class="bg-lime-500 text-white px-4 py-2 rounded-md hover:bg-lime-600">
                                            Inscrever-se
                                        </button>
                                    </form>
                                @elseif($registro && ($userRole === 1 || $userRole === 2))
                                    <div class="flex ">
                                        @if(($userRole === 1 || $userRole === 2)) {{-- Coordenador ou Organizador --}}
                                            <div class="my-3.5">
                                                <a href="{{ route('atividade.inscritos', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}" class="justify-center bg-lime-500 hover:bg-lime-600 text-white text-center px-4 py-2 my-2 mr-2 rounded-md ">
                                                    Ver Inscritos
                                                </a>
                                            </div>
                                            <div class="my-3.5">
                                                <a href="{{ route('atividade.edit', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}" class="justify-center bg-orange-500 hover:bg-orange-600 text-white text-center px-4 py-2 my-2 mr-2 rounded-md ">
                                                    Editar
                                                </a>
                                            </div>
                                            <div class="mt-2">
                                                <button type="button" class="justify-center bg-red-500 hover:bg-red-600 text-white text-center px-3.5 py-1.5 rounded-md " onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-event-modal-{{ $atividade->id }}' }))">
                                                    Excluir
                                                </button>
                                            </div>
                                            <x-modal name="delete-event-modal-{{ $atividade->id }}" maxWidth="md">
                                                <div class="p-6">
                                                    <h2 class="text-lg font-medium text-gray-900">
                                                        Tem certeza de que deseja excluir esta atividade?
                                                    </h2>
                                        
                                                    <p class="mt-1 text-sm text-gray-600">
                                                        Esta ação é irreversível e resultará na exclusão permanente da atividade.
                                                    </p>
                                        
                                                    <div class="mt-6 flex justify-end space-x-4">
                                                        <x-secondary-button x-on:click="$dispatch('close-modal', 'delete-event-modal-{{ $atividade->id }}')">
                                                            Cancelar
                                                        </x-secondary-button>
                                                        <form method="POST" action="{{ route('atividade.destroy', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-danger-button>
                                                                Confirmar Exclusão
                                                            </x-danger-button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </x-modal>
                                        @endif
                                    </div>
                                
                                @endif
                                @endauth
                                    @guest
                                        <a href="{{ route('login') }}" class=" justify-center bg-lime-500 text-white text-center px-4 py-2 mt-4 rounded-md hover:bg-lime-600">
                                            Inscrever-se!
                                        </a>
                                    @endguest
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- modal de confirmação de inscrição -->
                {{-- <div class="fixed top-0 w-full z-50">
                    @if (session('message'))
                    <div class="bg-green-100 text-green-700 p-4 rounded-md shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                    @endif
                </div> --}}
            @else
                <p class="text-gray-500">Nenhuma atividade cadastrada para este evento.</p>
            @endif
            
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const atividades = document.querySelectorAll('.atividade');
            const elementos = document.querySelectorAll('[data-tooltip]');
            let blocoSelecionado = null;

            // Criar tooltip para a imagem
            const tooltip = document.createElement('div');
            tooltip.id = 'tooltip';
            tooltip.style.position = 'absolute';
            tooltip.style.display = 'none';
            tooltip.style.border = '1px solid black';
            tooltip.style.background = 'black';
            tooltip.style.padding = '5px';
            tooltip.style.maxWidth = '200px';
            tooltip.style.fontSize = '12px';

            const tooltipImg = document.createElement('img');
            tooltipImg.style.width = '150px';
            tooltipImg.style.height = '100px';
            tooltip.appendChild(tooltipImg);

            // Criar a lista de salas
            const listaSalas = document.createElement('ul');
            listaSalas.style.listStyle = 'none';
            listaSalas.style.padding = '0';
            listaSalas.style.margin = '0';
            listaSalas.style.fontSize = '11px';
            tooltip.appendChild(listaSalas);

            document.body.appendChild(tooltip);

            // Mapeamento de blocos para imagens
            const blocos = {
                "bloco-didático": "{{ asset('images/didatico.jpg') }}",
                "bloco-de-esportes": "{{ asset('images/esportes.jpg') }}",
                "bloco-de-laboratórios": "{{ asset('images/laboratorio.jpg') }}",
                "central1": "{{ asset('images/central1.jpg') }}",
                "central2": "{{ asset('images/central2.jpg') }}",
                "central3": "{{ asset('images/central3.jpg') }}",
                "bloco-central-4": "{{ asset('images/central4.jpg') }}",
                "administrativo": "{{ asset('images/administrativo.jpg') }}"
            };

            // Mapeamento de blocos para suas salas
            const salas = {
                "bloco-didático": ["Sala 10", "Sala 11", "Sala 12", "Lab. Informática 0", "Lab. Informática 1", "Lab. Informática 2", "Lab. Informática 3", "Lab. Informática 4", "Lab. Informática 5"],
                "bloco-esportes": ["Quadra 1", "Quadra 2", "Vestiário Masculino", "Vestiário Feminino"],
                "bloco-laboratórios": ["Lab Química", "Lab Física", "Lab Biologia"],
                "bloco-central-1": ["Auditório", "Sala de Reunião", "Diretoria"],
                "bloco-central-2": ["Biblioteca", "Sala de Estudos", "Computadores"],
                "bloco-central-3": ["Sala dos Professores", "Secretaria"],
                "bloco-central-4": ["-- 1º PISO --", "Mosaico", "Cozinha", "-- 2º PISO --", "Sala Profs. de Matemática", "Sala Profs. de Física", "Sala Profs. de Linguagens", "Sala Profs. de C. Biológicas", "Sala Profs. de Mecânica"],
                "bloco-administrativo": ["RH", "Financeiro", "Coordenação"]
            };

            elementos.forEach(elemento => {
                const bloco = elemento.id.toLowerCase().replace(/\s+/g, '-');

                // Evento de hover para destaque e exibição da imagem
                elemento.addEventListener('mouseenter', function (event) {
                    if (!blocoSelecionado) {
                        atividades.forEach(atividade => {
                            if (atividade.dataset.bloco.toLowerCase().replace(/\s+/g, '-') === bloco) {
                                atividade.classList.add(bloco);
                            }
                        });
                    }

                    // Exibir a imagem do bloco correspondente
                    if (blocos[bloco]) {
                        tooltipImg.src = blocos[bloco];
                        listaSalas.innerHTML = ''; // Limpar lista antes de adicionar as salas
                        salas[bloco].forEach(sala => {
                            let item = document.createElement('li');
                            item.textContent = sala;
                            item.style.borderBottom = '1px solid #ddd';
                            item.style.padding = '2px 0';
                            listaSalas.appendChild(item);
                        });
                        tooltip.style.display = 'block';
                        tooltip.style.left = event.pageX + 'px';
                        tooltip.style.top = event.pageY + 'px';
                    }
                });

                // Evento de movimento do mouse para seguir o cursor
                elemento.addEventListener('mousemove', function (event) {
                    tooltip.style.left = event.pageX + 10 + 'px';
                    tooltip.style.top = event.pageY + 10 + 'px';
                });

                elemento.addEventListener('mouseleave', function () {
                    if (!blocoSelecionado) {
                        atividades.forEach(atividade => {
                            atividade.classList.remove(bloco);
                        });
                    }

                    // Esconder a tooltip quando o mouse sair
                    tooltip.style.display = 'none';
                });

                // Evento de clique para filtrar atividades
                elemento.addEventListener('click', function () {
                    if (blocoSelecionado === bloco) {
                        blocoSelecionado = null;
                        atividades.forEach(atividade => {
                            atividade.style.display = 'block';
                            atividade.classList.remove(bloco);
                        });
                    } else {
                        blocoSelecionado = bloco;
                        atividades.forEach(atividade => {
                            if (atividade.dataset.bloco.toLowerCase().replace(/\s+/g, '-') === bloco) {
                                atividade.style.display = 'block';
                                atividade.classList.add(bloco);
                            } else {
                                atividade.style.display = 'none';
                                atividade.classList.remove(atividade.dataset.bloco.toLowerCase().replace(/\s+/g, '-'));
                            }
                        });
                    }
                });
            });
        });

    </script>
@endpush