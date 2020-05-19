<div class="box box-solid">
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th>Imię</th>
					<th>Nazwisko</th>
					<th>Email</th>
					<th>PESEL</th>
					<th>Typ dokumentu</th>
					<th class="bool-column">Zagraniczny</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$goscie = View::pobierz('goscie');
					if(!empty($goscie)): 
				?>
					<?php foreach($goscie as $a): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $a['gh_id']; ?></th> 
						<td><?= $a['uzy_imie']; ?></td>
						<td><?= $a['uzy_nazwisko']; ?></td>
						<td><?= $a['kuz_email']; ?></td>
						<td><?= $a['gh_pesel']; ?></td>
						<td><?= $a['gh_typ_dokumentu_tozsamosci']; ?>
						<td class="bool-column"><?= $a['gh_zagraniczny']; ?></td>
						<td class="action-column">
							<?php if(!empty($a['gh_id'])): ?>
								<a href="<?= Router::utworz_link(array('controller' => 'GoscieHotelowi', 'action' => 'edytuj', 'id' => $a['gh_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'GoscieHotelowi', 'action' => 'usun', 'id' => $a['gh_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
							<?php else: ?>
								<a href="<?= Router::utworz_link(array('controller' => 'GoscieHotelowi', 'action' => 'dodaj', 'user_id' => $a['uzy_id'])); ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></a>
							<?php endif; ?>
							
						</td>
					</tr>
					<?php endforeach; ?>
				<?php 
					endif;
				?>
			</tbody>
		</table>
		</div><!-- /.table-responsive -->
	</div>
</div>