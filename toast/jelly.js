Vue.component('draggable-header-view', {
  template: '#header-view-template',
  data: function () {
    return {
      dragging: false,
      // quadratic bezier control point
      c: { x: 260, y: 260 },
      m: { x: 100, y: 100 },
      l: { x: 420, y: 100 },
      // record drag start point
      start: { x: 100, y: 100 }
    }
  },
  computed: {
    headerPath: function () {
      return 'M'+ this.m.x +','+ this.m.y +' L'+ this.l.x +','+ this.l.y +' 420,260' +
        'Q' + this.c.x + ',' + this.c.y +
        ' 100,260'
    },
  },
  methods: {
    startDrag: function (e) {
      e = e.changedTouches ? e.changedTouches[0] : e
      this.dragging = true
      this.start.x = e.pageX
      this.start.y = e.pageY
    },
    onDrag: function (e) {
      e = e.changedTouches ? e.changedTouches[0] : e
      if (this.dragging) {
        this.c.x = 260 + (e.pageX - this.start.x)
        // dampen vertical drag by a factor
        var dy = e.pageY - this.start.y
        var dampen = dy > 0 ? 1.5 : 4
        this.c.y = 260 + dy / dampen
        
        this.m.x = 100 + (e.pageX - this.start.x)
        // dampen vertical drag by a factor
        var dy = e.pageY - this.start.y
        var dampen = dy > 0 ? 1.5 : 4
        this.m.y = 100 + dy / dampen
        
        this.l.x = 420 + (e.pageX - this.start.x)
        // dampen vertical drag by a factor
        var dy = e.pageY - this.start.y
        var dampen = dy > 0 ? 1.5 : 4
        this.l.y = 100 + dy / dampen
      }
    },
    stopDrag: function () {
      if (this.dragging) {
        this.dragging = false
        dynamics.animate(this.c, {
          x: 260,
          y: 260
        }, {
          type: dynamics.spring,
          duration: 1000,
          friction: 200
        })
        
        dynamics.animate(this.m, {
          x: 100,
          y: 100
        }, {
          type: dynamics.spring,
          duration: 1000,
          friction: 200
        })
        
        dynamics.animate(this.l, {
          x: 420,
          y: 100
        }, {
          type: dynamics.spring,
          duration: 1000,
          friction: 200
        })
      }
    }
  }
})

new Vue({ el: '#app' })