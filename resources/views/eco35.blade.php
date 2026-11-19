<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eco35 - CBTa No. 35 "Leona Vicario"</title>
    <!-- Estilos de Leaflet por si hay internet -->
    <link rel="stylesheet" href="https://unpkg.com" />
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { margin: 0; background-color: #f0f4f1; color: #1e293b; }
        .navbar { background: linear-gradient(135deg, #15803d, #166534); color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .navbar h1 { margin: 0; font-size: 1.3rem; }
        .container { max-width: 1200px; margin: 24px auto; padding: 0 16px; }
        .alerta-ok { background: #dcfce7; border-left: 5px solid #16a34a; color: #14532d; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
        
        /* Tarjetas KPI */
        .kpi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
        .kpi-card { background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border-top: 5px solid #16a34a; }
        .kpi-card.azul { border-top-color: #0284c7; }
        .kpi-card.rojo { border-top-color: #dc2626; }
        .kpi-card h3 { margin: 0; font-size: 0.9rem; color: #64748b; text-transform: uppercase; }
        .kpi-card .numero { font-size: 2rem; font-weight: 800; margin: 10px 0; color: #15803d; }
        .kpi-card.azul .numero { color: #0284c7; }
        .kpi-card.rojo .numero { color: #dc2626; }
        .badge { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }

        /* Contenido principal */
        .main-grid { display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px; }
        .panel { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); margin-bottom: 20px; }
        .panel-header { background: #1e293b; color: white; padding: 12px 20px; font-weight: bold; }
        .panel-header.verde { background: #15803d; }
        .panel-header.amarillo { background: #eab308; color: #1e293b; }
        .panel-body { padding: 20px; }

        /* Mapa */
        #mapa { height: 420px; width: 100%; border-radius: 8px; background: #e2e8f0; border: 2px dashed #94a3b8; }

        /* Formularios */
        label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; margin-bottom: 14px; font-size: 0.95rem; }
        .fila-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .checkbox-box { background: #fef2f2; border: 1px solid #fecaca; padding: 10px; border-radius: 8px; margin-bottom: 14px; color: #b91c1c; font-weight: 600; font-size: 0.85rem; }
        button { width: 100%; padding: 12px; border: none; border-radius: 8px; font-weight: bold; font-size: 1rem; cursor: pointer; transition: 0.2s; }
        .btn-verde { background: #16a34a; color: white; }
        .btn-verde:hover { background: #15803d; }
        .btn-amarillo { background: #eab308; color: #1e293b; }
        .btn-amarillo:hover { background: #ca8a04; }
    </style>
</head>
<body>

<div class="navbar">
    <h1>🌱 Eco35 | Sistema de Monitoreo Ambiental - CBTa No. 35</h1>
    <span>San Juan Tlalpizáhuac, Ixtapaluca</span>
</div>

<div class="container">
    @if(session('mensaje'))
        <div class="alerta-ok"> {{ session('mensaje') }}</div>
    @endif

    <!-- MÓDULO 5: Dashboard KPI -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <h3>Árboles Plantados (Meta: 500)</h3>
            <div class="numero">{{ $totalPlantados }} / 500</div>
            <span class="badge">{{ $estadoMeta }}</span>
        </div>
        <div class="kpi-card azul">
            <h3>Supervivencia Estimada</h3>
            <div class="numero">{{ $totalSupervivientes }} árboles</div>
            <small>Calculado con factor hídrico</small>
        </div>
        <div class="kpi-card rojo">
            <h3>Alertas Activas (Truzas / Agua)</h3>
            <div class="numero">{{ count($alertas) }}</div>
            <small>Polígono CBTa 35</small>
        </div>
    </div>

    <div class="main-grid">
        <!-- MÓDULO 3: Mapa Geoespacial -->
        <div class="panel">
            <div class="panel-header"> Mapa WebGIS: Alertas de Truzas y Calidad del Agua</div>
            <div class="panel-body">
                <div id="mapa"></div>
                <h4 style="margin-top: 20px; margin-bottom: 10px; color: #1e293b;">📋 Historial de Alertas en el Plantel</h4>
<table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
    <thead>
        <tr style="background: #f1f5f9; text-align: left; border-bottom: 2px solid #cbd5e1;">
            <th style="padding: 8px;">Tipo</th>
            <th style="padding: 8px;">Semáforo</th>
            <th style="padding: 8px;">Descripción</th>
            <th style="padding: 8px;">Acción</th>
        </tr>
    </thead>
    <tbody>
        @forelse($alertas as $a)
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 8px; font-weight: bold;">{{ strtoupper($a->tipo) }}</td>
                <td style="padding: 8px;">
                    @if($a->semaforo == 'rojo')  Rojo
                    @elseif($a->semaforo == 'amarillo')  Amarillo
                    @else  Verde @endif
                </td>
                <td style="padding: 8px;">{{ $a->descripcion }}</td>
                <td style="padding: 8px;">
                    <form action="{{ route('alertas.destroy', $a->id) }}" method="POST" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#dc2626; color:white; padding:5px 10px; font-size:0.75rem; border-radius:5px; width:auto;">
                            🗑️ Borrar
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="padding: 12px; text-align: center; color: #94a3b8;">Aún no hay alertas registradas.</td>
            </tr>
        @endforelse
    </tbody>
</table>

                <p style="font-size: 0.85rem; color: #64748b; margin-top: 10px;">
                     <b>Tip:</b> Haz clic en cualquier punto del mapa para capturar automáticamente las coordenadas GPS.
                </p>
            </div>
        </div>

        <!-- MÓDULO 3 y 4: Formularios -->
        <div>
            <div class="panel">
                <div class="panel-header verde"> Registrar Jornada de Siembra</div>
                <div class="panel-body">
                    <form action="{{ route('siembras.store') }}" method="POST">
                        @csrf
                        <label>Especie Nativa</label>
                        <select name="especie_id" required>
                            @forelse($especies as $esp)
                                <option value="{{ $esp->id }}">
                                    {{ $esp->nombre_cientifico }} (Base: {{ $esp->tasa_base * 100 }}%)
                                </option>
                            @empty
                                <option value="">Faltan cargar especies en BD</option>
                            @endforelse
                        </select>

                        <label>Cantidad de Plántulas</label>
                        <input type="number" name="cantidad" min="1" placeholder="Ej. 50" required>

                        <div class="checkbox-box">
                            <input type="checkbox" name="agua_roja" id="aguaRoja">
                            <label for="aguaRoja" style="display:inline; color:#b91c1c;">
                                 Regado con agua en Semáforo Rojo (-25% supervivencia)
                            </label>
                        </div>

                        <button type="submit" class="btn-verde">Calcular y Guardar Siembra</button>
                    </form>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header amarillo"> Reportar Alerta en Mapa</div>
                <div class="panel-body">
                    <form action="{{ route('alertas.store') }}" method="POST">
                        @csrf
                        <div class="fila-2">
                            <div>
                                <label>Tipo de Riesgo</label>
                                <select name="tipo">
                                    <option value="agua">Calidad del Agua</option>
                                    <option value="truza">Acopio de Truzas</option>
                                </select>
                            </div>
                            <div>
                                <label>Semáforo</label>
                                <select name="semaforo">
                                    <option value="verde"> Verde (Apta)</option>
                                    <option value="amarillo"> Amarillo (Moderada)</option>
                                    <option value="rojo"> Rojo (Contaminada)</option>
                                </select>
                            </div>
                        </div>

                        <label>Descripción del hallazgo</label>
                        <input type="text" name="descripcion" placeholder="Ej. Canal con descarga orgánica" required>

                        <div class="fila-2">
                            <input type="text" id="lat" name="latitud" placeholder="Latitud" readonly required>
                            <input type="text" id="lng" name="longitud" placeholder="Longitud" readonly required>
                        </div>

                        <button type="submit" class="btn-amarillo">Publicar Alerta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- SECCIÓN: HISTORIAS DE USUARIO (BACKLOG SCRUM) -->
    <div class="panel" style="margin-top: 24px;">
        <div class="panel-header" style="background: #0f172a; display: flex; justify-content: space-between; align-items: center;">
            <span> Historias de Usuario y Backlog Scrum (HU-01 a HU-12)</span>
            <select id="filtroSprint" onchange="filtrarHU()" style="width: auto; margin: 0; padding: 4px 10px; font-size: 0.85rem; border-radius: 6px;">
                <option value="TODOS">Todos los Sprints</option>
                <option value="Sprint 1">Sprint 1 (Auth y Roles)</option>
                <option value="Sprint 2">Sprint 2 (Catálogo)</option>
                <option value="Sprint 3">Sprint 3 (Mapa GIS)</option>
                <option value="Sprint 4">Sprint 4 (Siembras)</option>
                <option value="Sprint 5">Sprint 5 (Dashboard)</option>
                <option value="Sprint 6">Sprint 6 (Sincronización)</option>
            </select>
        </div>
        <div class="panel-body" style="overflow-x: auto;">
            <table id="tablaHU" style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #f1f5f9; text-align: left; border-bottom: 2px solid #cbd5e1;">
                        <th style="padding: 10px;">ID</th>
                        <th style="padding: 10px;">Sprint / Módulo</th>
                        <th style="padding: 10px;">Rol</th>
                        <th style="padding: 10px;">Historia de Usuario</th>
                        <th style="padding: 10px;">Criterios de Aceptación</th>
                        <th style="padding: 10px;">Prioridad</th>
                    </tr>
                </thead>
                                <tbody>
                    <tr data-sprint="Sprint 1" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-01</td>
                        <td style="padding: 10px;"><b>Sprint 1</b><br><small>Módulo 1: Autenticación y Roles</small></td>
                        <td style="padding: 10px;">Estudiante / Docente / Admin</td>
                        <td style="padding: 10px;">Como usuario del CBTa 35, quiero iniciar sesión con mi correo institucional o RFC y mi contraseña encriptada, para ingresar de forma segura y acceder únicamente a las funciones autorizadas para mi rol.</td>
                        <td style="padding: 10px;">1. Valida credenciales contra BD con Bcrypt. 2. Redirige según rol. 3. Muestra alerta de error sin borrar el usuario.</td>
                        <td style="padding: 10px;"><span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-weight:bold;">Alta</span></td>
                    </tr>
                    <tr data-sprint="Sprint 1" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-02</td>
                        <td style="padding: 10px;"><b>Sprint 1</b><br><small>Módulo 1: Autenticación y Roles</small></td>
                        <td style="padding: 10px;">Administrador</td>
                        <td style="padding: 10px;">Como administrador del sistema Eco35, quiero dar de alta, editar y asignar roles a profesores y alumnos, para mantener un control estricto del personal autorizado para registrar jornadas.</td>
                        <td style="padding: 10px;">1. Formulario con RFC, correo y rol. 2. Impide duplicados. 3. Permite restablecer contraseña.</td>
                        <td style="padding: 10px;"><span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-weight:bold;">Alta</span></td>
                    </tr>
                    <tr data-sprint="Sprint 2" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-03</td>
                        <td style="padding: 10px;"><b>Sprint 2</b><br><small>Módulo 2: Catálogo Botánico</small></td>
                        <td style="padding: 10px;">Estudiante / Docente</td>
                        <td style="padding: 10px;">Como estudiante o docente agropecuario, quiero consultar el catálogo de especies nativas (Pino Moctezuma, Encino, Fresno, Pirul), para conocer su porcentaje base de supervivencia y resistencia al agua sucia.</td>
                        <td style="padding: 10px;">1. Ficha técnica y cepa 40x40x40 cm. 2. Tasa base: Pino (85%), Encino (80%), Fresno (70%), Pirul (90%). 3. Buscador en tiempo real.</td>
                        <td style="padding: 10px;"><span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-weight:bold;">Alta</span></td>
                    </tr>
                    <tr data-sprint="Sprint 2" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-04</td>
                        <td style="padding: 10px;"><b>Sprint 2</b><br><small>Módulo 2: Catálogo Botánico</small></td>
                        <td style="padding: 10px;">Docente Agropecuario</td>
                        <td style="padding: 10px;">Como docente agropecuario, quiero agregar nuevas especies nativas o ajustar sus parámetros de resistencia hídrica, para mantener actualizada la información técnica según las prácticas de vivero.</td>
                        <td style="padding: 10px;">1. Modifica porcentaje base y tolerancia hídrica. 2. Valida rango 0%-100%.</td>
                        <td style="padding: 10px;"><span style="background:#fef3c7; color:#b45309; padding:3px 8px; border-radius:12px; font-weight:bold;">Media</span></td>
                    </tr>
                    <tr data-sprint="Sprint 3" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-05</td>
                        <td style="padding: 10px;"><b>Sprint 3</b><br><small>Módulo 3: Mapa Geoespacial</small></td>
                        <td style="padding: 10px;">Estudiante / Voluntario</td>
                        <td style="padding: 10px;">Como brigadista de reforestación del CBTa 35, quiero marcar en el mapa las zonas con madera tirada (truzas) o canales de agua contaminada, para advertir a la comunidad y no sembrar en zonas de riesgo.</td>
                        <td style="padding: 10px;">1. Captura coordenadas al dar clic. 2. Clasifica en Truzas o Agua Contaminada. 3. Asigna semáforo hídrico.</td>
                        <td style="padding: 10px;"><span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-weight:bold;">Alta</span></td>
                    </tr>
                    <tr data-sprint="Sprint 3" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-06</td>
                        <td style="padding: 10px;"><b>Sprint 3</b><br><small>Módulo 3: Mapa Geoespacial</small></td>
                        <td style="padding: 10px;">Usuario del Sistema</td>
                        <td style="padding: 10px;">Como usuario de la plataforma Eco35, quiero consultar un mapa ambiental interactivo con pines de colores, para identificar rápidamente las siembras activas y los focos de alerta ambiental.</td>
                        <td style="padding: 10px;">1. Marcadores por color según semáforo. 2. Ventana de detalle al presionar cualquier pin.</td>
                        <td style="padding: 10px;"><span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-weight:bold;">Alta</span></td>
                    </tr>
                    <tr data-sprint="Sprint 4" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-07</td>
                        <td style="padding: 10px;"><b>Sprint 4</b><br><small>Módulo 4: Registro de Siembras</small></td>
                        <td style="padding: 10px;">Docente / Coordinador</td>
                        <td style="padding: 10px;">Como docente o coordinador de brigada, quiero registrar los datos de una siembra (especie, cantidad y calidad del agua), para actualizar la bitácora oficial.</td>
                        <td style="padding: 10px;">1. Desplegables alimentados por el catálogo botánico. 2. Selección de condición hídrica.</td>
                        <td style="padding: 10px;"><span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-weight:bold;">Alta</span></td>
                    </tr>
                    <tr data-sprint="Sprint 4" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-08</td>
                        <td style="padding: 10px;"><b>Sprint 4</b><br><small>Módulo 4: Registro de Siembras</small></td>
                        <td style="padding: 10px;">Docente Agropecuario</td>
                        <td style="padding: 10px;">Como docente agropecuario, quiero que el sistema calcule automáticamente los árboles que sobrevivirán aplicando la penalización si el agua está contaminada, para obtener pronósticos precisos.</td>
                        <td style="padding: 10px;">1. Agua limpia: REDONDEAR(Cantidad * Tasa_Base). 2. Semáforo Rojo: Descuento automático del 25%.</td>
                        <td style="padding: 10px;"><span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-weight:bold;">Alta</span></td>
                    </tr>
                    <tr data-sprint="Sprint 5" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-09</td>
                        <td style="padding: 10px;"><b>Sprint 5</b><br><small>Módulo 5: Dashboard e Info</small></td>
                        <td style="padding: 10px;">Usuario del Sistema</td>
                        <td style="padding: 10px;">Como alumno o profesor, quiero ver un tablero principal con tarjetas KPI en tiempo real, para saber el avance del plantel rumbo a la meta de 500 árboles.</td>
                        <td style="padding: 10px;">1. Tarjeta KPI Plantados vs Meta (500). 2. Cambia estado de 'En Progreso' a 'Alcanzada'. 3. Contador de alertas activas.</td>
                        <td style="padding: 10px;"><span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-weight:bold;">Alta</span></td>
                    </tr>
                    <tr data-sprint="Sprint 5" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: bold; color: #15803d; white-space: nowrap;">HU-10</td>
                        <td style="padding: 10px;"><b>Sprint 5</b><br><small>Módulo 5: Dashboard e Info</small></td>
                        <td style="padding: 10px;">Evaluador / Visitante</td>
                        <td style="padding: 10px;">Como evaluador o visitante, quiero ver la ficha oficial del CBTa 35, para validar los datos de ubicación y vinculación institucional.</td>
                        <td style="padding: 10px;">1. Dirección oficial: Carr. Fed. México-Puebla Km 22.5, San Juan Tlalpizáhuac, Ixtapaluca.</td>
                        <td style="padding: 10px;"><span style="background:#fef3c7; color:#b45309; padding:3px 8px; border-radius:12px; font-weight:bold;">Media</span></td>


<script>
    const contenedor = document.getElementById('mapa');
    contenedor.style.position = 'relative';
    contenedor.style.overflow = 'hidden';
    contenedor.style.cursor = 'crosshair';

    // Cambia 'cbta35.png' por 'eco35.png' si así se llama tu foto en public
    contenedor.innerHTML = `<img src="{{ asset('cbta35.png') }}" style="width:100%; height:100%; object-fit:cover; display:block;" alt="Mapa CBTa 35">`;

    const alertas = @json($alertas);

    function ponerPunto(xPorc, yPorc, color, esTemporal, texto = '') {
        if (esTemporal) {
            const viejo = document.getElementById('pin-temp');
            if (viejo) viejo.remove();
        }
        const punto = document.createElement('div');
        if (esTemporal) punto.id = 'pin-temp';
        punto.style.position = 'absolute';
        punto.style.left = xPorc + '%';
        punto.style.top = yPorc + '%';
        punto.style.width = '20px';
        punto.style.height = '20px';
        punto.style.borderRadius = '50%';
        punto.style.backgroundColor = color;
        punto.style.border = '3px solid white';
        punto.style.boxShadow = '0 0 6px rgba(0,0,0,0.8)';
        punto.style.transform = 'translate(-50%, -50%)';
        punto.style.zIndex = '10';

        if (!esTemporal && texto !== '') {
            punto.style.cursor = 'pointer';
            punto.addEventListener('click', function(e) {
                e.stopPropagation();
                alert('📌 REPORTE EN MAPA:\n\n' + texto);
            });
        }

        contenedor.appendChild(punto);
    }

    alertas.forEach(a => {
        const xPorc = ((parseFloat(a.longitud) + 98.9510) / 0.0060) * 100;
        const yPorc = ((19.3255 - parseFloat(a.latitud)) / 0.0060) * 100;
        const color = a.semaforo === 'rojo' ? '#dc2626' : (a.semaforo === 'amarillo' ? '#eab308' : '#16a34a');
        const info = `${a.tipo.toUpperCase()} (${a.semaforo}): ${a.descripcion}`;
        ponerPunto(Math.max(5, Math.min(95, xPorc)), Math.max(5, Math.min(95, yPorc)), color, false, info);
    });

    contenedor.addEventListener('click', function(e) {
        const rect = contenedor.getBoundingClientRect();
        const xPorc = ((e.clientX - rect.left) / rect.width) * 100;
        const yPorc = ((e.clientY - rect.top) / rect.height) * 100;

        const lat = (19.3255 - (yPorc / 100) * 0.0060).toFixed(6);
        const lng = (-98.9510 + (xPorc / 100) * 0.0060).toFixed(6);

        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        ponerPunto(xPorc, yPorc, '#2563eb', true);
    });
</script>
</body>
</html>


