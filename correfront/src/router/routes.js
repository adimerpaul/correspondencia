import Login from "pages/Login";
import Recepcion from "pages/Recepcion";
import User from "pages/User";
import Seguimiento from "pages/Seguimiento";
import Asignacion from "pages/Asignacion";
import Misrecepciones from "pages/Misrecepciones";
import MainLayout from "layouts/MainLayout";
import Consulta from "pages/Consulta";
import MainLayoutPublico from "layouts/MainLayoutPublico";
import Reportecorrespondencia from "pages/Reportecorrespondencia";

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: Login },
      { path: '/recepcion', component: Recepcion,meta: {requiresAuth: true,} },
      { path: '/user', component: User,meta: {requiresAuth: true,} },
      { path: '/seguimiento', component: Seguimiento,meta: {requiresAuth: true,} },
      { path: '/asignacion', component: Asignacion,meta: {requiresAuth: true,} },
      { path: '/misrecepciones', component: Misrecepciones,meta: {requiresAuth: true,} },
      { path: '/reportecorrespondencia', component: Reportecorrespondencia,meta: {requiresAuth: true,} },
    ]
  },
  {
    path: '/consulta',
    component: MainLayoutPublico,
    children: [
      { path: '', component: Consulta },
    ]
  },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
