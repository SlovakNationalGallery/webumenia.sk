import tinycolor from 'tinycolor2'

function _colorChange (data) {
  var color

    // hsl is better than hex between conversions
  if (data && data.hsl) {
    color = tinycolor(data.hsl)
  } else if (data && data.hex && data.hex.length > 0) {
    color = tinycolor(data.hex)
  } else {
    color = tinycolor(data)
  }

  var hsl = color.toHsl()

  if (hsl.s === 0) {
    hsl.h = data.h || (data.hsl && data.hsl.h) || 0
    hsl.s = data.s || (data.hsl && data.hsl.s) || 80
  }

  if (!color._ok) {
    hsl.s = 80
    hsl.l = 50
  }

  if (hsl.l < 0.01) {
    hsl.h = data.h || (data.hsl && data.hsl.h) || 0
    hsl.s = data.s || (data.hsl && data.hsl.s) || 0
  }

  return {
    hsl: hsl,
    hex: color.toHexString().toUpperCase()
  }
}

export default {
  props: ['value'],
  data () {
    return {
      val: _colorChange(this.value)
    }
  },
  computed: {
    colors: {
      get () {
        return this.val
      },
      set (newVal) {
        this.val = newVal
        this.$emit('input', newVal)
      }
    }
  },
  watch: {
    value (newVal) {
      this.val = _colorChange(newVal)
    }
  },
  methods: {
    hueChange (h) {
      const data = { hsl: { ...this.val.hsl, h } }
      this.colors = _colorChange(data)
    },
    lightnessChange (l) {
      const data = { hsl: { ...this.val.hsl, l } }
      this.colors = _colorChange(data)
    }
  }
}
