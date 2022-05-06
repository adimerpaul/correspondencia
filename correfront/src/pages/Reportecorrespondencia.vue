<template>
<q-page class="q-pa-xs">

</q-page>
</template>
<script>
export default {
  name: `Reportecorrespondencia`,
  created() {
    this.$axios.get(process.env.API+'/reportecorrespondencia/1').then(res=>{
      console.log(res.data)
    })
  }
}
</script>

<style scoped>

</style>
