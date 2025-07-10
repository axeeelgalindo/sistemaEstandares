<?php
// views/modulos/modales/modales-usuarioajustes.php
// Asume que $value y $plantas vienen de usuarioajustes.php

if (!isset($value)) {
  echo "<p style='color:red'></p>";
  return;
}
?>
<div class="modal fade" id="modal-editar-usuariop" tabindex="-1" role="dialog" aria-labelledby="editarPerfilLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="editarPerfil" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="editarPerfilLabel">Editar Perfil</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Nombre -->
          <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control"
              value="<?= htmlspecialchars($value['nombre'], ENT_QUOTES) ?>" required>
          </div>

          <!-- Email -->
          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control"
              value="<?= htmlspecialchars($value['email'], ENT_QUOTES) ?>" required>
          </div>

          <!-- Planta -->
          <?php $plantas = ModeloPlanta::listarPlantas(); ?>
          <div class="form-group">
            <label>Planta:</label>
            <select name="planta_id" class="form-control" required disabled>
              <?php foreach ($plantas as $pl): ?>
                <option value="<?= $pl['id'] ?>" <?= $pl['id'] == $value['planta_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($pl['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Contraseña -->
          <div class="form-group">
            <label>Contraseña:</label>
            <div class="password-container">
              <input type="password" name="password" id="password" class="form-control"
                placeholder="Deja esto en blanco para no cambiarla" autocomplete="new-password">
              <i class="toggle-password fas fa-eye" id="togglePassword"></i>
            </div>
          </div>

          <!-- Campos ocultos -->
          <input type="hidden" name="tipoOperacion" value="actualizarPerfil">
          <input type="hidden" name="id_usuario" value="<?= $value['id'] ?>">
          <input type="hidden" name="email_old" value="<?= htmlspecialchars($value['email'], ENT_QUOTES) ?>">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cerrar
          </button>
          <button type="submit" class="btn btn-primary">
            Guardar cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>