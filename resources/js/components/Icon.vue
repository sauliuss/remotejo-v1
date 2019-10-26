<template>
    <svg :class="setIconClass">
      <use :xlink:href="setIconId"/>
    </svg>
</template>

<script>
export default {
  name: 'Icon',
  data () {
    return {
    }
  },
  computed: {
    setIconId() {
      return `/img/svg_symbols.svg#icon-${this.name}`;
    },
    setIconClass() {
      return `icon icon-${this.name}`;
    }
  },
  props: {
    name: String
  },
  methods: {
  }
}
</script>
