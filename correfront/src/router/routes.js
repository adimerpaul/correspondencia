import Login from "pages/Login";
import Recepcion from "pages/Recepcion";
import User from "pages/User";
import Seguimiento from "pages/Seguimiento";
import Asignacion from "pages/Asignacion";

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: Login },
      { path: '/recepcion', component: Recepcion,meta: {requiresAuth: true,} },
      { path: '/user', component: User,meta: {requiresAuth: true,} },
      { path: '/seguimiento', component: Seguimiento,meta: {requiresAuth: true,} },
      { path: '/asiganacion', component: Asignacion,meta: {requiresAuth: true,} },
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
