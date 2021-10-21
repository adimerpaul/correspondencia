<template>
<q-page class="q-pa-xs">
  <div class="row">
    <div class="col-12">
      <q-form @submit.prevent="buscar">
        <div class="row">
          <div class="col-12 col-md-6 q-pa-xs">
            <q-input label="Ingresar codigo" v-model="codigo" outlined required/>
          </div>
          <div class="col-12 col-md-6 q-pa-xs flex flex-center">
            <q-btn label="Buscar" color="primary" icon="send" type="submit" />
          </div>
        </div>
      </q-form>
    </div>
    <div class="col-6">
      <pre>{{email}}</pre>
    </div>
    <div class="col-6">
      <pre>{{email.logs}}</pre>
    </div>
  </div>
</q-page>
</template>

<script>
export default {
  data(){
    return{
      codigo:'',
      email:{}
    }
  },
  methods:{
    buscar(){
      this.$q.loading.show()
      this.email={}
      this.$axios.post(process.env.API+'/buscar',{codigo:this.codigo}).then(res=>{
        console.log(res.data)
        this.email=res.data[0]
        this.$q.loading.hide()
      })
    }
  }
}
</script>

<style scoped>

</style>
