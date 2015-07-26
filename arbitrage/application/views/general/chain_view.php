<link rel="stylesheet" href="/static/map/map.css">

<ul class="timeline">
<?php
$names = array();
$buy = array();
$sell = array();
$income = array();
$total = 0;
foreach ($chains as $key=>$chain){
		  $names[$key] = $markets[$key]->name;
		  $buy[$key] = array();
		  //$sell[$key] = array();
		  foreach ($chain as $operation)
		  {
				    array_push($buy[$key], $operation);
				    $next = $operation['id'];
				    if(!isset($sell[$next])) $sell[$next] = array();
				    array_push($sell[$next], $operation);
				    if(!isset($income[$next])) $income[$next] = 0;
				    $income[$next] += intval($operation['sell']) - intval($operation['buy']);
				    //echo var_dump($income[$next]);
				    $total += intval($operation['sell']) - intval($operation['buy']);
		  }
}


//echo var_dump($income);



foreach ($chains as $key=>$chain): ?>
		  <li >
		    <div class="timeline-badge"><i class="fa fa-map-marker"></i></div>
		    <div class="timeline-panel">
		      <div class="timeline-heading">
			<h4 class="timeline-title"><?=$names[$key]?>     <span style="color:green" class="pull-right"><?php if(isset($income[$key])) :?>+<?=$income[$key]?><?php endif;?></span></h4>
		  </div>
		      <div class="timeline-body">
		        <?php if(isset($sell[$key])) foreach ($sell[$key] as $el) :?>
			   <h5><?=$el['item']?></h5>
			   <p>Продати <?=$el['sell']?></p>
			<?php endforeach;?>
			
			<?php if(isset($buy[$key])) foreach ($buy[$key] as $el) :?>
			   <h5><?=$el['item']?></h5>
			   <p>Придбати <?=$el['buy']?></p>
			<?php endforeach;?>
		      </div>
		    </div>
		  </li>
<?php endforeach; ?>


</ul>

<h4>Загальний прибуток: <?=$total?></h4>