@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-server mr-3"></i> Fiche Technique
                </h1>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $resource->name }}</h2>
                        <span
                            class="inline-block mt-2 px-3 py-1 rounded-full text-sm font-semibold 
                            {{ $resource->status === 'disponible' ? 'bg-green-100 text-green-800' :
        ($resource->status === 'maintenance' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($resource->status) }}
                        </span>
                    </div>
                    <div class="text-right text-gray-500">
                        <p class="text-sm">ID: #{{ $resource->id }}</p>
                        <p class="text-sm">{{ $resource->updated_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Caractéristiques Principales</h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span class="font-medium">{{ $resource->type }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">Catégorie:</span>
                                <span class="font-medium">{{ $resource->category }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">OS:</span>
                                <span class="font-medium">{{ $resource->os ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Performance</h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between">
                                <span class="text-gray-600">CPU:</span>
                                <span class="font-medium">{{ $resource->cpu }} vCores</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">RAM:</span>
                                <span class="font-medium">{{ $resource->ram }} GB</span>
                            </li>
                        </ul>
                    </div>
                </div>

                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->id === $resource->manager_id))
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Espace Gestionnaire</h3>
                        <div class="flex gap-4">
                            <a href="{{ route('resources.edit', $resource->id) }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </a>
                            <a href="{{ route('resources.print_qr', $resource->id) }}" target="_blank"
                                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                                <i class="fas fa-qrcode mr-2"></i>Imprimer QR
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection