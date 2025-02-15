<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Gestor de Eventos - IFPR</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://ifpr.edu.br/wp-content/themes/tema-multisite/assets/images/favicon.gif" rel="shortcut icon" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="https://unpkg.co/gsap@3/dist/gsap.min.js" defer></script>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">

        <style>
            #tooltip {
                position: absolute;
                display: none;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 5px;
                border-radius: 10px;
                pointer-events: none;
            }

            .scrollable-content::-webkit-scrollbar {
                width: 8px;
            }

            .scrollable-content::-webkit-scrollbar-track {
                background: #ecfccb;
                border-radius: 10px;
            }

            .scrollable-content::-webkit-scrollbar-thumb {
                background-color: #bef264;
                border-radius: 10px;
                border: 2px solid #f1f1f1;
            }

            .scrollable-content::-webkit-scrollbar-thumb:hover {
                background-color: #84cc16;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-lime-400">
        <div class="">
            @include('layouts.navigation')
                @if (session('message'))
                    <div class="bg-green-100 text-green-700 p-4 rounded-md shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                @endif
            <!-- Page Heading -->
            @if (isset($header))
                <header class="">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="mx-auto my-auto sm:px-6 lg:px-8 px-10 py-4 overflow-hidden">
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="align-top flex flex-wrap">
                        <!-- Conteúdo com rolagem interna, sem ultrapassar o limite da tela -->
                        <div class="py-3 pl-4 basis-2/5 overflow-y-auto max-h-[calc(100vh-85px)] scrollable-content">                        
                            @yield('content')
                        </div>
            
                        <!-- Mapa estático -->
                        <div class="py-3 basis-3/5 px-8"> 
                            <h1 class="text-2xl font-bold text-lime-700">Mapa do Campus Paranaguá</h1>
                            <p class="text-gray-500">Passe o mouse no mapa para mais detalhes!</p>
                            <div class="flex justify-center items-center">
                                {!! file_get_contents(public_path('images/mapa-IFPR-2.svg')) !!}
                            </div>
                            

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const elementos = document.querySelectorAll('[data-tooltip]');
                                
                                    // Cria a tooltip
                                    const tooltip = document.createElement('div');
                                    tooltip.id = 'tooltip';
                                    tooltip.style.position = 'absolute';
                                    tooltip.style.display = 'none';
                                    tooltip.style.border = '1px solid black';
                                    tooltip.style.background = 'black';
                                    tooltip.style.padding = '5px';
                                    tooltip.style.maxWidth = '200px';
                                    tooltip.style.fontSize = '12px';
                                    tooltip.style.color = 'white';

                                    const tooltipNomeBloco = document.createElement('div');
                                    tooltipNomeBloco.style.fontWeight = 'bold';
                                    tooltipNomeBloco.style.marginBottom = '5px';
                                    tooltipNomeBloco.style.textAlign = 'center';
                                    tooltip.appendChild(tooltipNomeBloco);
                                    
                                    const tooltipImg = document.createElement('img');
                                    tooltipImg.style.width = '150px';
                                    tooltipImg.style.height = '100px';
                                    tooltip.appendChild(tooltipImg);
                                
                                    // Cria um container para informações adicionais (título, divisores, lista de salas)
                                    const infoContainer = document.createElement('div');
                                    tooltip.appendChild(infoContainer);
                                
                                    document.body.appendChild(tooltip);
                                
                                    const blocos = {
                                        "bloco-didático": "{{ asset('images/didatico.jpg') }}",
                                        "bloco-de-esportes": "{{ asset('images/esportes.jpg') }}",
                                        "bloco-de-laboratórios": "{{ asset('images/laboratorio.jpg') }}",
                                        "bloco-central-1": "{{ asset('images/central1.jpg') }}",
                                        "bloco-central-2": "{{ asset('images/central2.jpg') }}",
                                        "bloco-central-3": "{{ asset('images/central3.jpg') }}",
                                        "bloco-central-4": "{{ asset('images/central4.jpg') }}",
                                        "bloco-administrativo": "{{ asset('images/administrativo.jpg') }}"
                                    };
                                
                                    const salas = {
                                        "bloco-didático": [
                                            { piso: "1º piso", salas: ["Assistência Estudantil", "Lab. Física", "Lab. Química", "Lab. Biologia"]},
                                            { piso: "2º piso", salas: ["Sala 10", "Sala 11", "Sala 12", "Lab. Informática 0", "Lab. Informática 1", "Lab. Informática 2", "Lab. Informática 3", "Lab. Informática 4", "Lab. Informática 5"]}
                                        ],
                                        "bloco-de-esportes": [
                                            { piso: "1º piso", salas: ["Quadra 1", "Quadra 2", "Vestiário Masculino", "Vestiário Feminino"]}
                                        ],
                                        "bloco-de-laboratórios": [
                                            { piso: "1º piso", salas: ["Lab. de Mecânica (FABLAB)", "Lab. Meio Ambiente", "Lab. Usinagem", "Lab.Materiais", "Lab. CAM/CNC", "Sala de Biologia"]},
                                            { piso: "2º piso", salas: ["Lab. Metrologia", "Lab. Soldagem", "Lab. Manutenção", "Coord. Manutenção Industrial", "Lab. Automação", "Lab. Projetos", "Lab.Fenômenos de Transporte"]}
                                        ],
                                        "bloco-central-1": [
                                            { piso: "1º piso", salas: ["Auditório", "Sala de Reunião", "Diretoria"] }
                                        ],
                                        "bloco-central-2": [
                                            { piso: "1º piso", salas: ["Biblioteca", "Sala de Estudos", "Computadores"] }
                                        ],
                                        "bloco-central-3": [
                                            { piso: "1º piso", salas: ["Sala dos Professores", "Secretaria"] }
                                        ],
                                        "bloco-central-4": [
                                            { piso: "1º Piso", salas: ["Recepção", "Sala A", "Sala B"] },
                                            { piso: "2º Piso", salas: ["Salão Principal", "Cantina"] }
                                        ],
                                        "bloco-administrativo": [
                                            { piso: "1º piso", salas: ["RH", "Financeiro", "Coordenação"] }
                                        ]
                                    };
                                
                                    function montarInfo(blocoKey) {
                                        infoContainer.innerHTML = ''; // Limpa o container
                                        
                                        const infoPisos = salas[blocoKey];
                                    
                                        if (Array.isArray(infoPisos)) {
                                            infoPisos.forEach(p => {
                                                const titulo = document.createElement('h4');
                                                titulo.textContent = p.piso;
                                                titulo.style.margin = '5px 0 2px 0';
                                                infoContainer.appendChild(titulo);
                                    
                                                const divisor = document.createElement('hr');
                                                divisor.style.border = '0';
                                                divisor.style.height = '1px';
                                                divisor.style.backgroundColor = '#ddd';
                                                infoContainer.appendChild(divisor);
                                    
                                                const ul = document.createElement('ul');
                                                ul.style.listStyle = 'none';
                                                ul.style.padding = '0';
                                                ul.style.margin = '0 0 5px 0';
                                                p.salas.forEach(sala => {
                                                    const li = document.createElement('li');
                                                    li.textContent = sala;
                                                    li.style.padding = '2px 0';
                                                    ul.appendChild(li);
                                                });
                                                infoContainer.appendChild(ul);
                                            });
                                        }
                                    }
                                
                                    elementos.forEach(elemento => {
                                        const bloco = elemento.id.toLowerCase().replace(/\s+/g, '-');
                                        
                                        elemento.addEventListener('mouseenter', function (event) {
                                            if (blocos[bloco]) {
                                                tooltipNomeBloco.textContent = elemento.id;
                                                tooltipImg.src = blocos[bloco];
                                                montarInfo(bloco);
                                                tooltip.style.display = 'block';
                                                tooltip.style.left = event.pageX + 'px';
                                                tooltip.style.top = event.pageY + 'px';
                                            }
                                        });
                                
                                        elemento.addEventListener('mousemove', function (event) {
                                            tooltip.style.left = event.pageX + 10 + 'px';
                                            tooltip.style.top = event.pageY + 10 + 'px';
                                        });
                                
                                        elemento.addEventListener('mouseleave', function () {
                                            tooltip.style.display = 'none';
                                        });
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
