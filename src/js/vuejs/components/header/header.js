var demo = new Vue({
	
	el: '#main',

	data: {
		active: 'titresite'
	},

	methods: {
		makeActive: function(item){
			this.active = item;
		}
	}
});