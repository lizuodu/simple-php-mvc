<?php
class TagController extends Controller {
	public function actionIndex() {
		$model = $this->loadModel ( 'Tag' );
		$tag = $model->getAllTags ();
		$listCount = count ( $tag );
		$tag = json_encode ( $tag );
		$this->zprint ( "{'total': {$listCount}, 'list': {$tag}}", '' );
	}
}





