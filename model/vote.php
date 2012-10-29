<?php

class Vote extends ValidatingBasicObject {
	protected static function table_name(){
		return 'vote';
	}

	protected function validation_hooks() {
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
			$this->add_error('score', "Du kan inte ge ett bidrag flera placeringar");
		}
	}

}
