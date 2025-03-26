@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-secondary text-white" style="border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-tachometer-alt mr-2"></i>Panel de Permisos
                        </h5>
                    </div>

                    <div class="card-body" style="background-color: #f8fafc;">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert"
                                style="border-radius: 8px;">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if ($permissionsCount == 0)
                            <div class="alert alert-warning" style="border-radius: 8px;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No tienes módulos o permisos asignados actualmente.
                            </div>
                        @else
                            <div class="row">
                                @foreach ($modules as $moduleSlug => $moduleData)
                                    @can($moduleData['view_permission'])
                                        <div class="col-lg-6 mb-4">
                                            <div class="card h-100 shadow-sm"
                                                style="border-radius: 10px; border-left: 4px solid var(--module-color);">
                                                <div class="card-header bg-white d-flex justify-content-between align-items-center cursor-pointer"
                                                    data-toggle="collapse" data-target="#{{ $moduleSlug }}Module"
                                                    aria-expanded="false" aria-controls="{{ $moduleSlug }}Module"
                                                    style="border-radius: 10px 10px 0 0; transition: all 0.3s;">
                                                    <h5 class="mb-0">
                                                        <i
                                                            class="fas fa-{{ $moduleData['icon'] }} text-{{ $moduleData['color'] }} mr-2"></i>
                                                        <span style="">{{ ucfirst($moduleData['name']) }}</span>
                                                    </h5>
                                                    <div>
                                                        <span class="badge badge-pill mr-2"
                                                            style="background-color: var(--module-bg); ">
                                                            {{ count($moduleData['permissions']) }} permisos
                                                        </span>
                                                        <i class="fas fa-chevron-down collapse-icon"></i>
                                                    </div>
                                                </div>
                                                
                                                <div class="collapse" id="{{ $moduleSlug }}Module">
                                                    <div class="card-body" style="background-color: #fefefe;">
                                                        <div class="list-group list-group-flush">
                                                            @foreach ($moduleData['permissions'] as $permission)
                                                                @php
                                                                    $details = is_array($permission->details)
                                                                        ? $permission->details
                                                                        : json_decode($permission->details, true);
                                                                    $actionType = explode(' ', $permission->name)[0];
                                                                @endphp

                                                                <div class="list-group-item border-0 px-0 py-2"
                                                                    style="background-color: transparent;">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="mr-3">
                                                                            <i
                                                                                class="fas {{ $details['permission']['icon'] ?? 'fa-cog' }} {{ $details['permission']['color'] ?? 'text-dark' }} h4 mb-0"></i>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <div style="color: #4a5568; font-weight: 500;">
                                                                                {{ $details['descriptions']['es'] ?? 'Sin descripción' }}
                                                                            </div>
                                                                            <div class="mt-1">
                                                                                <small class="text-muted">
                                                                                    <i class="fas fa-key mr-1"></i>
                                                                                    {{ $details['permission']['code'] ?? '' }}
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-light" style="border-radius: 0 0 10px 10px;">
                        <small class="text-muted">
                            <i class="fas fa-clock mr-1"></i>
                            Actualizado el {{ now()->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            :root {
                --module-primary: #4e73df;
                --module-success: #1cc88a;
                --module-warning: #f6c23e;
                --module-info: #36b9cc;
            }

            .cursor-pointer {
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .cursor-pointer:hover {
                transform: translateY(-2px);
            }

            .collapse-icon {
                transition: transform 0.3s ease;
            }

            .collapsed .collapse-icon {
                transform: rotate(-90deg);
            }

            .bg-gradient-primary {
                background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            }

            .card {
                transition: all 0.3s ease;
                border: none;
            }

            .card:hover {
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .list-group-item {
                transition: background-color 0.3s ease;
            }

            .list-group-item:hover {
                background-color: #f8f9fa !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Convertir hex a rgb
                function hexToRgb(hex) {
                    hex = hex.trim().replace('#', '');
                    var r = parseInt(hex.substring(0, 2), 16);
                    var g = parseInt(hex.substring(2, 4), 16);
                    var b = parseInt(hex.substring(4, 6), 16);
                    return r + ',' + g + ',' + b;
                }
            });
        </script>
    @endpush
@endsection