// Función para oscurecer un color HEX en un porcentaje dado
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

// Manejo de interactividad al cargar el documento
document.addEventListener('DOMContentLoaded', async () => {
    const svg = document.querySelector('svg.image-overlay');
    const rectangulos = svg.querySelectorAll('rect[data-id]');
    const infoPanel = document.getElementById('info-panel');
    const parkingContainer = document.getElementById('parking-container') || svg.parentElement;

    let rectActivo = null;
    let coloresOriginales = {};

    try {
        const response = await fetch('/api/plazas');
        if (!response.ok) throw new Error('No se pudo cargar la información');

        const data = await response.json();

        const infoPorPlaza = {};
        data.forEach(plaza => {
            infoPorPlaza[plaza.id] = plaza;
        });

        rectangulos.forEach(rect => {
            const id = rect.getAttribute('data-id');
            const plaza = infoPorPlaza[id];

            if (plaza) {
                const color = plaza.color || '#00ff00';
                rect.setAttribute('fill', color);
                coloresOriginales[id] = color;

                rect.addEventListener('click', (e) => {
                    if (rectActivo) {
                        const prevId = rectActivo.getAttribute('data-id');
                        rectActivo.setAttribute('fill', coloresOriginales[prevId]);
                    }

                    const colorOscuro = darkenColor(color);
                    rect.setAttribute('fill', colorOscuro);
                    rectActivo = rect;

                    document.getElementById('plaza-id').value = plaza.id;

                    // Mostart valor de la matricula de la base de datos
                    const matriculaInput = document.getElementById('matricula');
                    matriculaInput.value = plaza.matricula || '';
                    const result = document.querySelector('.div-result');
                    result.style.display = 'none';
                    // Tener un buscador desde el input de matricula
                        // Ocultar resultados si el foco se va fuera del input y del div
                        matriculaInput.addEventListener('focus', () => {
                            result.style.display = 'block';
                        });

                        // Usar setTimeout para permitir cambiar el foco antes de ocultar
                        function hideIfFocusOutside() {
                            setTimeout(() => {
                                const active = document.activeElement;
                                const isInInput = active === matriculaInput;
                                const isInResult = result.contains(active);
                                if (!isInInput && !isInResult) {
                                    result.style.display = 'none';
                                }
                            }, 100);
                        }

                        // Eventos de blur
                        matriculaInput.addEventListener('blur', hideIfFocusOutside);
                        result.addEventListener('blur', hideIfFocusOutside);

                        // Si haces clic en el div, darle foco
                        result.addEventListener('mousedown', () => {
                            result.focus();
                        });

                        // Filtrar resultados al escribir






                    // Obtener el select de tipo y establecer el valor correspondiente
                    const estadoSelect = document.getElementById('estado');
                    const estadoNombre = plaza.estado || '';
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
                    document.getElementById('entrada').value = plaza.entrada || '';
                    
                    // comprobar si existe el el select no es null
                    document.getElementById('visita-form').addEventListener('submit', function (e) {
                        const estado = document.getElementById('estado').value;
                        if (!estado) {
                            alert('Debes seleccionar un estado válido');
                            e.preventDefault();
                        }
                    });

                    // elimanr vista 
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


                    // Mostrar temporalmente el infoPanel oculto para medir tamaño
                    infoPanel.style.visibility = 'hidden';
                    infoPanel.style.display = 'block';

                    // Calcular posición del cursor respecto al contenedor
                    const containerRect = parkingContainer.getBoundingClientRect();
                    const mouseX = e.clientX - containerRect.left;
                    const mouseY = e.clientY - containerRect.top;

                    // Calcular el umbral del 10% de la altura del contenedor
                    const umbralSuperior = parkingContainer.offsetHeight * 0.1;
                    if (mouseY >= 0 && mouseY <= umbralSuperior) {
                        var offsetY = -10
                    } else {
                        var offsetY = 150
                    }


                    const offsetX = infoPanel.offsetWidth / 2;

                    infoPanel.style.position = 'absolute';
                    infoPanel.style.left = `${mouseX - offsetX}px`;
                    infoPanel.style.top = `${mouseY - offsetY}px`;
                    infoPanel.style.display = 'block';

                     // Mostrar finalmente el panel con estilo visible
                    infoPanel.style.visibility = 'visible';
                    infoPanel.style.display = 'block';
                    
                    e.stopPropagation();
                });
            }
        });

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
                !e.target.closest('button')) {
                document.getElementById('form-crear')?.style?.setProperty('display', 'none');
                document.getElementById('form-modificar')?.style?.setProperty('display', 'none');
            }
        });

    } catch (err) {
        console.error('Error cargando datos:', err);
    }
});

// Mostrar formulario dinámicamente
export function mostrarFormulario(tipo) {
    document.getElementById('form-crear').style.display = tipo === 'crear' ? 'block' : 'none';
    document.getElementById('form-modificar').style.display = tipo === 'modificar' ? 'block' : 'none';
}

// Enviar formulario para crear o modificar tipo
export function enviarFormulario(tipo) {
    const url = tipo === 'crear' ? '/ShowParking/add-tipo' : '/ShowParking/update-tipo';
    const data = new FormData();

    if (tipo === 'crear') {
        data.append('nombre', document.getElementById('crear-nombre').value);
        data.append('color', document.getElementById('crear-color').value);
    } else {
        data.append('id', document.getElementById('modificar-id').value);
        data.append('nombre', document.getElementById('modificar-nombre').value);
        data.append('color', document.getElementById('modificar-color').value);
    }

    fetch(url, {
        method: 'POST',
        body: data
    })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                alert('¡Operación exitosa!');
                location.reload();
            } else {
                alert('Error: ' + json.error);
            }
        })
        .catch(err => {
            alert('Error en la petición.');
            console.error(err);
        });
}

// Hacer las funciones accesibles globalmente
window.mostrarFormulario = mostrarFormulario;
window.enviarFormulario = enviarFormulario;

