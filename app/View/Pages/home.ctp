<?php

/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')) :
	throw new NotFoundException();
endif;

App::uses('Debugger', 'Utility');
?>
<?php $this->assign('title', 'Home Page'); ?>
<div class="container">
	<div class="row align-items-center" style="min-height: 25rem">
		<div class="col"></div>
		<div class="col-5">
			<?php echo $this->Html->link('Add Survey', array('controller' => 'Surveys', 'action' => 'msf_setup'), array('class' => 'btn btn-secondary btn-lg btn-block')) ?>
			<?php echo $this->Html->link('Take Survey', array('controller' => 'Surveys', 'action' => 'index'), array('class' => 'btn btn-primary btn-lg btn-block')) ?>
		</div>
		<div class="col"></div>
	</div>
</div>