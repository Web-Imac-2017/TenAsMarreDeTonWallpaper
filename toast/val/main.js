Vue.component('test-component', {
    template: '\
        <h1 v-on:click="increment" class="counter" :class="{active: active}">{{count}}</h1>\
    ',
    props: {
        'start': {type: Number, default: 0}
    },
    data: function() { return {
        count: this.start,
        active: false
    }},
    methods:{
        increment: function(){
            this.count++;
            this.active = false;
            setInterval(() => this.active = true, 10);
        }
    }


});

new Vue({
    el: '#app',
    data: {}
});