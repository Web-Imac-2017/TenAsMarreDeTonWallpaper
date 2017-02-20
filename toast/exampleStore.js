
class QuestionsStore {

	constructor () {
		this.state = {
			count: 0
		}
	}

	increment () {
		this.state.count++
	}

	decrement () {
		this.state.count--
	}
}

let question_store = new QuestionsStore()

let Counter = {
	data: function () {
		return {
			state: question_store.state
		}
	},
	computed: {
		count: function () {
			return this.state.count
		}
	},
	methods: {
		increment: function(){
			question_store.increment()
		}
	},
	template: `<button @click="increment"> {{ count }} </button>`
}

let question = {
	components: { Counter },
	methods: {
		addQuestions: function() {
			question_store.increment()
		}
	},
	template: `<div>
		<counter> </counter>
		<button @click="addQuestions"> Ajouter Question </button>
	</div>`
}

let vue = new Vue ({
	el: '#app',
	components: { question, Counter },
	data: {
		message: 'Test Component'
	},
	methods: {

	}

})