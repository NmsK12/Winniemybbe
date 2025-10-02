// public/js/app.js
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('apply-form');
  if (!form) return;
  form.addEventListener('submit', function (e) {
    // basic validation
    const email = form.querySelector('input[name="email"]').value.trim();
    const name = form.querySelector('input[name="name"]').value.trim();
    const position = form.querySelector('select[name="position"]').value;
    if (!name || !email || !position) {
      e.preventDefault();
      Swal.fire({ icon: 'warning', title: 'Faltan datos', text: 'Completa nombre, correo y puesto.' });
      return;
    }
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerText = 'Enviando...';
  });
});
