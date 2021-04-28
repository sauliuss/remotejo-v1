<template>
  <span v-if="this.type == 0" class="tooltip" role="tooltip">
    Only a small part of the team works remotely
  </span>
  <span v-else-if="this.type == 1" class="tooltip" role="tooltip">
    Most of the team works remotely
  </span>
  <span v-else-if="this.type == 2" class="tooltip" role="tooltip">
    100% of the team works remotely
  </span>
</template>

<script>

export default {
  name: 'tooltip',
  data () {
    return {
      data: {
        type: this.type
      },
    }
  },
  computed: {
  },
  mounted() {
  },
  props: {
    type: {
      required: true,
      default: 1
    }
  },
  methods: {

  }
}
</script>
