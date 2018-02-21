<template>
	<div class="container">
		<div class="row justify-content-center">
			<div class="mt-4" v-bind:class="{'col-8': isTable, 'col-6': isCard}">
				<div class="card">
                    <div class="card-body">
						<template v-if="status == 'ok' && report.length == 0">
							<h2 class="card-title">Questions</h2>
							<form method="POST" action="#" @submit.prevent="sendAnswer">
								<div class="list-group">
									<div v-for="(_question, n) in questionsList" class="list-group-item list-group-item-action flex-column align-items-start">
										<div class="d-flex w-100 justify-content-between">
											<h5 class="mb-1">{{_question.title}}</h5>
										</div>
										<p class="mb-1">
											<div v-for="answer in _question.answers" class="form-check">
												<input class="form-check-input" type="radio" v-model="selectedList[n]" v-bind:value="answer">
												<label class="form-check-label">{{answer.title}}</label>
											</div>
										</p>
									</div>
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
			questionsList: {},
			selectedList: [{}],
			questionTitle: '',
			answers: [],
			message: '',
			report: []
		};
	},
	mounted() {
		let component = this;
		component.reports = [];
		component.isCard = true;
		component.isTable = false;
		/**
		 * request for questions list
		 */
		axios.get('/api/question').then((resp) => {
            if (resp.data.meta.status === 'ok') {
            	component.questionsList = resp.data.data.questions;
                component.$store.dispatch(Types.FETCH_QUESTION, resp.data.data.questions);
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
    		let answers = [];
    		$.each(component.selectedList, function(k, item) {
    			let d = {
	    			question_id: item.question_id,
	    			answer_id: item.id
	    		};
    			answers.push(d);
    		});
    		axios.post('/api/question', answers).then((resp) => {
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