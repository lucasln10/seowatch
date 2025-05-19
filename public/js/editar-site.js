document.addEventListener('DOMContentLoaded', function () {
  const editarModal = document.getElementById('editarModal');
  const editarForm = document.getElementById('editarForm');

  editarModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // botão que abriu o modal
    const id = button.getAttribute('data-id');
    const title = button.getAttribute('data-title');
    const url = button.getAttribute('data-url');

    // Ajusta o action do form para a rota update do site correto
    editarForm.action = '/site/editar/' + id;

    // Preenche os campos
    document.getElementById('siteId').value = id;
    document.getElementById('nome').value = title;
    document.getElementById('url').value = url;
  });
});