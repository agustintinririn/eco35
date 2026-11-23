# Historias de Usuario - Proyecto Eco35 (CBTa No. 35 "Leona Vicario")

* **Estudiante:** Bruno Agustín Morales Patiño
* **Carrera:** Técnico en Programación | **Grupo:** 3 B PRVC
* **Profesor / Evaluador:** Mariano Sánchez Reyes
* **Institución:** CBTa No. 35 (San Juan Tlalpizáhuac, Ixtapaluca, Estado de México)
* **Archivo Base:** `Plan_Reforestacion_CBTa35.xlsx`



## Matriz de Trazabilidad (Excel Original vs. Módulos Laravel)

| Pestaña de Excel | Operación / Fórmula Replicada | Historias de Usuario |
| :--- | :--- | :--- |
| `Plan_Laravel` | Tareas T-001 a T-006 (Auth Bcrypt y Roles RBAC) | HU-01, HU-02 |
| `Reforestacion` (Col B-C) | Catálogo de especies nativas y % base de supervivencia | HU-03, HU-04 |
| `Alertas` / Mapa GIS | Reporte de truzas y semáforo de agua en canales de riego | HU-05, HU-06 |
| `Reforestacion` (Fórmula F) | `=REDONDEAR(Cantidad * Tasa, 0)` (Penalización 25% agua roja) | HU-07, HU-08 |
| `Info_CBTa35` | `=SI(Total>=500, "Alcanzada", "En Progreso")` y portal `cbta35.edu.mx` | HU-09, HU-10 |
| `Administracion` | Sincronización `Maatwebsite/Laravel-Excel` y acuses PDF | HU-11, HU-12 |



## Módulo 1: Autenticación y Roles (Hoja `Plan_Laravel`)

### HU-01: Iniciar sesión según mi función en el plantel
* **Como:** Alumno, maestro o administrador del CBTa 35,
* **Quiero:** Ingresar a la plataforma con mi correo institucional o RFC y contraseña encriptada,
* **Para:** Acceder únicamente a las herramientas autorizadas para mi rol
* **Criterios de aceptación (Tareas T-001 a T-003):**
  1. Valida credenciales en MySQL usando encriptación `Bcrypt` de Laravel
  2. Redirige al panel correspondiente según el rol (Administrador, Docente o Estudiante)
  3. Muestra alerta de error si la contraseña falla sin borrar el usuario escrito

### HU-02: Registrar y administrar usuarios y roles
* **Como:** Administrador del sistema Eco35,
* **Quiero:** Dar de alta, editar o desactivar cuentas asignándoles su rol,
* **Para:** Mantener el control del personal autorizado para registrar jornadas
* **Criterios de aceptación (Tareas T-004 a T-006):**
  1. Formulario con nombre completo, RFC, correo institucional y rol
  2. Impide duplicar RFC o correos en la base de datos
  3. Permite restablecer contraseñas mediante clave temporal



## Módulo 2: Catálogo Botánico y Resistencia Hídrica (Hoja `Reforestacion`)

### HU-03: Consultar especies de árboles aptas para reforestar
* **Como:** Estudiante o profesor del área Agropecuaria,
* **Quiero:** Revisar el catálogo de especies nativas (*Pinus montezumae*, *Quercus rugosa*, *Fraxinus uhdei*, *Schinus molle*),
* **Para:** Conocer su tasa base de supervivencia y resistencia al agua contaminada antes de plantar
* **Criterios de aceptación (Columnas B y C):**
  1. Ficha técnica con nombre común, científico, cepa recomendada (`40x40x40 cm`) y tolerancia hídrica
  2. Muestra tasas base del Excel: Pino Moctezuma (`85%`), Encino (`80%`), Fresno (`70%`) y Pirul (`90%`)
  3. Buscador en tiempo real por nombre de árbol

### HU-04: Gestionar fichas botánicas y parámetros de resistencia
* **Como:** Docente Agropecuario,
* **Quiero:** Agregar nuevas especies o ajustar sus porcentajes de supervivencia base,
* **Para:** Mantener actualizada la información según los resultados observados en vivero
* **Criterios de aceptación:**
  1. Permite editar porcentaje base y categoría de resistencia (Sensible, Moderada, Alta)
  2. Valida campos obligatorios y rangos válidos de `0%` a `100%`



## Módulo 3: Mapa Geoespacial de Alertas (Hoja `Alertas`)

### HU-05: Reportar madera tirada (truzas) o fuentes de agua sucia
* **Como:** Brigadista o estudiante de reforestación,
* **Quiero:** Marcar en el mapa puntos con acumulación ilegal de truzas o canales de agua contaminada,
* **Para:** Evitar sembrar en zonas de riesgo o regar con agua tóxica
* **Criterios de aceptación:**
  1. Captura coordenadas GPS al hacer clic en el mapa de San Juan Tlalpizáhuac
  2. Clasifica la incidencia en `Descontrol de Truzas` o `Toma de Agua Contaminada`
  3. Asigna semáforo hídrico: Verde (Apta), Amarillo (Moderada) o Rojo (Contaminada)
### HU-06: Visualizar el mapa ambiental de San Juan Tlalpizáhuac
* **Como:** Usuario del sistema Eco35,
* **Quiero:** Ver un mapa interactivo con marcadores de colores,
* **Para:** Diferenciar rápidamente las siembras activas de las alertas ambientales
* **Criterios de aceptación (`Leaflet.js` + OpenStreetMap):**
  1. Pines diferenciados: Verde (Siembras), Naranja (Truzas) y Rojo (Agua contaminada)
  2. Ventana emergente (`popup`) con fecha, responsable y detalles al pulsar cualquier pin



## Módulo 4: Registro de Siembras y Algoritmo (Fórmulas `Reforestacion`)

### HU-07: Registrar una jornada de reforestación en campo
* **Como:** Docente o coordinador de brigada,
* **Quiero:** Capturar fecha, zona, especie, cantidad de plantas y fuente de agua utilizada,
* **Para:** Actualizar la bitácora oficial del plantel
* **Criterios de aceptación:**
  1. Desplegables vinculados al catálogo botánico y zonas del mapa
  2. Selección obligatoria de fuente de agua (Pozo del plantel, Vivero o Canal aledaño)

### HU-08: Cálculo automático de supervivencia ajustado por calidad de agua
* **Como:** Docente Agropecuario,
* **Quiero:** Que el sistema calcule cuántos árboles sobrevivirán replicando la fórmula de Excel y restando `25%` si se usa agua contaminada,
* **Para:** Obtener pronósticos realistas sin hacer cálculos manuales
* **Criterios de aceptación (`=REDONDEAR(Cantidad * Tasa, 0)`):**
  1. Agua limpia (Verde): aplica `REDONDEAR(Cantidad * Tasa_Base, 0)`
  2. Agua contaminada (Rojo): aplica castigo automático del `25%` (`Tasa_Base * 0.75`) por daño micorrízico.
  3. Acumula totales por especie replicando `=SUMAR.SI()`



## Módulo 5: Dashboard e Información Institucional (Hoja `Info_CBTa35`)

### HU-09: Monitorear el avance hacia la meta global de 500 árboles
* **Como:** Directivo, docente o alumno del CBTa 35,
* **Quiero:** Visualizar un tablero principal con KPIs y barra de progreso en tiempo real,
* **Para:** Conocer el porcentaje cumplido rumbo a la meta de 500 árboles.
* **Criterios de aceptación (`=SI(Total>=500, "Alcanzada", "En Progreso")`):**
  1. Tarjeta KPI de Árboles Plantados vs. Meta (`500`) con barra porcentual
  2. Cambia automáticamente el estatus de `En Progreso` a `Meta Alcanzada` al llegar a `500`

### HU-10: Consultar la información oficial del CBTa 35
* **Como:** Evaluador o visitante,
* **Quiero:** Ver la ficha institucional del plantel extraída del Excel,
* **Para:** Validar la ubicación y vinculación académica del proyecto
* **Criterios de aceptación:**
  1. Muestra dirección oficial: Carretera Federal México-Puebla Km 22.5, San Juan Tlalpizáhuac, C.P. 56618.
  2. Incluye enlace directo al portal oficial `cbta35.edu.mx`



## Módulo 6: Sincronización y Reportes (Hoja `Administracion`)

### HU-11: Sincronizar registros con `Plan_Reforestacion_CBTa35.xlsx`
* **Como:** Administrador del sistema,
* **Quiero:** Exportar e importar jornadas hacia la plantilla oficial de Excel,
* **Para:** Entregar reportes administrativos sin capturar celda por celda
* **Criterios de aceptación (`Maatwebsite/Laravel-Excel`):**
  1. Descarga archivo `.xlsx` compatible con las fórmulas originales del plantel
  2. Permite importar archivos Excel para carga masiva de registros en MySQL

### HU-12: Descargar acuses en PDF y consultar bitácora de auditoría
* **Como:** Profesor evaluador o administrador,
* **Quiero:** Imprimir acuses en PDF y revisar el historial de cambios del sistema,
* **Para:** Contar con evidencia física firmada y trazabilidad inalterable
* **Criterios de aceptación:**
  1. Genera PDF con membrete oficial del CBTa 35
  2. Registra en bitácora (`logs`) fecha, hora, usuario y acción realizada