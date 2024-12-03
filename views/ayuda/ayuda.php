<h2 class="text-center text-primary">MANUAL DE USUARIO</h2>

<?php if ($_SESSION['CECOM_ADMINISTR']) : ?>
    <iframe src="<?= asset('pdf/administrador.pdf') ?>" width="100%" height="600px" frameborder="0"></iframe>
<?php elseif ($_SESSION['CECOM_USUARIO']) : ?>
    <iframe src="<?= asset('pdf/usuario.pdf') ?>" width="100%" height="600px" frameborder="0"></iframe>
<?php endif ?>

<script src="<?= asset('build/js/ayuda/ayuda.js') ?>"></script>
