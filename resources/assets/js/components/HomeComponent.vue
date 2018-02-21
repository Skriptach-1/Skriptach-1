<template>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-6 mt-4">
				<div class="card">
                    <div class="card-body">
						<template v-if="1">
							<h2 class="card-title">Question</h2>
							<form method="POST" action="#" @submit.prevent="sendAnswer">
								<p class="lead">{{questionTitle}}</p>
								<div v-for="answer in answers" class="form-check">
									<input class="form-check-input" type="radio" v-model="selectedAnswer" v-bind:value="answer" name="answer">
									<label class="form-check-label">{{answer.title}}</label>
								</div>
								<button class="btn btn-default" type="submit" id="answerBtn" data-loading-text="Saving...">Answer</button>
							</form>
						</template>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import axios from 'axios';
import {store} from '../store';
import * as Types from "../store/auth/Types";

export default {
	data() {
		return {
			questionTitle: '',
			answers: [],
			selectedAnswer: {
				id: '',
				question_id: ''
			}
		};
	},
	mounted() {
		let component = this;
		axios.get('/api/question').then((resp) => {
            if (resp.data.meta.status === 'ok') {
            	component.questionTitle = resp.data.data.question.title + '?';
            	component.answers = resp.data.data.question.answers;
                component.$store.dispatch(Types.FETCH_QUESTION, resp.data.data.question);
            } else {
                commit(Types.LOGOUT);
            }
        }, (err) => {
        	console.log(err);
        });
    },
    methods: {
    	sendAnswer: function() {
    		let component = this;
    		console.log(component.selectedAnswer);
    	}
    }
}
</script>