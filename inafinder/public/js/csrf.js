// Utilidades CSRF. El token lo inyecta el partial navbar en window.CSRF_TOKEN.

function csrfToken() {
    return window.CSRF_TOKEN || '';
}

// Navega mediante un formulario POST oculto que incluye el token CSRF.
// Sustituye a las antiguas navegaciones GET (window.location.href) para
// acciones que modifican datos: borrados, reembolsos, etc.
function enviarPost(url) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'csrf_token';
    input.value = csrfToken();
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
}

window.csrfToken = csrfToken;
window.enviarPost = enviarPost;
