<?php $this->load->view('commons/cabecalho'); ?>

<div class="container">
	<div class="page-header">
		<h1 class="text-center">Validação de email com a API MailBoxLayer</h1>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<p class="lead">Informe seu email para executar o processo de validação.</strong>.</p>
			<form method="POST" action="<?=base_url()?>">
				<div class="form-group">
					<input type="email" name="email" class="form-control" required />
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-success" value="Validar" />
				</div>
			</form>
      <?php if(isset($validEmail)): ?>
          <div class="alert alert-info">
            <h2 class="text-center">Mensagens da validação</h2>
            <hr />
            <ul>
      <?php
          foreach($validEmail as $item):
            echo "<li>$item</li>";
          endforeach;
      ?>
            </ul>
          </div>
      <?php endif; ?>
		</div>
	</div>
</div>

<?php $this->load->view('commons/rodape'); ?>
