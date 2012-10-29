<?php

class Vote extends ValidatingBasicObject {
	protected static function table_name(){
		return 'vote';
	}

	protected function validation_hooks() {

		$this->validate_in_range('score', array('minimum' => 1, 'maximum' => 5, 'message' => "Poängen måste vara mellan 1 och 5"));

		//Validate that the user have not a vote with the same score in this category:
		if(static::count(array(
			'score' => $this->score,
			'category_id' => $this->category_id,
			'user_id' => $this->user_id,
			'vote_id:!=' => $this->vote_id
		)) > 0) {
			$this->add_error('score', "Du kan inte ge samma poäng till två bidrag i samma kategori");
		}

		//Validate unique entry vote for user
		if(static::count(array(
			'entry_id' => $this->entry_id,
			'user_id' => $this->user_id,
			'vote_id:!=' => $this->vote_id
		)) > 0) {
			$this->add_error('score', "Du kan inte ge ett bidrag flera olika poäng");
		}
	}

	public static function find_or_new($category, $user, $score) {
		$data = array('category_id' => $category->category_id, 'user_id' => $user->user_id, 'score' => $score);
		$v = Vote::one($data);
		if($v) return $v;
		$data['entry_id'] = null;
		$v = new Vote($data);
		return $v;
	}

}
