<template>
	<div class="container">
		<div class="row justify-content-center">
			<div class="mt-4" v-bind:class="{'col-8': isTable, 'col-6': isCard}">
				<div class="card">
                    <div class="card-body">
						<template v-if="status == 'ok' && report.length == 0">
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
						<template v-else-if="status == 'ok' && report.length > 0">
							<h2 class="card-title">Questions report (latest {{report.length}} records)</h2>
							<table class="table">
								<tr>
									<th scope="col">Question</th>
									<th scope="col">Answer</th>
									<th scope="col">Date</th>
								</tr>
								<tr v-for="item in report">
									<td>{{item.question}}?</td>
									<td>{{item.answer}}</td>
									<td>{{item.created_at}}</td>
								</tr>
							</table>
						</template>
						<template v-else>
							<h2 class="card-title">{{message}}</h2>
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
			isTable: false,
			isCard: true,
			status: '',
			questionTitle: '',
			answers: [],
			message: '',
			report: [],
			selectedAnswer: {
				id: '',
				question_id: ''
			}
		};
	},
	mounted() {
		let component = this;
		component.reports = [];
		component.isCard = true;
		component.isTable = false;
		axios.get('/api/question').then((resp) => {
            if (resp.data.meta.status === 'ok') {
            	component.questionTitle = resp.data.data.question.title + '?';
            	component.answers = resp.data.data.question.answers;
                component.$store.dispatch(Types.FETCH_QUESTION, resp.data.data.question);
            } else {
                component.message = resp.data.meta.message;
            }
            component.status = resp.data.meta.status;
        }, (err) => {
        	console.log(err);
        });
    },
    methods: {
    	sendAnswer: function() {
    		let component = this;
    		let user = this.$store.getters.authUser;
    		let data = {
    			question_id: component.selectedAnswer.question_id,
    			answer_id: component.selectedAnswer.id
    		};
    		axios.post('/api/question', data).then((resp) => {
    			if (resp.data.meta.status === 'ok') {
    				component.report = resp.data.data.report;
    			} else {
    				component.message = resp.data.meta.message;
    			}
    			component.status = resp.data.meta.status;
    			component.isCard = (component.report.length == 0);
    			component.isTable = (component.report.length > 0);
    		}, (err) => {
	        	console.log(err);
	        });
    	}
    }
}
</script>