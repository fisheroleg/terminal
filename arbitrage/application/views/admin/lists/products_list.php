      <?php if(!$async):?>
      <div class="col-sm-8 admin-panel">
      	
      	<h3><i class="fa fa-users"></i> <span id="itemType">Продукти</span></h3>
	    <hr>
		  
	    <div class="panel-body">
			
		  <form class="form-inline form-add" id="formAdd" style="font-family: 'FontAwesome', 'Helvetica Neue', Helvetica, Arial, sans-serif">
			  <div class="form-group">
			    <label class="sr-only" for="exampleInputAmount"></label>
			    <div class="input-group">
			      <div class="input-group-addon"></div>
			      <input type="text" class="form-control" name="nameProduct" id="" placeholder="Назва">
			      <div class="input-group-addon"></div>
			      <input type="text" class="form-control" name="categoryProduct" id="" placeholder="Категорія">
			      <div class="input-group-addon"></div>
			      <button type="submit" class="btn btn-success form-control" id="addItem"><span>&#xf055;</span></button>
			    </div>
			  </div>
			  
			</form>
			<br>
			<div class="alert alert-danger alert-dismissible fade in" id="errorMessage" role="alert" style="display: none;">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
			  <strong>Помилка!</strong>
			</div>
			<hr>

			<ul class="pagination">
			  <?php for($i=0;$i<intval($num[0]->num/10)+1;$i++):?>
			  <li><a href="?start=<?=$i*10?>&end=<?=($i+1)*10?>"><?=$i+1?></a></li>
			  <?php endfor; ?>
			</ul>

			<table class="table table-striped">
			<thead>
			  <tr><th>ID</th><th>Назва</th><th>Категорія</th><th style="width: 30px;"></th><th style="width: 30px;"></th></tr>
			</thead>
			<tbody id="listpoll">
			  <?php endif;?>
				<?php $c=1;foreach ($products as $item):?>
		      		<?php if($async) $added = "newItem"; else $added = ""?>
					<tr class="item link <?=$added?>">
						<td class="id-article itemId"><?=$item->idProduct?></td>
						<td class="author-article"><?=$item->nameProduct?></td>
						<td class="itemName"><?=$item->categoryProduct?></td>
						<td class="edit-icon" element-id="<?=$item->idProduct?>" data-toggle="modal" data-target="#confirm-edit"><i class="fa fa-edit text-muted"></i></td>
						<td class="remove-icon" element-id="<?=$item->idProduct?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-remove text-muted"></i></td>
					</tr>
				<?php $c++;endforeach;?>
				<?php if(!$async):?>
			</tbody>
		  </table>
	    
	  
	    </div><!--/panel-body-->
	    </div><!--/panel-->  
      </div><!--/col-->
      
      
      <script>
	jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[1234567890абвгдеёі'їІЇґҐжзийклмнопрстуфхцчшщьыъэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮ@.ЯqwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM-]*$/.test(value);
	}, "Letters only please");
	
	$('#formAdd').validate({
	validClass: 'has-success',
        rules: {
            nameProduct: {
                minlength: 2,
                required: true,
		lettersonly: true
            },
	    categoryProduct: {
                minlength: 2,
                required: true,
		lettersonly: true
            }
        },
        highlight: function (element) {
	  console.log($(element))
            $(element).removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
	  console.log($(element).closest('.form-control'))
            $(element).closest('input').removeClass('has-error').addClass('has-success');
        },
errorPlacement: function(error,element) {
    return true;
  }
    });
	
	var CATEGORY = "Products"
	
      </script>
      
      <?php endif; ?>