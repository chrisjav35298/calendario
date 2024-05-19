    // Función para validar que la fecha seleccionada no sea anterior al día de hoy

    document.addEventListener('DOMContentLoaded', function() {
        const inputFecha = document.getElementById('solicitar_turno_fecha');
        const fechaActual = new Date();

        inputFecha.addEventListener('change', function(event) {
            const fechaIngresada = new Date(event.target.value);

            // Verificar si la fecha ingresada es menor que la fecha actual
            if (fechaIngresada < fechaActual) {
                alert('No puede seleccionar una fecha anterior al día de hoy!');
                event.target.value = ''; // Limpiar el valor del input
            }
        });
    });

   
   
    document.addEventListener('DOMContentLoaded', function() {
        // Define la URL del endpoint
       // const url = '{{ path('app_solicitar_turno_ajax') }}';
    
        // Realiza la solicitud AJAX al endpoint
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Procesa los datos recibidos
                const turnosOcupados = data;
                console.log(turnosOcupados);
                console.log("Fechas ocupadas:", turnosOcupados);
    
                // Inicializa el calendario con las fechas ocupadas
                const calendarEl = document.getElementById('calendar');
                console.log(calendarEl);
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    locale: 'es',
                    select: function(info) {
                        const fechaSeleccionada = info.start;
                        const fechaActual = new Date();
    
                        // Verifica si la fecha seleccionada es menor que la fecha actual
                        if (fechaSeleccionada < fechaActual) {
                            alert('No puede seleccionar una fecha anterior al día de hoy!');
                            return;
                        }
    
                        const year = fechaSeleccionada.getFullYear();
                        const month = String(fechaSeleccionada.getMonth() + 1).padStart(2, '0');
                        const day = String(fechaSeleccionada.getDate()).padStart(2, '0');
    
                        const fechaFormateada = `${year}-${month}-${day}`;
    
                        if (turnosOcupados.includes(fechaFormateada)) {
                            return;
                        }
    
                        document.getElementById('solicitar_turno_fecha').value = fechaFormateada;
                    },
                    events: turnosOcupados.map(fecha => ({
                        title: 'Asignado',
                        start: fecha,
                        color: 'violet'
                    }))
                });
                calendar.render();
            })
            .catch(error => {
                console.error('Error al obtener las fechas ocupadas:', error);
            });
    });