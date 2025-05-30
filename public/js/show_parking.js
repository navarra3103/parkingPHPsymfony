// ==================== GLOBALES ====================
window.mostrarFormulario = mostrarFormulario;

// ==================== FUNCIONES ====================
//Oscurecer 
function darkenColor(hex, percent = 20) {
    let num = parseInt(hex.replace("#", ""), 16);
    let amt = Math.round(2.55 * percent);
    let R = (num >> 16) - amt;
    let G = (num >> 8 & 0x00FF) - amt;
    let B = (num & 0x0000FF) - amt;

    return "#" + (
        0x1000000 +
        (R < 0 ? 0 : R > 255 ? 255 : R) * 0x10000 +
        (G < 0 ? 0 : G > 255 ? 255 : G) * 0x100 +
        (B < 0 ? 0 : B > 255 ? 255 : B)
    ).toString(16).slice(1);
}

// Mostrar los formularios de tipo de plaza dinámicamente   
export function mostrarFormulario(tipo) {
    document.getElementById('form-crear').style.display = tipo === 'crear' ? 'block' : 'none';
    document.getElementById('form-modificar').style.display = tipo === 'modificar' ? 'block' : 'none';
    document.getElementById('form-eliminar').style.display = tipo === 'eliminar' ? 'block' : 'none';
}

// ==================== FUNCIONES DE DATOS ====================
async function cargarDatosPlazas() {
    try {
        const response = await fetch('/api/plazas');
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Error cargando datos de plazas:', error);
        // Mostrar mensaje al usuario
        mostrarMensajeError('No se pudieron cargar los datos de las plazas');
        return null;
    }
}

function mostrarMensajeError(mensaje) {
    // Crear o actualizar un elemento de error en la UI
    let errorDiv = document.getElementById('error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'error-message';
        errorDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ff4444; color: white; padding: 10px; border-radius: 5px; z-index: 1000;';
        document.body.appendChild(errorDiv);
    }
    errorDiv.textContent = mensaje;
    errorDiv.style.display = 'block';
    
    // Ocultar después de 5 segundos
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}

// ==================== FUNCIONES DE UI ====================
function ocultarFormularios() {
    const formularios = ['form-crear', 'form-modificar', 'form-eliminar'];
    formularios.forEach(id => {
        const form = document.getElementById(id);
        if (form) form.style.display = 'none';
    });
}

function configurarPanelMatricula(matriculaInput) {
    const result = document.querySelector('.div-result');
    if (!result) {
        console.warn('No se encontró el panel de resultados (.div-result)');
        return;
    }

    // Configuración inicial del panel
    result.style.display = 'none';
    result.style.visibility = 'hidden';
    result.style.display = 'block';


    // Función para ocultar panel cuando pierde foco
    function hideIfFocusOutside() {
        setTimeout(() => {
            const active = document.activeElement;
            const isInInput = active === matriculaInput;
            const isInResult = result.contains(active);
            if (!isInInput && !isInResult) {
                result.style.visibility = 'hidden';
            }
        }, 100);
    }

    // Eventos de foco - mostrar panel
    matriculaInput.addEventListener('focus', () => {
        result.style.display = 'block';
        result.style.visibility = 'visible';
        // Posicionamiento del panel
        const offsetLeft = matriculaInput.offsetLeft;
        const offsetTop = matriculaInput.offsetTop + matriculaInput.offsetHeight;     
            result.style.left = `${offsetLeft}px`;
            result.style.top = `${offsetTop}px`;
            result.style.width = `${matriculaInput.offsetWidth}px`;
    });

    // Eventos de pérdida de foco - ocultar panel
    matriculaInput.addEventListener('blur', hideIfFocusOutside);
    result.addEventListener('blur', hideIfFocusOutside);
    
    // Mantener foco en panel al hacer click
    result.addEventListener('mousedown', () => {
        result.focus();
    });

    // Configurar selección y filtrado de opciones
    configurarOpcionesMatricula(matriculaInput, result);
}

function configurarOpcionesMatricula(matriculaInput, result) {
    // Obtener todas las opciones de matrícula
    const dataResults = document.querySelectorAll('.div-result > p');
    
    if (dataResults.length === 0) {
        console.warn('No se encontraron opciones de matrícula en .div-result > p');
        return;
    }

    // Configurar click en cada opción de matrícula
    dataResults.forEach(dataResult => {
        dataResult.addEventListener('click', (e) => {
            const selectedMatricula = e.target.innerText;
            matriculaInput.value = selectedMatricula;
            result.style.display = 'none';
        });
    });

    // Configurar filtrado de búsqueda en tiempo real
    const items = result.getElementsByTagName('p');
    
    matriculaInput.addEventListener('input', () => {
        const filter = matriculaInput.value.toLowerCase().trim();
        
        // Optimización: usar Array.from para mejor rendimiento con muchos elementos
        Array.from(items).forEach(item => {
            const txt = item.textContent.toLowerCase();
            item.style.display = txt.includes(filter) ? '' : 'none';
        });
    });
}

function configurarBotonEliminar() {
    const deleteButton = document.getElementById('delete-button');
    if (!deleteButton) return;

    deleteButton.addEventListener('click', async () => {
        const plazaId = document.getElementById('plaza-id').value;
        
        if (!confirm('¿Estás seguro de que quieres eliminar esta visita?')) {
            return;
        }

        try {
            const response = await fetch('/ShowParking/deleteVisit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `plaza=${encodeURIComponent(plazaId)}`
            });

            if (response.ok) {
                alert('Visita eliminada');
                location.reload();
            } else {
                throw new Error('Error en la respuesta del servidor');
            }
        } catch (error) {
            console.error('Error eliminando visita:', error);
            alert('Error al eliminar la visita');
        }
    });
}

function posicionarInfoPanel(infoPanel, parkingContainer, e) {
    // Mostrar en oculto para calcular dimensiones
    infoPanel.style.visibility = 'hidden';
    infoPanel.style.display = 'block';

    // Calcular posición
    const containerRect = parkingContainer.getBoundingClientRect();
    const mouseX = e.clientX - containerRect.left;
    const mouseY = e.clientY - containerRect.top;

    // Calcular umbrales
    const umbralSuperior = parkingContainer.offsetHeight * 0.1;
    const umbralInferior = parkingContainer.offsetHeight * 0.95;

    let offsetY;
    if (mouseY >= 0 && mouseY <= umbralSuperior) {
        offsetY = -10; // Arriba
    } else if (mouseY > umbralSuperior && mouseY < umbralInferior) {
        offsetY = 0; // Medio
    } else {
        offsetY = 250; // Abajo
    }

    const offsetX = infoPanel.offsetWidth / 2;
    infoPanel.style.left = `${mouseX - offsetX}px`;
    infoPanel.style.top = `${mouseY - offsetY}px`;

    // Mostrar panel
    infoPanel.style.visibility = 'visible';
    infoPanel.style.display = 'block';
}

function actualizarFormularioPlaza(plaza) {
    // ID de plaza
    const idInput = document.getElementById('plaza-id');
    if (idInput) idInput.value = plaza.id;

    // Tipo de plaza
    const tipoSelect = document.getElementById('tipo-id');
    if (tipoSelect) tipoSelect.value = plaza.tipo || '';

    // Matrícula
    const matriculaInput = document.getElementById('matricula');
    if (matriculaInput) {
        matriculaInput.value = plaza.matricula || '';
        configurarPanelMatricula(matriculaInput);
    }

    // Estado
    const estadoSelect = document.getElementById('estado');
    if (estadoSelect) {
        const estadoNombre = plaza.estado || '';
        let found = false;
        
        for (let option of estadoSelect.options) {
            if (option.text === estadoNombre) {
                estadoSelect.value = option.value;
                found = true;
                break;
            }
        }
        
        if (!found) {
            estadoSelect.value = '';
        }
    }

    // Fecha de entrada
    const entradaInput = document.getElementById('entrada');
    if (entradaInput) entradaInput.value = plaza.entrada || '';
}

// ==================== GESTION DEL DOM ====================
document.addEventListener('DOMContentLoaded', async () => {
    // Variables de objetos del DOM
    const svg = document.querySelector('svg.image-overlay');
    const rectangulos = svg?.querySelectorAll('rect[data-id]');
    const infoPanel = document.getElementById('info-panel');
    const parkingContainer = document.getElementById('parking-container') || svg?.parentElement;

    // Verificar elementos críticos
    if (!svg || !rectangulos || !infoPanel || !parkingContainer) {
        console.error('No se encontraron elementos críticos del DOM');
        mostrarMensajeError('Error al inicializar la aplicación');
        return;
    }

    // Variables de estado
    let rectActivo = null;
    let coloresOriginales = {};

    // Cargar datos desde el backend
    const data = await cargarDatosPlazas();
    if (!data) {
        return; // Error ya manejado en cargarDatosPlazas
    }

    // Procesar datos de plazas
    const infoPorPlaza = {};
    data.forEach(plaza => {
        infoPorPlaza[plaza.id] = plaza;
    });

    // Configurar cada rectángulo/plaza
    rectangulos.forEach(rect => {
        const id = rect.getAttribute('data-id');
        const plaza = infoPorPlaza[id];
        
        if (!plaza) {
            console.warn(`No se encontraron datos para la plaza ${id}`);
            return;
        }

        // Pintar plaza con color de la base de datos
        const color = plaza.color || '#00ff00';
        rect.setAttribute('fill', color);
        coloresOriginales[id] = color;

        // Añadir borde si está ocupada
        if (plaza.ocupada) {
            rect.setAttribute('stroke', 'black');
            rect.setAttribute('stroke-width', '4');
        } else {
            rect.removeAttribute('stroke');
            rect.removeAttribute('stroke-width');
        }

        // Event listener para click en plaza
        rect.addEventListener('click', (e) => {
            // Ocultar formularios
            ocultarFormularios();

            // Restaurar color del rectángulo anterior
            if (rectActivo) {
                const prevId = rectActivo.getAttribute('data-id');
                rectActivo.setAttribute('fill', coloresOriginales[prevId]);
            }

            // Oscurecer color del rectángulo actual
            const colorOscuro = darkenColor(color);
            rect.setAttribute('fill', colorOscuro);
            rectActivo = rect;

            // Actualizar formulario con datos de la plaza
            actualizarFormularioPlaza(plaza);

            // Configurar botón eliminar
            configurarBotonEliminar();

            // Posicionar y mostrar panel de información
            posicionarInfoPanel(infoPanel, parkingContainer, e);

            e.stopPropagation();
        });
    });

    // Event listener para ocultar panel al hacer click fuera
    document.addEventListener('click', (e) => {
        if (!e.target.closest('rect') && !e.target.closest('#info-panel')) {
            infoPanel.style.display = 'none';
            if (rectActivo) {
                const id = rectActivo.getAttribute('data-id');
                rectActivo.setAttribute('fill', coloresOriginales[id]);
                rectActivo = null;
            }
        }

        // Ocultar formularios al hacer click fuera
        if (!e.target.closest('#form-crear') &&
            !e.target.closest('#form-modificar') &&
            !e.target.closest('#form-eliminar') &&
            !e.target.closest('button')) {
            ocultarFormularios();
        }
    });
});