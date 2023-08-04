<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @if (!auth()->user()->accessToken)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Solicitar Permisos para poder acceder a la información
                        <a href="{{route('redirect')}}" class="ml-20 sm:ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Redirigir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
