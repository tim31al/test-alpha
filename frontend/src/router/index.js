import Vue from 'vue'
import VueRouter from 'vue-router'
import {appRoute} from '@/const'


Vue.use(VueRouter)

const routes = [
  {
    path: appRoute.HOME,
    name: 'Home',
    component: () => import('@/views/Home')
  },
  {
    path: appRoute.PRODUCT_CATEGORY,
    component: () => import('@/views/ProductCategory'),
    children: [
      {
        path: "",
        component: () =>
          import("@/modules/product-category/ProductCategoryList"),
        name: "ProductCategoryList",
      },
      {
        path: `:id`,
        component: () => import("@/modules/product-category/ProductCategoryShow"),
        name: "ProductCategoryShow",
      }
    ]
  },
  {
    path: "*",
    name: "NotFound",
    component: () => import("@/views/NotFound"),
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
