import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchTasks(ctx, payload) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/apps/todo/tasks', { params: payload })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    addTask(ctx, taskData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/apps/todo/tasks', { task: taskData })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateTask(ctx, { task }) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/apps/todo/tasks/xyxy/'+task.id, { task })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    removeTask(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`api/apps/todo/tasks/xyxy/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    staffList(ctx){
      return new Promise((resolve, reject)=>{
        axios
          .get(`api/client/staffList`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    }
  },
}
