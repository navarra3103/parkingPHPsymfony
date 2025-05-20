// ==================== GLOBALES ====================
window.mostrarFormulario = mostrarFormulario;
window.enviarFormulario = enviarFormulario;

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
    // Enviar formulario update/create/delete
    export async function enviarFormulario(tipo) {
        let payload = {};
        
        if (tipo === 'add-tipo') {
            payload.nombre = document.getElementById('crear-nombre').value;
            payload.color = document.getElementById('crear-color').value;
        }
    
        if (tipo === 'update-tipo') {
            payload.id = document.getElementById('modificar-id').value;
            payload.nombre = document.getElementById('modificar-nombre').value;
            payload.color = document.getElementById('modificar-color').value;
        }
    
        if (tipo === 'delete-tipo') {
            payload.id = document.getElementById('eliminar-id').value;
        }
    
        const response = await fetch(`/ShowParking/${tipo}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
    
        if (response.ok) {
            location.reload();
        } else {
            alert('Error al procesar la acción');
        }
    }



// ==================== GESTION DEL DOM ====================
document.addEventListener('DOMContentLoaded', async () => { 
    // Varibles de objetos del DOM
    const svg = document.querySelector('svg.image-overlay');
    const rectangulos = svg.querySelectorAll('rect[data-id]');
    const infoPanel = document.getElementById('info-panel');
    const parkingContainer = document.getElementById('parking-container') || svg.parentElement;

    // Varible de activo
    let rectActivo = null;
    // Variable de color
    let coloresOriginales = {};

    // Cargar datos desde el backend
    try {
        // Hacer la consulta a /api/plazas
        const response = await fetch('/api/plazas');
        if (!response.ok) throw new Error('No se pudo cargar la información');
            // Asignarlo a una varible
            const data = await response.json();

            // array de plazas
            const infoPorPlaza = {};
            // Obtener los datos con forEach
            data.forEach(plaza => {
                infoPorPlaza[plaza.id] = plaza;
            });

            rectangulos.forEach(rect => {
            const id = rect.getAttribute('data-id');
            const plaza = infoPorPlaza[id];

                // Pintar las plazas con el color de la data base
                const color = plaza.color || '#00ff00';
                    rect.setAttribute('fill', color);
                    coloresOriginales[id] = color;

                // Click en la plaza
                rect.addEventListener('click', (e) => {
                    // Si esta activo
                    if (rectActivo) {
                        const prevId = rectActivo.getAttribute('data-id');
                        rectActivo.setAttribute('fill', coloresOriginales[prevId]);
                    }
                    // Ocurecer el color del rect
                    const colorOscuro = darkenColor(color);
                        rect.setAttribute('fill', colorOscuro);
                        rectActivo = rect;
                    // Obtener la id del parking
                    const idInput = document.getElementById('plaza-id')
                        idInput.value = plaza.id;
                    // Obtener la matricula del parking
                    const matriculaInput = document.getElementById('matricula');
                        matriculaInput.value = plaza.matricula || '';
                        // Obtener las opciones de matricula
                            const result = document.querySelector('.div-result');
                                result.style.display = 'none';
                                // Mostrar el panel opciones de matricula en oculto
                                result.style.visibility = 'hidden';
                                result.style.display = 'block';
                                // Posicionar las opciones de matricula
                                const positionInput = matriculaInput.getBoundingClientRect();
                                    result.style.left = '${positionInput.left + window.scrollX}px';
                                    result.style.top = '${positionInput.top + window.scrollY}px';
                                    result.style.width = '${positionInput.width}px';
                                // Gestion de foco para el input de matricula y el panel de opciones
                                    // tiene foco
                                    matriculaInput.addEventListener('focus', () => {
                                        result.style.display = 'block';
                                        result.style.visibility = 'visible';
                                    });
                                    // funcion de foco 
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
                                    // No tiene nadie foco
                                    matriculaInput.addEventListener('blur', hideIfFocusOutside);
                                    result.addEventListener('blur', hideIfFocusOutside);
                                    // Click en el panel de opciones
                                    result.addEventListener('mousedown', () => {
                                        result.focus();
                                    });
                                // Copiar opcionar la matricula al input
                                    // añadir los pulsadores a cada opcion
                                    const dataResults = document.querySelectorAll('.div-result > p'); 
                                        dataResults.forEach(dataResult => { // Iterate over each <p> element
                                            dataResult.addEventListener('click', (e) => {
                                                const selectedMatricula = e.target.innerText;
                                                matriculaInput.value = selectedMatricula;
                                                result.style.display = 'none';
                                            });
                                        });
                                    // buscar la matricula del parking
                                    const items = result.getElementsByTagName('p');
                                    // Detecion del cambios en el input de matricula
                                    matriculaInput.addEventListener('input', () => {
                                        const filter = matriculaInput.value.toLowerCase().trim();
                                        for (let i = 0; i < items.length; i++) {
                                            const txt = items[i].textContent.toLowerCase();
                                            if (txt.includes(filter)) {
                                                items[i].style.display = '';
                                            } else {
                                                items[i].style.display = 'none';
                                            }
                                        }
                                    });
                    // Obtener las opciones del select de tipo de plaza
                        // Opciones del select
                        const estadoSelect = document.getElementById('estado');
                            const estadoNombre = plaza.estado || '';
                        // Varible de opcione por defecto
                        var found = false;
                        // Limpiar el valor del select
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
                    // Obtener la fecha de entrada si existe si no existe poner " "
                        const entradaInput = document.getElementById('entrada');
                            entradaInput.value = plaza.entrada || '';
                    // Guardar visita / actualizar
                        document.getElementById('visita-form').addEventListener('submit', function (e) {
                            const estado = document.getElementById('estado').value;
                            if (!estado) {
                                alert('Debes seleccionar un estado válido');
                                e.preventDefault();
                            }
                        });
                    // Eliminar visita / salir
                       document.getElementById('delete-button').addEventListener('click', () => {
                            const plazaId = document.getElementById('plaza-id').value;

                            if (confirm('¿Estás seguro de que quieres eliminar esta visita?')) {
                                fetch('/ShowParking/deleteVisit', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: `plaza=${encodeURIComponent(plazaId)}`
                                })
                                .then(response => {
                                    if (response.ok) {
                                        alert('Visita eliminada');
                                        location.reload(); // Recarga la página para ver cambios
                                    } else {
                                        alert('Error al eliminar la visita');
                                    }
                                });
                            }
                        });
                    // Color el info del panel abajo si estamos mas arriba 
                        // Obtener ubicacion del panel
                        const containerRect = parkingContainer.getBoundingClientRect();
                            const mouseX = e.clientX - containerRect.left;
                            const mouseY = e.clientY - containerRect.top;
                        // Cacular el umbral de 10%
                        const umbralSuperior = parkingContainer.offsetHeight * 0.1;
                            if (mouseY >= 0 && mouseY <= umbralSuperior) {
                                // Colocar ariba
                                var offsetY = -10
                            } else {
                                // Colocar abajo
                                var offsetY = 150
                            }
                        // Colocar el panel de info en el medio de raton
                        const offsetX = infoPanel.offsetWidth / 2;
                            infoPanel.style.left = `${mouseX - offsetX}px`;
                            infoPanel.style.top = `${mouseY - offsetY}px`;
                    // Mostrar el panel de info
                    infoPanel.style.visibility = 'visible';
                    infoPanel.style.display = 'block'; 
                        
                    e.stopPropagation()
                });
            }); 
    } catch (err) {
        console.error('Error cargando datos:', err);  
    }
    // Ocultar el panel de info
    document.addEventListener('click', (e) => {
        if (!e.target.closest('rect') && !e.target.closest('#info-panel')) {
            infoPanel.style.display = 'none';
            if (rectActivo) {
                const id = rectActivo.getAttribute('data-id');
                rectActivo.setAttribute('fill', coloresOriginales[id]);
                rectActivo = null;
            }
        }
        // Ocultar formularios al hacer clic fuera
        if (!e.target.closest('#form-crear') &&
            !e.target.closest('#form-modificar') &&
            !e.target.closest('#form-eliminar') &&
            !e.target.closest('button')) {
            document.getElementById('form-crear')?.style?.setProperty('display', 'none');
            document.getElementById('form-modificar')?.style?.setProperty('display', 'none');
            document.getElementById('form-eliminar')?.style?.setProperty('display', 'none');
        }
    });


});