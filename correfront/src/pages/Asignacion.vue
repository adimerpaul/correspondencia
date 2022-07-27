<template>
  <q-page class="q-pa-xs">
    <div class="row">
      <div class="col-12">
<!--        <div class="col-12">-->
<!--          <iframe id="docpdf" src="" frameborder="0" style="width: 100%;height: 100vh"></iframe>-->
<!--        </div>-->
        <q-btn label="Crear" icon="mail" color="primary" @click="fromcrear" />
        <q-btn label="Actualizar" icon="refresh" color="teal" @click="actualizar" />
        <q-dialog full-width v-model="crear">
          <q-card>
            <q-card-section>
              <div class="text-h6" v-if="dato.id==undefined">Crear Hoja de ruta</div>
              <div class="text-h6" v-else>Modificar Hoja de ruta</div>
<!--              <pre>{{dato}}</pre>-->
            </q-card-section>
            <q-card-section class="q-pt-none">
              <q-form @submit.prevent="guardar">
                <div class="row" style="border: 1px solid rgba(128,128,128,0.50)">
                  <div class="col-6 flex flex-center"><q-radio dense required v-model="dato.tipo" val="EXTERNO" label="EXTERNO"/></div>
                  <div class="col-6 flex flex-center"><q-radio dense required v-model="dato.tipo" val="INTERNO" label="INTERNO"/></div>
                  <div class="col-sm-2 col-12 q-pa-xs"><q-input required dense label="Cite/Codigo/Tramite" autofocus v-model="dato.citecontrol"  outlined /></div>
                  <div class="col-sm-2 col-12 q-pa-xs"><q-input required dense label="Cite entrante" autofocus v-model="dato.cite"  outlined /></div>
                  <div class="col-sm-4 col-12 q-pa-xs">
                    <!-- <q-input style="text-transform: uppercase" outlined dense label="Destinatario" v-model="destinatario"/> -->
                     <q-input style="text-transform: uppercase" outlined dense label="Destinatario" list="destinatario" name="midestinatario" v-model="destinatario" />
                    <datalist id="destinatario">
                      <option v-for="r in destinatarios" :key="r.id">{{r.destinatario}}</option>
                    </datalist>
                  </div>
                  <div class="col-sm-4 col-12 q-pa-xs">
                    <q-input  @keyup="cambio" style="text-transform: uppercase" outlined dense label="remitente" list="browsers" name="myBrowser" v-model="remitente" />
                    <datalist id="browsers">
                      <option v-for="r in remitentes" :key="r.id">{{r.remitente}}</option>
                    </datalist>
                  </div>

                  <div class="col-sm-2 col-12 q-pa-xs"><q-input style="text-transform: uppercase" dense label="Cargo" v-model="cargo" outlined/></div>
                  <div class="col-sm-2 col-12 q-pa-xs"><q-input style="text-transform: uppercase" dense label="Institucion" v-model="institucion" outlined/></div>
                  <div class="col-sm-8 col-12 q-pa-xs"><q-input style="text-transform: uppercase" dense  label="Referencia" v-model="dato.ref" outlined/></div>
                  <div class="col-sm-2 col-12 q-pa-xs"><q-input dense label="Fecha de correspondencia" v-model="dato.fecha" type="date" outlined/></div>
                  <div class="col-sm-2 col-12 q-pa-xs"><q-input dense label="Fojas" v-model="dato.folio"  outlined /></div>
<!--                  <div class="col-sm-2 col-12 q-pa-xs"><q-input dense label="Cod externo" v-model="dato.codexterno" outlined /></div>-->
                  <div class="col-sm-2 col-12 q-pa-xs flex flex-center">
                    <q-btn type="submit" color="primary" icon="add_circle" label="Registrar" v-if="dato.id==undefined || dato.id==''"/>
                    <q-btn type="submit" color="amber" icon="edit" label="Modificar" v-else />
                  </div>
                </div>
              </q-form>

            </q-card-section>

            <q-card-actions align="right">
              <q-btn flat label="Cerrar" icon="delete" color="primary" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>

      </div>
      <div class="col-12">
        <q-table
          dense
          title="Correspondencia "
          :rows="mails"
          :columns="columns"
          :filter="filter"
          v-model:pagination="pagination"
          :loading="loading"
          :rows-per-page-options="[10,100,150,200,0]"
           row-key="name"
           @request="handleRequest"
        >
          <template v-slot:top-right>
            <q-input outlined bottom-slots dense debounce="300" v-model="filter" @keyup.enter="buscar" placeholder="Buscar">
              <template v-slot:append>
                <q-icon v-if="filter !== ''" name="close" @click="actualizar" class="cursor-pointer" />
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
          <template v-slot:body-cell-ref="props">
              <q-td key="ref" :props="props">
                <!--            <q-badge color="orange">-->
<!--                <q-badge color="info" v-if="props.row.ref!=''" @click="mostrar(props.row.ref)">-->
                  {{ props.row.ref.substring(0,10) }}
<!--                </q-badge>-->
                <!--            </q-badge>-->
              </q-td>
          </template>
          <template v-slot:body-cell-logs="props">
              <q-td key="logs" :props="props">
                <ul style="font-size: 0.8em;padding: 0px;margin: 0px;border: 0px;    list-style: none;">
                  <li v-for="l in props.row.logs" :key="l.id">
                    <q-btn @click="impresion(l)" v-if="l.estado=='EN PROCESO'" size="xs" icon="print" color="info" flat round/>
                    {{l.unit.nombre}}
                    <q-badge :color="l.estado=='REMITIDO'||l.estado=='ARCHIVADO'?'positive':'negative'" :label="l.estado" />
                  </li>
                </ul>
              </q-td>
          </template>
<!--          <template v-slot:body-cell-dias="props">-->
<!--              <q-td key="dias" :props="props">-->
<!--                <q-badge :color="props.row.dias==0?'positive':props.row.dias==1?'amber':'negative'">-->
<!--                  {{ props.row.dias }} d-->
<!--                </q-badge>-->
<!--              </q-td>-->
<!--          </template>-->
          <template v-slot:body-cell-fecha="props">
              <q-td  :props="props">
<!--                 <div class="text-h6"> {{props.row.fecha}}<br>{{props.row.hora}}</div>-->
              </q-td>
          </template>
          <template v-slot:body-cell-archivo="props">
              <q-td key="archivo" :props="props">
                <template v-if="props.row.archivo!=''">
                  <q-btn label="Descargar" color="primary" size="xs" type="a" :href="url+'/../imagenes/'+props.row.archivo" target="__blank"/>
                </template>
              </q-td>
          </template>
          <template  v-slot:body-cell-opciones="props">
              <q-td auto-width key="opciones" :props="props">
<!--                <q-btn-group>-->
                  <template v-if="props.row.estado=='EN PROCESO'">
                    <q-btn dense @click="aceptar(props.row)" color="info" label="Aceptar" icon="code" size="xs" />
                  </template >
                  <template v-if="props.row.estado=='ARCHIVADO'">
                    <small style="color: darkred;font-weight: bold">Archivado</small>
                  </template >
                <template v-if="props.row.estado=='REMITIDO'">
                  <small style="color: darkgreen;font-weight: bold">Remitido</small>
                </template >
                  <template v-if="props.row.estado=='ACEPTADO'">
                    <q-btn-dropdown color="primary" label="Opciones">
                      <q-list>
                        <q-item clickable v-close-popup @click="modificar(props.row)">
                          <q-item-section>
                            <q-item-label>Modificar Correspodencia</q-item-label>
                          </q-item-section>
                        </q-item>

                        <q-item clickable v-close-popup @click="diaglosasignacion=true;mail=props.row; dest=[]">
                          <q-item-section>
                            <q-item-label>Remitir correspodencia</q-item-label>
                          </q-item-section>
                        </q-item>
<!--                        <q-item clickable v-close-popup @click="impresion(props.row)">-->
<!--                          <q-item-section>-->
<!--                            <q-item-label>Imprimir HR</q-item-label>-->
<!--                          </q-item-section>-->
<!--                        </q-item>-->
                        <q-item clickable v-close-popup @click="archivo(props.row)">
                          <q-item-section>
                            <q-item-label>Subir Archivo</q-item-label>
                          </q-item-section>
                        </q-item>
                        <q-item clickable v-close-popup @click="archivar(props.row)">
                          <q-item-section>
                            <q-item-label>Archivar</q-item-label>
                          </q-item-section>
                        </q-item>
                      </q-list>
                    </q-btn-dropdown>
<!--                    <q-btn dense @click="modificar(props.row)" color="warning" label="Modifica Correspondencia" icon="edit" size="xs" /><br>-->
<!--                    <q-btn dense @click="diaglosasignacion=true;mail=props.row; dest=[]" color="positive" label="Remitir Correspodencia" icon="code" size="xs" /><br>-->
<!--                    <q-btn dense @click="impresion(props.row.mail_id)" color="info" label="Imprimir Hoja Ruta" icon="picture_as_pdf" size="xs" /><br>-->
<!--&lt;!&ndash;                    <q-btn dense @click="impresion2(props.row.mail_id)" color="info" label="IMP" icon="print" size="xs" /><br>&ndash;&gt;-->
<!--                    <q-btn dense @click="archivo(props.row)" color="amber" label="Subir Archivo a nube" icon="upload" size="xs" /><br>-->
<!--                    <q-btn dense @click="archivar(props.row)" color="negative" label="Archivar Correspondencia" icon="list" size="xs" /><br>-->
                  </template>
<!--                </q-btn-group>-->
              </q-td>
          </template>
        </q-table>
        <q-dialog full-width full-height v-model="diaglosasignacion">
          <q-card >
            <q-card-section>
              <div class="text-h6"> <q-icon name="code"/> {{mail.ref}} | Remitir</div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <q-form @submit.prevent="registrarlog">
                <q-input type="textarea" style="text-transform: uppercase" outlined label="Instruccion / Observacion" v-model="miaccion" required/>
<!--                <q-select :options="usuarios" label="Seleccionar personal" v-model="usuario" outlined required/>-->
                <q-select dense use-input :options="usuarios" label="Seleccionar personal" v-model="usuario" @filter="filterFn" outlined >
                  <template v-slot:no-option>
                    <q-item>
                      <q-item-section class="text-grey">
                        Sin resultados
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
                <q-btn color="positive"  label="Agregar Destinatario" icon="add_circle" @click="tabdest"/>
<!--                <table>-->
<!--                  <tbody>-->
<!--                  <tr v-for="(row,index) in dest" :key="index"><td>{{index+1}}- {{row.label}} </td></tr>-->
<!--                  </tbody>-->
<!--                </table>-->
                <q-list dense bordered padding class="rounded-borders">
                  <q-item clickable v-ripple v-for="(row,index) in dest" :key="index">
                    <q-item-section class="q-pa-none text-subtitle2" >
                      {{index+1}} {{row.name}}
<!--                      <pre>{{row}}</pre>-->
                    </q-item-section >
                    <q-item-section class="q-pa-none text-subtitle2">
                      {{row.unit.nombre}}
                    </q-item-section>
                    <q-item-section class="q-pa-none text-subtitle2">
                      <q-btn @click="quitardestinatario(index)" icon="delete" color="negative" flat />
                    </q-item-section>
                  </q-item>
                </q-list>
<!--                {{mail}}-->
                <q-btn label="Remitir" color="teal" icon="send" class="full-width" type="submit"/>
              </q-form>
            </q-card-section>
            <q-card-section align="right">
              <q-btn flat label="Cancelar" color="primary" icon="delete" v-close-popup />
            </q-card-section>
          </q-card>
        </q-dialog>

        <q-dialog v-model="dialogarchivo">
          <q-card style="width: 300px;min-width: 40vh">
            <q-card-section>
              <div class="text-h6"> <q-icon name="list"/> {{mail.ref}} Subir archivo</div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <q-uploader
                class="full-width"
                label="Subir archivo"
                :factory="uploadFile"
                max-files="1"
              />
            </q-card-section>
            <q-card-section align="right">
              <q-btn flat label="Cancelar" color="primary" icon="delete" v-close-popup />
            </q-card-section>
          </q-card>
        </q-dialog>

      </div>
    </div>
  </q-page>
</template>

<script>
// import $ from 'jquery'
import { jsPDF } from "jspdf";
import { format, render, cancel, register } from 'timeago.js';
import $ from 'jquery'
import {date} from 'quasar'
export default {
  data(){
    return {
      destinatario:'',
      destinatarios:'',
      crear:false,
      miaccion:'',
      filter:'',
      usuario:'',
      diaglosasignacion:false,
      dialogarchivo:false,
      url:process.env.API,
      dato:{tipo:'',fecha:date.formatDate(Date.now(),'YYYY-MM-DD'),folio:1},
      //folios:[],
      usuarios:[],
      usuarios2:[],
      mails:[],
      mail:{},
      // log:{},
      remitentes:[],
      remitentes2:[],
      remitente:'',
      cargo:'',
      institucion:'',
      dest:[],
      columns:[
        {name:'opciones',field:'opciones',label:'opciones',align:'right'},
        {name:'codigo',field:'codigo',label:'codigo',align:'left'},
        {name:'citecontrol',field:'citecontrol',label:'CITE',align:'left'},
        {name:'logs',field:'logs',label:'HISTORIAL',align:'left'},
        {name:'remitente',field:'remitente',label:'Remitente',align:'left'},
        {name:'ref',field:'ref',label:'Referencia',align:'left'},
        // {name:'fecha',field:'fecha',label:'fecha',align:'right'},
        // {name:'hora',field:'hora',label:'hora',align:'right'},
        // {field:'codinterno',name:'codinterno',label:'codinterno',align:'right'},
        // {name:'ref',field:'ref',label:'ref',align:'right'},
        // {field:'cargo',name:'cargo',label:'cargo',align:'right'},
        // {field:'institucion',name:'institucion',label:'institucion',align:'right'},
        // {field:'fecha',name:'fecha',label:'fecha',align:'right'},
        // {name:'logs',field:row=>'logs',label:'logs',align:'left'},
        {name:'dias',field:'dias',label:'dias',align:'right'},
        // {field:'estado',name:'estado',label:'estado',align:'right'},
        // {name:'folio',field:'folio',label:'Fojas',align:'right'},
        // {name:'archivo',field:'archivo',label:'archivo',align:'right'},

      ],
      pagination:{
        // sortBy: 'name',
      // descending: false,
        page:1,
        rowsPerPage: 3,
        rowsNumber: 0
      },
      loading:false
    }
  },
  mounted() {

  },
  created() {

    this.misdatos()
    // for (let i=1;i<=1000;i++){
    //   this.folios.push(i)
    // }
    this.misremitentes()
    this.misdestinatarios()
    this.$axios.post(process.env.API+'/misremetentes').then(res=>{
      res.data.forEach(r=>{
        // console.log(r)
        r.label=r.unit.nombre+'-'+r.name
        this.usuarios.push(r)
      })
      this.usuarios2=this.usuarios
    })
  },
  methods:{
    fromcrear(){
      this.crear = true;
      this.dato={tipo:'',fecha:date.formatDate(Date.now(),'YYYY-MM-DD'),folio:1}
      this.remitente=''
      this.cargo=''
      this.institucion=''
    },
    modificar(prop){
      console.log(prop)
      this.crear=true;
      this.dato=prop;
      this.remitente=this.dato.remitente
      this.destinatario=this.dato.destinatario
      this.cargo=this.dato.cargo;
      this.institucion=this.dato.institucion;
    },
    quitardestinatario(i){
      this.dest.splice(i,1)
    },
    tabdest(){
      let verif=false;
      this.dest.forEach(element => {
        if(element.id==this.usuario.id)
          verif=true;
      });
      if(!verif && this.usuario!='')
        this.dest.push(this.usuario);
    },
    filterFn (val, update) {
      if (val === '') {
        update(() => {
          this.usuarios = this.usuarios2

          // here you have access to "ref" which
          // is the Vue reference of the QSelect
        })
        return
      }

      update(() => {
        const needle = val.toLowerCase()
        this.usuarios = this.usuarios2.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
      })
    },
    uploadFile(files) {
      this.file_path = files[0]
      const data = new FormData()
      data.append('imagen', this.file_path)
      data.append('mail_id', this.mail.mail_id)
      //Replace http://localhost:8000 with your API URL
      this.$q.loading.show()
      this.$axios.post(process.env.API+'/upload', data).then((response) => {
        this.misdatos(this.pagination.page,this.filter,this.pagination.rowsPerPage)
        this.$q.loading.hide()
        // console.log(response.data);
        // this.dato.imagen=response.data;
        this.dialogarchivo=false
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'cloud_done',
          message: 'Imagen subido correctamente!'
        });
      });
    },
    registrarlog(){
      // console.log({
      //   mail_id:this.mail.id,
      //   user_id2:this.usuario.id,
      //   destinatario:this.usuario.name,
      //   unit_id:this.usuario.unit_id,
      //   accion:this.miaccion
      // })
      // return false
      // console.log(this.dest);
      if(this.dest.length==0){
        this.$q.notify({
          message:'Debe seleccionar al menos 1 Dest',
          color:'red',
          icon:'error'
        })
        return false;
      }
      this.$q.loading.show()
      this.$axios.post(process.env.API+'/log',{
        log_id:this.mail.id,
        list_user:this.dest,
        //user_id2:this.usuario.id,
        //destinatario:this.usuario.name,
        //unit_id:this.usuario.unit_id,
        accion:this.miaccion
      }).then(res=>{
        // console.log(res.data)
        this.filter=''
        this.misdatos()
        // this.$q.loading.hide()
        this.diaglosasignacion=false
        this.$q.notify({
          message:'Remitido correctamente!!',
          color:'green',
          icon:'done'
        })
        this.miaccion=''
        this.dest=[]
      }).catch(err=>{
        this.$q.notify({
          message:err.response.data.message,
          color:'red',
          icon:'error'
        })
        this.$q.loading.hide()
      })
    },
    misremitentes(){
      this.$axios.get(process.env.API+'/mail/create').then(res=>{
        // console.log(res.data)
        this.remitentes=res.data
        // this.remitentes2=res.data
      })
    },
    misdestinatarios(){
      this.$axios.get(process.env.API+'/destinatarios').then(res=>{
        console.log("destinatarios: ",res.data)
        this.destinatarios=res.data
        // this.remitentes2=res.data
      })
    },
    cambio(){
      this.cargo=''
      this.institucion=''
      // console.log(this.remitente)
      this.remitentes.find(r=>{
        if (r.remitente===this.remitente){
          // console.log(r.cargo)
          this.cargo=r.cargo
          this.institucion=r.institucion
          return false
        }else{
          // this.cargo=''
          // this.institucion=''
        }
      })
    },
    // remitir(){},
    aceptar(mail){
      // console.log(mail)
      // return false
      this.$q.dialog({
        title:'Seguro de Aceptar?',
        message:'Seguro de aceptar',
        // prompt:{
        //   model:'',
        //   type:'text'
        // },
        cancel:true,
      }).onOk(()=>{
        // console.log(data)
       // this.$q.loading.show()
       this.loading=true
        this.$axios.post(process.env.API+'/aceptar',{mail_id:mail.id}).then(res=>{
          this.misdatos(this.pagination.page,this.filter,this.pagination.rowsPerPage)
          this.$q.notify({
            message: 'Aceptado',
            caption: 'Registro aceptado',
            color: 'green',
            icon:'done'
          });
        })
      })
    },
    mostrar(ref){
      this.$q.dialog({
        title:'Referencia',
        message:ref
      })
    },
    impresion(l){
      // console.log(l)
      this.$q.loading.show()
      this.$axios.get(process.env.API+'/mail/'+l.mail_id).then(res=>{
        // console.log(res.data)
        let m=res.data
        this.$q.loading.hide()
        var doc = new jsPDF()
        // doc.setFont("arial");

        // doc.setFont('Arial');
        // var img = new Image()
        // img.src = 'logo.jpg'
        // doc.addImage(img, 'jpg', 190, 3, 70, 20)
        let x=0
        let y=0
        //INICIO CABEZERA
        doc.setDrawColor(122);
        doc.roundedRect(5, 10, 200, 282, 2, 2, 'S')//principal
        doc.roundedRect(8, 22, 194, 30, 2, 2, 'S')//destinatario 1
        doc.roundedRect(58, 22, 20, 30, 2, 2, 'S')//el logo HR
        doc.roundedRect(183, 11, 20, 8, 1, 1, 'S')//hoja1 hoja de ruta
        doc.setFillColor(158,158,158)
        doc.line(8, 28, 58, 28)//line n
        doc.line(8, 36, 58, 36)//line fecha recepcion
        doc.line(8, 46, 58, 46)//line hora repeccion
        doc.line(78, 26, 202, 26)//line remitente
        doc.line(78, 31, 202, 31)//line entidad
        doc.line(78, 36, 202, 36)//line CITE
        doc.line(78, 41, 202, 41)//line referecnia
        doc.line(78, 46, 202, 46)//line hojas
        doc.roundedRect(70, 5, 70, 10, 2, 2, 'F') // fondo gamo
        doc.setFontSize(35);
        doc.setFont('times', 'bold');
        doc.setTextColor(255,255,255)
        doc.text('GAMO',85,14)
        doc.setTextColor(0,0,0)
        doc.setFontSize(37);
        doc.text('HR',58,38)
        doc.setFontSize(7);
        doc.text('HOJA DE RUTA',59,42)
        doc.setTextColor(158,158,158)
        doc.setFontSize(10);
        doc.text('HOJA 1',187,14)
        doc.setFontSize(7);
        doc.text('HOJA DE RUTA',184,17)
        doc.setTextColor(0,0,0)
        doc.setFontSize(11)
        doc.text('REGISTRO DE CORRESPONDENCIA - HOJA DE RUTA',58,20)
        doc.setFontSize(15)
        doc.text('Nº',10,27)
        doc.setFont(undefined, 'normal');
        doc.text(m.codigo.toString(),40,27,'center')
        doc.setFont('times', 'bold');
        doc.setFontSize(10)
        doc.text(['FECHA DE','RECEPCION:'],10,31)
        doc.setFont(undefined, 'normal');
        doc.text(l.fecha.toString(),45,33,'center')
        doc.setFont('times', 'bold');
        doc.text(['HORA DE','RECEPCION:'],10,40)
        doc.setFont(undefined, 'normal');
        doc.text(l.hora.toString(),45,42,'center')
        doc.setFont('times', 'bold');
        doc.text('CITE:',10,49)
        doc.setFont('times', 'normal');
        doc.setFontSize(14)
        doc.text(m.citecontrol.toString(),45,50,'center')
        doc.setFontSize(10)
        doc.setFont('times', 'bold');
        doc.text('REMITENTE:',80,25)
        doc.setFont('times', 'normal');
        doc.text(m.remitente,152,25,'center')
        doc.setFont('times', 'bold');
        doc.text('ENTIDAD:',80,30)
        doc.setFont('times', 'normal');
        doc.text(m.institucion,152,30,'center')
        doc.setFont('times', 'bold');
        doc.text('CITE:',80,35)
        doc.setFont('times', 'normal');
        doc.setFontSize(8)
        doc.text(m.cite,120,35,'center')
        doc.setFontSize(10)
        doc.setFont('times', 'bold');
        doc.text('HOJAS:',160,35)
        doc.setFont('times', 'normal');
        doc.text(m.folio,188,35,'center')
        doc.setFont('times', 'bold');
        doc.text('REF:',80,40)
        doc.setFont('times', 'normal');
        doc.text([m.ref.substring(0,50),m.ref.substring(50,100)],148,40,'center')
        doc.setFont('times', 'bold');
        doc.text('DESTINATARIO 1:',80,50)
        doc.setFont(undefined, 'normal');
        doc.text(l.user2.name,152,50,'center')
        doc.setFont('times', 'bold');
        //FIN CABEZERA
        //INICIO CORRESPONDENCIA
        let con=2
        for (let i=0;i<3;i++){
          doc.roundedRect(8, 52+i*80, 194, 80, 2, 2, 'S')

          doc.setFontSize(6)
          doc.text(['ATENDRE TRAMITE DE','A CUERDO','A PROCEDIMIENTO','VIGENTE Y NORMATIVA'],21,54+i*80,{align:'center'})
          doc.setFontSize(10)
          doc.text('RESPONDER',21,69+i*80,{align:'center'})
          doc.text('INFORME',21,79+i*80,{align:'center'})
          doc.text('TOME ACCION',21,89+i*80,{align:'center'})
          doc.text('NOTIFICAR',21,99+i*80,{align:'center'})
          doc.setFontSize(6)
          doc.text(['PROCESAR CONFORME','EL PROVEIDO QUE','ANTECEDE'],21,106+i*80,{align:'center'})
          doc.setFontSize(9)
          doc.text(['PROYECTAR','NOTA'],21,117+i*80,{align:'center'})
          doc.text('ARCHIVAR',21,129+i*80,{align:'center'})
          doc.roundedRect(35, 52+i*80, 5, 10, 1, 1, 'S')
          doc.line(8, 62+i*80, 40, 62+i*80)//line1
          doc.roundedRect(35, 62+i*80, 5, 10, 1, 1, 'S')
          doc.line(8, 72+i*80, 40, 72+i*80)//line2
          doc.roundedRect(35, 72+i*80, 5, 10, 1, 1, 'S')
          doc.line(8, 82+i*80, 40, 82+i*80)//line3
          doc.roundedRect(35, 82+i*80, 5, 10, 1, 1, 'S')
          doc.line(8, 92+i*80, 40, 92+i*80)//line4
          doc.roundedRect(35, 92+i*80, 5, 10, 1, 1, 'S')
          doc.line(8, 102+i*80, 40, 102+i*80)//line5
          doc.roundedRect(35, 102+i*80, 5, 10, 1, 1, 'S')
          doc.line(8, 112+i*80, 40, 112+i*80)//line5
          doc.roundedRect(35, 112+i*80, 5, 10, 1, 1, 'S')
          doc.line(8, 122+i*80, 40, 122+i*80)//line6
          doc.roundedRect(35, 122+i*80, 5, 10, 1, 1, 'S')

          doc.text('INSTRUCCIONES:',85,55+i*80,{align:'center'})
          doc.text('_________________',85,55+i*80,{align:'center'})
          if (i==0){
            doc.setFont(undefined, 'normal');
            doc.text(l.accion.toString().substring(0,45),41,61+i*80)
            doc.text(l.accion.toString().substring(45,90),41,66+i*80)
            doc.text(l.accion.toString().substring(90,135),41,71+i*80)
            doc.text(l.accion.toString().substring(135,180),41,76+i*80)
            doc.text(l.accion.toString().substring(180,225),41,81+i*80)
            doc.text(l.accion.toString().substring(225,270),41,86+i*80)
            doc.text(l.accion.toString().substring(270,315),41,91+i*80)
            doc.setFont(undefined, 'bold');
          }


          doc.text('.................................................................................................................',40,62+i*80)
          doc.text('.................................................................................................................',40,67+i*80)
          doc.text('.................................................................................................................',40,72+i*80)
          doc.text('.................................................................................................................',40,77+i*80)
          doc.text('.................................................................................................................',40,82+i*80)
          doc.text('.................................................................................................................',40,87+i*80)
          doc.text('.................................................................................................................',40,92+i*80)
          doc.text('.................................................................................................................',40,97+i*80)
          doc.text('.................................................................................................................',40,102+i*80)
          doc.text('.................................................................................................................',40,107+i*80)
          doc.text('.................................................................................................................',40,112+i*80)
          doc.text('.................................................................................................................',40,117+i*80)
          doc.text('.................................................................................................................',40,122+i*80)
          doc.text('.................................................................................................................',40,127+i*80)
          doc.text('FIRMAR',115,130+i*80)

          doc.roundedRect(130, 52+i*80, 72, 80, 1, 1, 'S')
          doc.line(130, 59+i*80, 202, 59+i*80)//line1
          doc.line(130, 126+i*80, 202, 126+i*80)//line2
          doc.text('DESTINATARIO '+con+':',132,57+i*80)
          doc.text('SELLO DE RECEPCION '+con+':',150,125+i*80)
          doc.text('FECHA:',132,130+i*80)
          doc.text('HORA:',172,130+i*80)
          con++
        }




///////////////////////////////////////
        // doc.addPage();
        // doc.setFont('times', 'bold');
        // doc.setDrawColor(122);
        // doc.roundedRect(5, 10, 200, 282, 2, 2, 'S')//principal
        // doc.roundedRect(183, 11, 20, 8, 1, 1, 'S')//hoja1 hoja de ruta
        // doc.setTextColor(158,158,158)
        // doc.setFontSize(10);
        // doc.text('HOJA 2',187,14)
        // doc.setFontSize(7);
        // doc.text('HOJA DE RUTA',184,17)
        // doc.setTextColor(0,0,0)


        // con=5

        // for (let i=0;i<4;i++){
        //   // doc.roundedRect(8, 52+i*80, 194, 80, 2, 2, 'S')
        //   doc.roundedRect(8, 20+i*68, 194, 68, 2, 2, 'S')

        //   doc.setFontSize(5)
        //   doc.text(['ATENDRE TRAMITE DE','A CUERDO','A PROCEDIMIENTO','VIGENTE Y NORMATIVA'],21,22+i*68,{align:'center'})
        //   doc.setFontSize(9)
        //   doc.text('RESPONDER',21,34+i*68,{align:'center'})
        //   doc.text('INFORME',21,43+i*68,{align:'center'})
        //   doc.text('TOME ACCION',21,51+i*68,{align:'center'})
        //   doc.text('NOTIFICAR',21,60+i*68,{align:'center'})
        //   doc.setFontSize(6)
        //   doc.text(['PROCESAR CONFORME','EL PROVEIDO QUE','ANTECEDE'],21,65+i*68,{align:'center'})
        //   doc.setFontSize(9)
        //   doc.text(['PROYECTAR','NOTA'],21,75+i*68,{align:'center'})
        //   doc.text('ARCHIVAR',21,85+i*68,{align:'center'})
        //   doc.roundedRect(35, 20+i*68, 5, 8.5, 1, 1, 'S')
        //   doc.roundedRect(35, 28.5+i*68, 5, 8.5, 1, 1, 'S')
        //   doc.roundedRect(35, 37+i*68, 5, 8.5, 1, 1, 'S')
        //   doc.roundedRect(35, 45.5+i*68, 5, 8.5, 1, 1, 'S')
        //   doc.roundedRect(35, 54+i*68, 5, 8.5, 1, 1, 'S')
        //   doc.roundedRect(35, 62.5+i*68, 5, 8.5, 1, 1, 'S')
        //   doc.roundedRect(35, 71+i*68, 5, 8.5, 1, 1, 'S')
        //   doc.roundedRect(35, 79.5+i*68, 5, 8.5, 1, 1, 'S')
        //   doc.line(8, 28.5+i*68, 40, 28.5+i*68)//line1
        //   doc.line(8, 37+i*68, 40, 37+i*68)//line2
        //   doc.line(8, 45.5+i*68, 40, 45.5+i*68)//line3
        //   doc.line(8, 54+i*68, 40, 54+i*68)//line4
        //   doc.line(8, 62.5+i*68, 40, 62.5+i*68)//line5
        //   doc.line(8, 71+i*68, 40, 71+i*68)//line5
        //   doc.line(8, 79.5+i*68, 40, 79.5+i*68)//line6

        //   doc.text('INSTRUCCIONES:',85,23+i*68,{align:'center'})
        //   doc.text('_________________',85,23+i*68,{align:'center'})
        //   doc.text('.................................................................................................................',40,28.5+i*68)
        //   doc.text('.................................................................................................................',40,33+i*68)
        //   doc.text('.................................................................................................................',40,37+i*68)
        //   doc.text('.................................................................................................................',40,41.5+i*68)
        //   doc.text('.................................................................................................................',40,45.5+i*68)
        //   doc.text('.................................................................................................................',40,50+i*68)
        //   doc.text('.................................................................................................................',40,54+i*68)
        //   doc.text('.................................................................................................................',40,58.5+i*68)
        //   doc.text('.................................................................................................................',40,62.5+i*68)
        //   doc.text('.................................................................................................................',40,67+i*68)
        //   doc.text('.................................................................................................................',40,71+i*68)
        //   doc.text('.................................................................................................................',40,75.5+i*68)
        //   doc.text('.................................................................................................................',40,79.5+i*68)
        //   doc.text('.................................................................................................................',40,84+i*68)
        //   doc.text('FIRMAR',115,87+i*68)

        //   doc.roundedRect(130, 20+i*68, 72, 68, 1, 1, 'S')
        //   doc.line(130, 24+i*68, 202, 24+i*68)//line1
        //   doc.line(130, 84+i*68, 202, 84+i*68)//line2
        //   doc.text('DESTINATARIO '+con+':',132,23+i*68)
        //   doc.text('SELLO DE RECEPCION '+con+':',150,83+i*68)
        //   doc.text('FECHA:',132,87+i*68)
        //   doc.text('HORA:',172,87+i*68)
        //   con++
        // }
/////////////////////////////




        // $( '#docpdf' ).attr('src', doc.output('datauristring'));
        // // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
        window.open(doc.output('bloburl'), '_blank');
      })
      // console.log(c)

      // return false
      // this.$axios.post(process.env.API+'/impruta/'+id).then(res=>{
      //   console.log(res.data);
      //   let myWindow = window.open("", "Imprimir", "width=1000,height=1000");
      //   myWindow.document.write(res.data);
      //   myWindow.document.close();
      //   myWindow.focus();
      //   setTimeout(function(){
      //     myWindow.print();
      //     myWindow.close();
      //   },700);
      // })
    },
        impresion2(id){
      // console.log(id)
      // return false
      this.$axios.post(process.env.API+'/impblanco/'+id).then(res=>{
        console.log(res.data);
        let myWindow = window.open("", "Imprimir", "width=1000,height=1000");
        myWindow.document.write(res.data);
        myWindow.document.close();
        myWindow.focus();
        setTimeout(function(){
          myWindow.print();
          myWindow.close();
        },700);
      })
    },
    archivar(mail){
      this.$q.dialog({
        title:'Seguro de archivar?',
         message:'Motivo de archivar',
         prompt:{
           model:'',
           type:'text'
         },
        cancel:true,
      }).onOk(data=>{
        // console.log(mail)
        this.$q.loading.show()
        this.$axios.post(process.env.API+'/anulado',{id:mail.id,archivado:data}).then(res=>{
          // console.log(res.data)
          this.misdatos();
          this.$q.notify({
            message: 'Archivado',
            caption: 'Registro archivado',
            color: 'green',
            icon:'done'
          });
        })
      })
    },
    archivo(mail){
      console.log(mail)
      this.mail=mail
      this.dialogarchivo=true
    },
    dividir(mail){
      this.$q.dialog({
        title:'En cuantos deseas dividir?',
        message:'Cantidad de divicion',
        prompt:{
          model:'',
          type:'number'
        },
        cancel:true,
      }).onOk(data=>{
        // console.log(data)
        this.$q.loading.show()
        this.$axios.post(process.env.API+'/dividir',{cantidad:data,mail_id:mail.id}).then(res=>{
          this.misdatos();
          this.$q.notify({
            message: 'Dividido',
            caption: 'Registro dividido',
            color: 'green',
            icon:'done'
          });
        })
      })
    },
    editar(props){
      // console.log(props.row);
      this.dato=props.row;
      this.remitente=props.row.remitente
      this.cargo=props.row.cargo
      this.institucion=props.row.institucion
    },
    // imprimir(){
    //   let cm=this;
    //   function header(fecha){
    //     // var img = new Image()
    //     // img.src = 'logo.jpg'
    //     // doc.addImage(img, 'jpg', 0.5, 0.5, 2, 2)
    //     doc.setFont(undefined,'bold')
    //     doc.text(5, 1, 'RESUMEN DIARIO DE INGRESOS')
    //     doc.text(5, 1.5, ' '+fecha)
    //     doc.text(1, 3, 'Nº COMPROBANTE')
    //     doc.text(4, 3, 'Nº TRAMITE')
    //     doc.text(7, 3, 'CONTRIBUYENTE')
    //     doc.text(13.5, 3, 'CI/RUN/RUC')
    //     doc.text(16, 3, 'MONTO BS.')
    //     doc.text(18, 3, 'CAJERO')
    //     doc.setFont(undefined,'normal')
    //   }
    //   var doc = new jsPDF('p','cm','letter')
    //   // console.log(dat);
    //   doc.setFont("courier");
    //   doc.setFontSize(9);
    //   // var x=0,y=
    //   header(Date.now())
    //   // let xx=x
    //   // let yy=y
    //   let y=0
    //   // this.pagos.forEach(r=>{
    //   //   // xx+=0.5
    //   //   y+=0.5
    //   //   doc.text(1, y+3, r.nrocomprobante)
    //   //   doc.text(4, y+3, r.nrotramite)
    //   //   doc.text(7, y+3, r.cliente)
    //   //   doc.text(13.5, y+3, r.ci)
    //   //   doc.text(16, y+3, r.total)
    //   //   doc.text(18, y+3, r.cajero )
    //   //   if (y+3>25){
    //   //     doc.addPage();
    //   //     header(this.fecha)
    //   //     y=0
    //   //   }
    //   // })
    //   doc.text(12, y+4, 'TOTAL RECAUDADCION: ')
    //   doc.text(18, y+4, '1000 Bs')
    //   // doc.save("Pago"+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
    //   window.open(doc.output('bloburl'), '_blank');
    // },
    // filterFn (val, update) {
    //   // console.log(val)
    //   if (val === '') {
    //     update(() => {
    //       this.remitentes = this.remitentes2
    //       this.remitente=''
    //       // here you have access to "ref" which
    //       // is the Vue reference of the QSelect
    //     })
    //     return false
    //   }
    //
    //   update(() => {
    //     const needle = val.toLowerCase()
    //     this.remitentes = this.remitentes2.filter(v => v.remitente.toLowerCase().indexOf(needle) > -1)
    //   })
    // },
    actualizar(){
        this.filter=''
        this.misdatos()
    },
    misdatos(page = 0,filter,rowsPerPage=10){
      //this.$q.loading.show()
      this.loading=true
      this.$axios.get(process.env.API+'/micorre',{params:{page: page,filter:filter,rowsPerPage: rowsPerPage }}).then(res=>{
          console.log('micorre:',res)
        // this.mails=res.data
        this.mails=[]
        register('es_ES', (number, index, total_sec) => [
          ['justo ahora', 'ahora mismo'],
          ['hace %s segundos', 'en %s segundos'],
          ['hace 1 minuto', 'en 1 minuto'],
          ['hace %s minutos', 'en %s minutos'],
          ['hace 1 hora', 'en 1 hora'],
          ['hace %s horas', 'in %s horas'],
          ['hace 1 dia', 'en 1 dia'],
          ['hace %s dias', 'en %s dias'],
          ['hace 1 semana', 'en 1 semana'],
          ['hace %s semanas', 'en %s semanas'],
          ['1 mes', 'en 1 mes'],
          ['hace %s meses', 'en %s meses'],
          ['hace 1 año', 'en 1 año'],
          ['hace %s años', 'en %s años']
        ][index]);

        // console.log( format(Date.now() - 11 * 1000 * 60 * 60,'es_ES'))
        this.pagination.page = res.data.current_page
        this.pagination.rowsPerPage = res.data.per_page
        this.pagination.rowsNumber = res.data.total
        console.log('pagination',this.pagination)
        res.data.data.forEach(r=>{
          //console.log(r)
          // const date1 = new Date()
          // const date2 = date.extractDate(r.mail.fecha, 'YYYY-MM-DD')
          // const dias = date.getDateDiff(date1, date2, 'days')
          const dias=format(r.mail.fecha+' '+r.mail.hora,'es_ES')
          this.mails.push({
            id:r.id,
            mail_id:r.mail_id,
            hora:r.hora,
            citecontrol:r.mail.citecontrol,
            cite:r.mail.cite,
            codigo:r.mail.codigo,
            tipo:r.mail.tipo,
            tipo2:r.mail.tipo2,
            ref:r.mail.ref,
            remitente:r.mail.remitente,
            destinatario:r.mail.destinatario,
            cargo:r.mail.cargo,
            institucion:r.mail.institucion,
            fecha:r.mail.fecha,
            fechacarta:r.mail.fechacarta,
            estado:r.estado,
            folio:r.mail.folio,
            archivo:r.mail.archivo,
            codinterno:r.mail.codinterno,
            codexterno:r.mail.codexterno,
            logs:r.mail.logs,
            dias:dias,
          })

        })
        this.loading=false
        this.$q.loading.hide()
      })
    },
    handleRequest(props){
        console.log('halderesquest: ',props)
        if(props.pagination.rowsPerPage===0){
          props.pagination.rowsPerPage=this.pagination.rowsNumber
        }
        this.misdatos(props.pagination.page,props.filter,props.pagination.rowsPerPage)
    },
    buscar(){
      //handleRequest()
      console.log('filter:',this.filter)
      this.misdatos(this.pagination.page,this.filter,this.pagination.rowsPerPage)
    },
    anular(mail){
      this.$q.dialog({
        title:'Seguro de anular?',
        message:'Motivo de anular',
        prompt:{
          model:'',
          type:'text'
        },
        cancel:true,
      }).onOk(data=>{
        // console.log(data)
        this.$q.loading.show()
        this.$axios.post(process.env.API+'/anulado',{mail_id:mail.id,accion:data,estado:'ANULADO'}).then(res=>{
          this.misdatos();
          this.$q.notify({
            message: 'Anulado',
            caption: 'Registro anulado',
            color: 'green',
            icon:'done'
          });
        })
      })
    },
    guardar(){
      if (this.dato.tipo==undefined||this.dato.tipo==null||this.dato.tipo==''){
        this.$q.notify({
          message:'Porfavor colocar interno o externo',
          color:'red',
          icon:'error'
        })
        return false
      }
      this.$q.dialog({
        title:"Seguro de registrar?",
        // message:"",
        cancel:true,
        persistent:true
      }).onOk(()=>{
        // if (!confirm("seguro de registrar?")){
        //   return false
        // }
        // console.log(this.remitente)
        // return false
        this.dato.remitente=this.remitente
        this.dato.destinatario=this.destinatario
        this.dato.cargo=this.cargo
        this.dato.institucion=this.institucion
        if(this.dato.id==undefined || this.dato.id==''){
          // console.log('new')
          this.$q.loading.show()
          this.$axios.post(process.env.API+'/mail',this.dato).then(res=>{
            console.log(res.data)
            this.dato={tipo:'EXTERNO',fecha:date.formatDate(Date.now(),'YYYY-MM-DD'),folio:1};
            this.remitente=''
            this.destinatario=''
            this.cargo=''
            this.institucion=''
            this.filter=''
            this.misdatos()
            this.misremitentes()
            this.misdestinatarios()
            this.crear=false

            // this.$q.loading.hide()
          }).catch(err=>{
            console.log("error",err)
            this.$q.loading.hide()
            this.$q.notify({
              message:err.response.data.message,
              color:'red',
              icon:'error'
            })
          })}
        else{
          this.$q.loading.show()
          this.$axios.post(process.env.API+'/updatemail',this.dato).then(res=>{
            console.log("RES.DATA",res.data)
            //return false
            this.dato={tipo:'EXTERNO',fecha:date.formatDate(Date.now(),'YYYY-MM-DD'),folio:1};
            this.remitente=''
            this.destinatario=''
            this.cargo=''
            this.institucion=''
            // console.log(res.data)
            this.misdatos(this.pagination.page,this.filter,this.pagination.rowsPerPage)
            this.misremitentes()
            this.misdestinatarios()
            this.crear=false

            // this.$q.loading.hide()
          }).catch(err=>{
            this.$q.loading.hide()
            this.$q.notify({
              message:err.response.data.message,
              color:'red',
              icon:'error'
            })
          })
        }
      })

    }
  }

}
</script>

<style scoped>

</style>
