// Tab switching en dashboard
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remover clase active de todos los botones y secciones
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));

        // Añadir clase active al botón clickeado y su sección correspondiente
        this.classList.add('active');
        document.getElementById(this.dataset.tab).classList.add('active');
    });
});

// Validaciones adicionales en formularios
function validarFormulario(form) {
    const campos = form.querySelectorAll('[required]');
    let valido = true;

    campos.forEach(campo => {
        if (!campo.value.trim()) {
            campo.style.borderColor = '#f44336';
            valido = false;
        } else {
            campo.style.borderColor = '#ddd';
        }
    });

    return valido;
}

// Confirmación antes de eliminar
function confirmarEliminacion(url) {
    if (confirm('¿Estás seguro que deseas eliminar este registro?')) {
        window.location.href = url;
    }
    return false;
}

// Formato de fecha en input
document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
    if (input.value) {
        input.addEventListener('change', function() {
            // Validar que la fecha no sea en el pasado
            const fecha = new Date(this.value);
            const ahora = new Date();
            if (fecha < ahora) {
                alert('No puedes agendar citas en el pasado');
                this.value = '';
            }
        });
    }
});

// Formatear precios
document.querySelectorAll('input[type="number"][name="precio"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-btn');
    const sections = document.querySelectorAll('.section');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-tab');
            
            // Quitar active de todos los botones
            tabs.forEach(t => t.classList.remove('active'));
            
            // Quitar active de todas las secciones
            sections.forEach(s => s.classList.remove('active'));
            
            // Agregar active al botón clickeado
            this.classList.add('active');
            
            // Agregar active a la sección correspondiente
            document.getElementById(target).classList.add('active');
        });
    });
});
