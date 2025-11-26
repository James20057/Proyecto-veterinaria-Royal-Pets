<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinaria Royal Pets - Siempre cerca de tu mascota</title>
    <!-- Usando solo un archivo CSS externo -->
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h1>🐾 Veterinaria Royal Pets</h1>
            </div>
            <div class="inicio-sesion">
                <a href="login.php">Acceso administración</a>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </nav>
    </header>
    <section class="welcome-section" id="inicio">
        <div class="hero-img"></div>
        <div class="welcome-text">
            <h2>Dedicados al bienestar de tus mascotas</h2>
            <p>Citas rápidas, atención de expertos y cariño a tu compañero en cada consulta.<br>
            <b>Llámanos o agenda ya por WhatsApp.</b></p>
        </div>
    </section>
    <section class="services-section" id="servicios">
        <h2>Servicios más Populares</h2>
        <div class="services-grid">
            <div class="service-card">
                <span class="service-icon">🩺</span>
                <h3>Consultas generales</h3>
                <p>Revisión veterinaria, chequeo y orientación.</p>
                <span class="service-price">$ 50.000</span>
            </div>
            <div class="service-card">
                <span class="service-icon">✂️</span>
                <h3>Baños & Cortes</h3>
                <p>Estética profesional y baño.</p>
                <span class="service-price">$ 25.000</span>
            </div>
            <div class="service-card">
                <span class="service-icon">💉</span>
                <h3>Vacunación</h3>
                <p>Todos los esquemas y asesoramiento.</p>
                <span class="service-price">$ 35.000</span>
            </div>
            <div class="service-card">
                <span class="service-icon">🦠</span>
                <h3>Desparasitación</h3>
                <p>Interna y externa para todos los peludos.</p>
                <span class="service-price">$ 20.000</span>
            </div>
            <div class="service-card">
                <span class="service-icon">🏥</span>
                <h3>Cirugías</h3>
                <p>Cirugía menor y procedimientos seguros.</p>
                <span class="service-price">$ 150.000</span>
            </div>
        </div>
        <div class="service-links">
            <a href="#servicios">Ver todos los servicios</a>
            <a href="https://wa.me/573101234567?text=Hola%20quiero%20agendar%20una%20cita%20en%20Royal%20Pets" target="_blank">Agendar cita por WhatsApp</a>
        </div>
    </section>
    <section class="contact-section" id="contacto">
        <h2>Horarios y Contacto</h2>
        <div class="contact-info">
            <p><strong>Horario:</strong> Lunes a Sábado: 8:00 AM - 7:00 PM</p>
            <p><strong>Dirección:</strong> Calle 123 #45-67, Bogotá, Colombia</p>
            <p><strong>WhatsApp:</strong> +57 310 123 4567</p>
            <p><strong>Email:</strong> contacto@royalpets.com</p>
            <p><strong>Teléfono:</strong> (601) 234 5678</p>
        </div>
    </section>
    <footer>
        <div class="footer-content">
            <div class="social-media">
                <a href="https://facebook.com/" target="_blank">Facebook</a>
                <a href="https://instagram.com/" target="_blank">Instagram</a>
                <a href="https://tiktok.com/" target="_blank">TikTok</a>
            </div>
            <p class="copyright">Todos los derechos reservados © 2025 Veterinaria Royal Pets</p>
        </div>
    </footer>
</body>
</html>