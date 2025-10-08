<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recoger los datos del formulario
    $nombre = strip_tags(trim($_POST["Nombre"]));
    $email = filter_var(trim($_POST["Email"]), FILTER_SANITIZE_EMAIL);
    $telefono = strip_tags(trim($_POST["Telefono"]));
    $mensaje = strip_tags(trim($_POST["Mensaje"]));

    // 2. Validar que los campos requeridos no estén vacíos
    if (empty($nombre) || empty($mensaje) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Enviar una respuesta de error si algo falla
        http_response_code(400);
        echo "Por favor, completa el formulario y vuelve a intentarlo.";
        exit;
    }

    // 3. Configurar el destinatario del correo
    $destinatario = "davidgrobah@gmail.com"; // <-- Aquí pones tu correo

    // 4. Configurar el asunto del correo
    $asunto = "Nuevo contacto desde el sitio web de CLESA de: $nombre";

    // 5. Construir el cuerpo del mensaje
    $contenido_email = "Nombre: $nombre\n";
    $contenido_email .= "Email: $email\n";
    // El teléfono es opcional, solo lo agregamos si fue llenado
    if (!empty($telefono)) {
        $contenido_email .= "Teléfono: $telefono\n";
    }
    $contenido_email .= "\nMensaje:\n$mensaje\n";

    // 6. Construir las cabeceras del correo
    $headers = "From: $nombre <$email>";

    // 7. Enviar el correo y preparar la respuesta JSON
    header('Content-Type: application/json'); // Indicar que la respuesta es JSON
    if (mail($destinatario, $asunto, $contenido_email, $headers)) {
        // Si se envía bien, devolver éxito
        echo json_encode(['status' => 'success', 'message' => '¡Mensaje enviado con éxito!']);
    } else {
        // Si falla el envío, devolver error
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el mensaje.']);
    }

} else {
    // Si alguien intenta acceder al archivo PHP directamente
    http_response_code(403);
    echo "Hubo un problema con tu envío, por favor intenta desde el formulario.";
}
?>