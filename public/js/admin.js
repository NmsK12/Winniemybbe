// public/js/admin.js
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const id = this.getAttribute('data-id');
      Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción eliminará el postulante y su CV. ¿Continuar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '/admin.php?action=delete&id=' + encodeURIComponent(id);
        }
      });
    });
  });
});
