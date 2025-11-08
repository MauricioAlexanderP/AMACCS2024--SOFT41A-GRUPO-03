<?php

/**
 * Configuración de correo electrónico
 * 
 * IMPORTANTE: Para usar Gmail, necesitas crear una "Contraseña de aplicación"
 * 
 * Pasos para configurar Gmail:
 * 1. Ve a tu cuenta de Google: https://myaccount.google.com/
 * 2. Seguridad > Verificación en dos pasos (actívala si no está activa)
 * 3. Seguridad > Contraseñas de aplicaciones
 * 4. Genera una nueva contraseña para "Correo"
 * 5. Usa esa contraseña de 16 caracteres en SMTP_PASSWORD
 * 
 * Alternativas a Gmail:
 * - Mailtrap (para testing): smtp.mailtrap.io
 * - SendGrid: smtp.sendgrid.net
 * - SMTP propio del servidor
 */

return [
  // Modo de envío de correo:
  // 'smtp' = Envío desde servidor con PHPMailer (AUTOMÁTICO)
  // 'mailto' = Abrir cliente de correo del usuario
  // 'test' = Simular envío (solo logs)
  'EMAIL_MODE' => 'smtp',

  // Configuración SMTP (CONFIGURAR ESTAS CREDENCIALES)
  'SMTP_HOST' => 'smtp.gmail.com',
  'SMTP_PORT' => 587,
  'SMTP_USERNAME' => 'peremauricio99@gmail.com', // ⬅️ CAMBIAR: Tu correo de Gmail
  'SMTP_PASSWORD' => 'wlmc tssu wlhk hyse', // ⬅️ CAMBIAR: Contraseña de aplicación de 16 caracteres
  'SMTP_SECURE' => 'tls', // tls o ssl

  // Remitente
  'FROM_EMAIL' => 'noreply@itca.edu.sv',
  'FROM_NAME' => 'Sistema de Consultas ITCA',

  // Correo de prueba (docente)
  'TEST_EMAIL' => 'mauricio.perez24@itca.edu.sv',

  // Configuración adicional
  'CHARSET' => 'UTF-8',
  'DEBUG_MODE' => 0, // 0 = off, 1 = client, 2 = server, 3 = connection, 4 = lowlevel
];
